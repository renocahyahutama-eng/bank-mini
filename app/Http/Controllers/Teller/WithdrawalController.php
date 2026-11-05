<?php

namespace App\Http\Controllers\Teller;

use App\Http\Controllers\Controller;
use App\Models\AccountingJournal;
use App\Models\Nasabah;
use App\Models\Transaction;
use App\Models\TransactionAuthorization;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WithdrawalController extends Controller
{
    const MINIMUM_BALANCE = 10000; // Rp 10.000

    public function create(Request $request)
    {
        $nasabah = null;
        if ($request->has('nasabah_id')) {
            $nasabah = Nasabah::where('id', $request->nasabah_id)
                ->where('status', 'Aktif')
                ->first();
        }

        $nasabahs = Nasabah::where('status', 'Aktif')->orderBy('student_name')->get();

        return view('teller.withdrawal.create', compact('nasabah', 'nasabahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nasabah_id' => 'required|exists:nasabahs,id',
            'amount' => [
                'required',
                'numeric',
                'min:1000',
                function ($attribute, $value, $fail) {
                    if (fmod((float) $value, 1000) != 0) {
                        $fail('Nominal penarikan harus dalam kelipatan ribuan penuh (contoh: Rp 1.000, Rp 2.000, Rp 5.000), tidak boleh menggunakan pecahan seperti 1 rupiah atau 500 rupiah.');
                    }
                },
            ],
            'security_pin' => 'required|digits:6',
            'description' => 'nullable|string|max:255',
        ], [
            'amount.min' => 'Nominal penarikan minimal Rp 1.000 (tidak boleh 1 rupiah atau 500 rupiah).',
            'amount.numeric' => 'Nominal penarikan harus berupa angka.',
            'amount.required' => 'Nominal penarikan wajib diisi.',
            'security_pin.required' => 'PIN keamanan wajib diisi.',
            'security_pin.digits' => 'PIN keamanan harus 6 digit angka.',
        ]);

        $nasabah = Nasabah::findOrFail($request->nasabah_id);

        if ($nasabah->status !== 'Aktif') {
            return back()->with('error', 'Rekening nasabah tidak aktif.')->withInput();
        }

        // Verify security PIN
        if (!Hash::check($request->security_pin, $nasabah->security_pin)) {
            return back()->with('error', 'PIN keamanan salah. Penarikan ditolak.')->withInput();
        }

        $amount = $request->amount;
        $balanceBefore = $nasabah->balance;
        $balanceAfter = $balanceBefore - $amount;

        // Check minimum balance (saldo mengendap)
        if ($balanceAfter < self::MINIMUM_BALANCE) {
            $maxWithdrawal = $balanceBefore - self::MINIMUM_BALANCE;
            return back()->with('error',
                "Saldo tidak mencukupi. Saldo saat ini: Rp " . number_format($balanceBefore, 0, ',', '.') .
                ". Saldo mengendap minimum: Rp " . number_format(self::MINIMUM_BALANCE, 0, ',', '.') .
                ". Penarikan maksimal: Rp " . number_format(max(0, $maxWithdrawal), 0, ',', '.')
            )->withInput();
        }

        DB::beginTransaction();
        try {
            // Create transaction
            $transaction = Transaction::create([
                'nasabah_id' => $nasabah->id,
                'user_id' => auth()->id(),
                'transaction_type' => 'Withdrawal',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $request->description,
            ]);

            // Update nasabah balance
            $nasabah->update(['balance' => $balanceAfter]);

            // Create double-entry journal (reverse of deposit)
            AccountingJournal::createWithdrawalEntries($transaction);

            DB::commit();

            return redirect()->route('teller.transactions.index')
                ->with('success', "Penarikan berhasil! Rp " . number_format($amount, 0, ',', '.') . " dari rekening {$nasabah->account_number} ({$nasabah->student_name})");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses penarikan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Teller requests PIN authorization from Nasabah's Mobile App.
     */
    public function requestPin(Request $request): JsonResponse
    {
        $request->validate([
            'nasabah_id' => 'required|exists:nasabahs,id',
            'amount' => [
                'required',
                'numeric',
                'min:1000',
                function ($attribute, $value, $fail) {
                    if (fmod((float) $value, 1000) != 0) {
                        $fail('Nominal penarikan harus dalam kelipatan ribuan penuh (contoh: Rp 1.000, Rp 2.000, Rp 5.000), tidak boleh menggunakan pecahan seperti 1 rupiah atau 500 rupiah.');
                    }
                },
            ],
            'description' => 'nullable|string|max:255',
        ], [
            'amount.min' => 'Nominal penarikan minimal Rp 1.000 (tidak boleh 1 rupiah atau 500 rupiah).',
            'amount.numeric' => 'Nominal penarikan harus berupa angka.',
            'amount.required' => 'Nominal penarikan wajib diisi.',
        ]);

        $nasabah = Nasabah::findOrFail($request->nasabah_id);

        if ($nasabah->status !== 'Aktif') {
            return response()->json(['success' => false, 'message' => 'Rekening nasabah tidak aktif.'], 422);
        }

        $amount = (float) $request->amount;
        $balanceBefore = (float) $nasabah->balance;
        $balanceAfter = $balanceBefore - $amount;

        if ($balanceAfter < self::MINIMUM_BALANCE) {
            $maxWithdrawal = max(0, $balanceBefore - self::MINIMUM_BALANCE);
            return response()->json([
                'success' => false,
                'message' => 'Saldo tidak mencukupi. Maksimal penarikan: Rp ' . number_format($maxWithdrawal, 0, ',', '.')
            ], 422);
        }

        // Cancel previous pending authorizations for this nasabah
        TransactionAuthorization::where('nasabah_id', $nasabah->id)
            ->where('status', 'pending')
            ->update(['status' => 'expired']);

        // Create new authorization
        $auth = TransactionAuthorization::create([
            'nasabah_id' => $nasabah->id,
            'user_id' => auth()->id(),
            'transaction_type' => 'Withdrawal',
            'amount' => $amount,
            'description' => $request->description ?: 'Penarikan Tunai di Loket Teller',
            'status' => 'pending',
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        return response()->json([
            'success' => true,
            'authorization_id' => $auth->id,
            'message' => 'Permintaan otorisasi PIN telah dikirim ke aplikasi mobile nasabah ' . $nasabah->student_name . ' (' . $nasabah->account_number . ').',
        ]);
    }

    /**
     * Teller polls status of the PIN authorization request.
     */
    public function checkPinStatus(int|string $id): JsonResponse
    {
        $auth = TransactionAuthorization::find($id);

        if (!$auth) {
            return response()->json(['success' => false, 'status' => 'not_found', 'message' => 'Permintaan tidak ditemukan.'], 404);
        }

        // Check if expired
        if ($auth->status === 'pending' && $auth->expires_at && Carbon::now()->isAfter($auth->expires_at)) {
            $auth->update(['status' => 'expired']);
        }

        return response()->json([
            'success' => true,
            'status' => $auth->status,
            'transaction_id' => $auth->transaction_id,
            'message' => match ($auth->status) {
                'pending' => 'Menunggu nasabah memasukkan PIN di aplikasi Flutter...',
                'approved' => 'Nasabah telah mengonfirmasi PIN. Penarikan berhasil diproses!',
                'rejected' => 'Nasabah menolak transaksi penarikan di aplikasi Flutter.',
                'expired' => 'Permintaan otorisasi telah kedaluwarsa.',
                default => 'Status: ' . $auth->status,
            },
        ]);
    }

    /**
     * Teller cancels PIN authorization request.
     */
    public function cancelPinRequest(int|string $id): JsonResponse
    {
        $auth = TransactionAuthorization::find($id);
        if ($auth && $auth->status === 'pending') {
            $auth->update(['status' => 'rejected']);
        }

        return response()->json(['success' => true, 'message' => 'Permintaan otorisasi PIN dibatalkan.']);
    }
}
