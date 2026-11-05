<?php

namespace App\Http\Controllers\Api\Nasabah;

use App\Http\Controllers\Api\ApiController;
use App\Models\AccountingJournal;
use App\Models\Transaction;
use App\Models\TransactionAuthorization;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthorizationController extends ApiController
{
    const MINIMUM_BALANCE = 10000; // Rp 10.000 saldo mengendap

    /**
     * Check if there is any pending transaction authorization requiring customer PIN.
     */
    public function pending(Request $request): JsonResponse
    {
        $nasabah = $this->getNasabah($request);

        if (!$nasabah || $nasabah->status !== 'Aktif') {
            return $this->errorResponse('Rekening nasabah tidak aktif.', 403);
        }

        $pending = TransactionAuthorization::with('teller:id,name')
            ->where('nasabah_id', $nasabah->id)
            ->where('status', 'pending')
            ->where('expires_at', '>', Carbon::now())
            ->latest('id')
            ->first();

        if (!$pending) {
            return $this->successResponse(null, 'Tidak ada transaksi yang menunggu otorisasi PIN.');
        }

        $remainingSeconds = max(0, Carbon::now()->diffInSeconds($pending->expires_at, false));

        return $this->successResponse([
            'id' => $pending->id,
            'transaction_type' => $pending->transaction_type,
            'type_label' => $pending->transaction_type === 'Withdrawal' ? 'Penarikan Tunai' : 'Transaksi Loket',
            'amount' => (float) $pending->amount,
            'amount_formatted' => 'Rp ' . number_format((float) $pending->amount, 0, ',', '.'),
            'description' => $pending->description ?: 'Penarikan tunai di loket teller',
            'teller_name' => $pending->teller->name ?? 'Petugas Teller',
            'created_at' => $pending->created_at->format('Y-m-d H:i:s'),
            'created_at_formatted' => $pending->created_at->translatedFormat('d M Y, H:i') . ' WIB',
            'remaining_seconds' => (int) $remainingSeconds,
        ], 'Terdapat 1 transaksi menunggu otorisasi PIN.');
    }

    /**
     * Approve pending transaction by submitting 6-digit PIN from mobile app.
     */
    public function approve(Request $request, int|string $id): JsonResponse
    {
        $nasabah = $this->getNasabah($request);

        if (!$nasabah || $nasabah->status !== 'Aktif') {
            return $this->errorResponse('Rekening nasabah tidak aktif.', 403);
        }

        $validator = Validator::make($request->all(), [
            'pin' => 'required|digits:6',
        ], [
            'pin.required' => 'PIN keamanan wajib diisi.',
            'pin.digits'   => 'PIN harus terdiri dari 6 digit angka.',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        $auth = TransactionAuthorization::where('id', $id)
            ->where('nasabah_id', $nasabah->id)
            ->first();

        if (!$auth) {
            return $this->errorResponse('Permintaan otorisasi tidak ditemukan.', 404);
        }

        if ($auth->status !== 'pending' || ($auth->expires_at && Carbon::now()->isAfter($auth->expires_at))) {
            return $this->errorResponse('Permintaan transaksi sudah tidak berlaku atau telah kedaluwarsa.', 422);
        }

        // Verify PIN
        if (!Hash::check($request->input('pin'), $nasabah->security_pin)) {
            return $this->errorResponse('PIN keamanan yang Anda masukkan salah.', 422, [
                'pin' => ['PIN keamanan nasabah tidak cocok.'],
            ]);
        }

        $amount = (float) $auth->amount;
        $balanceBefore = (float) $nasabah->balance;
        $balanceAfter = $balanceBefore - $amount;

        // Check minimum balance
        if ($balanceAfter < self::MINIMUM_BALANCE) {
            $maxWithdrawal = $balanceBefore - self::MINIMUM_BALANCE;
            return $this->errorResponse(
                "Saldo tidak mencukupi. Saldo saat ini: Rp " . number_format($balanceBefore, 0, ',', '.') .
                ". Saldo mengendap minimum: Rp " . number_format(self::MINIMUM_BALANCE, 0, ',', '.') .
                ". Penarikan maksimal: Rp " . number_format(max(0, $maxWithdrawal), 0, ',', '.'),
                422
            );
        }

        DB::beginTransaction();
        try {
            // Create Transaction record
            $transaction = Transaction::create([
                'nasabah_id' => $nasabah->id,
                'user_id' => $auth->user_id,
                'transaction_type' => $auth->transaction_type,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $auth->description ?: 'Penarikan tunai via Mobile PIN Authorization',
            ]);

            // Update balance
            $nasabah->update(['balance' => $balanceAfter]);

            // Create double-entry journal
            AccountingJournal::createWithdrawalEntries($transaction);

            // Update Authorization status
            $auth->update([
                'status' => 'approved',
                'transaction_id' => $transaction->id,
                'verified_at' => Carbon::now(),
            ]);

            DB::commit();

            return $this->successResponse([
                'transaction_id' => $transaction->id,
                'transaction_code' => 'TX-' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT),
                'amount' => $amount,
                'amount_formatted' => 'Rp ' . number_format($amount, 0, ',', '.'),
                'balance_after' => $balanceAfter,
                'balance_after_formatted' => 'Rp ' . number_format($balanceAfter, 0, ',', '.'),
                'message' => 'Otorisasi PIN berhasil. Transaksi telah diproses oleh Teller.',
            ], 'Otorisasi PIN transaksi berhasil dikonfirmasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Gagal menyelesaikan transaksi: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Reject pending transaction by customer.
     */
    public function reject(Request $request, int|string $id): JsonResponse
    {
        $nasabah = $this->getNasabah($request);

        if (!$nasabah || $nasabah->status !== 'Aktif') {
            return $this->errorResponse('Rekening nasabah tidak aktif.', 403);
        }

        $auth = TransactionAuthorization::where('id', $id)
            ->where('nasabah_id', $nasabah->id)
            ->first();

        if (!$auth) {
            return $this->errorResponse('Permintaan otorisasi tidak ditemukan.', 404);
        }

        if ($auth->status !== 'pending') {
            return $this->errorResponse('Transaksi sudah diproses sebelumnya.', 422);
        }

        $auth->update([
            'status' => 'rejected',
        ]);

        return $this->successResponse(null, 'Permintaan transaksi berhasil ditolak.');
    }
}

