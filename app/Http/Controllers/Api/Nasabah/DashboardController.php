<?php

namespace App\Http\Controllers\Api\Nasabah;

use App\Http\Controllers\Api\ApiController;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends ApiController
{
    /**
     * Get Dashboard Summary for Nasabah Mobile App.
     */
    public function index(Request $request): JsonResponse
    {
        $nasabah = $this->getNasabah($request);

        if (!$nasabah || $nasabah->status !== 'Aktif') {
            return $this->errorResponse('Rekening nasabah tidak aktif.', 403);
        }

        $today = Carbon::today();
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // ── Transaksi Hari Ini ──
        $todayTransactions = Transaction::with('user:id,name')
            ->where('nasabah_id', $nasabah->id)
            ->whereDate('created_at', $today)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($tx) {
                return [
                    'id' => $tx->id,
                    'transaction_code' => 'TX-' . str_pad($tx->id, 6, '0', STR_PAD_LEFT),
                    'type' => $tx->transaction_type,
                    'type_label' => $tx->transaction_type === 'Deposit' ? 'Setoran Tunai' : 'Penarikan Tunai',
                    'amount' => (float) $tx->amount,
                    'amount_formatted' => ($tx->transaction_type === 'Deposit' ? '+ ' : '- ') . 'Rp ' . number_format((float) $tx->amount, 0, ',', '.'),
                    'balance_after' => (float) $tx->balance_after,
                    'balance_after_formatted' => 'Rp ' . number_format((float) $tx->balance_after, 0, ',', '.'),
                    'description' => $tx->description ?: ($tx->transaction_type === 'Deposit' ? 'Setoran tabungan via Teller' : 'Penarikan tabungan via Teller'),
                    'teller_name' => $tx->user->name ?? 'Petugas Teller',
                    'date' => $tx->created_at->format('Y-m-d H:i:s'),
                    'date_formatted' => $tx->created_at->translatedFormat('d M Y, H:i') . ' WIB',
                    'time_formatted' => $tx->created_at->format('H:i') . ' WIB',
                ];
            });

        $todayDeposit = Transaction::where('nasabah_id', $nasabah->id)
            ->where('transaction_type', 'Deposit')
            ->whereDate('created_at', $today)
            ->sum('amount');

        $todayWithdrawal = Transaction::where('nasabah_id', $nasabah->id)
            ->where('transaction_type', 'Withdrawal')
            ->whereDate('created_at', $today)
            ->sum('amount');

        // ── Statistik Keseluruhan & Bulanan ──
        $totalSetoran = Transaction::where('nasabah_id', $nasabah->id)
            ->where('transaction_type', 'Deposit')
            ->sum('amount');

        $totalPenarikan = Transaction::where('nasabah_id', $nasabah->id)
            ->where('transaction_type', 'Withdrawal')
            ->sum('amount');

        $countSetoran = Transaction::where('nasabah_id', $nasabah->id)
            ->where('transaction_type', 'Deposit')
            ->count();

        $countPenarikan = Transaction::where('nasabah_id', $nasabah->id)
            ->where('transaction_type', 'Withdrawal')
            ->count();

        return $this->successResponse([
            'account' => [
                'id' => $nasabah->id,
                'account_number' => $nasabah->account_number,
                'student_name' => $nasabah->student_name,
                'student_number' => $nasabah->student_number,
                'class' => $nasabah->class,
                'jurusan' => $nasabah->jurusan,
                'gender' => $nasabah->gender === 'L' ? 'Laki-laki' : ($nasabah->gender === 'P' ? 'Perempuan' : '-'),
                'phone_number' => $nasabah->phone_number,
                'email' => $nasabah->email,
                'balance' => (float) $nasabah->balance,
                'balance_formatted' => 'Rp ' . number_format((float) $nasabah->balance, 0, ',', '.'),
                'status' => $nasabah->status,
            ],
            'today' => [
                'date' => $today->format('Y-m-d'),
                'date_formatted' => $today->translatedFormat('d F Y'),
                'total_deposit' => (float) $todayDeposit,
                'total_deposit_formatted' => 'Rp ' . number_format((float) $todayDeposit, 0, ',', '.'),
                'total_withdrawal' => (float) $todayWithdrawal,
                'total_withdrawal_formatted' => 'Rp ' . number_format((float) $todayWithdrawal, 0, ',', '.'),
                'count_transactions' => $todayTransactions->count(),
                'transactions' => $todayTransactions,
            ],
            'statistics' => [
                'total_deposit' => (float) $totalSetoran,
                'total_deposit_formatted' => 'Rp ' . number_format((float) $totalSetoran, 0, ',', '.'),
                'total_withdrawal' => (float) $totalPenarikan,
                'total_withdrawal_formatted' => 'Rp ' . number_format((float) $totalPenarikan, 0, ',', '.'),
                'count_deposit' => $countSetoran,
                'count_withdrawal' => $countPenarikan,
                'total_transactions' => $countSetoran + $countPenarikan,
            ],
            'bank_info' => [
                'institution' => 'SMK Negeri 1 Bantul',
                'service_name' => 'Bank Mini SMKN 1 Bantul',
                'operational_hours' => 'Senin - Jumat, 08.00 - 14.00 WIB',
                'counter_location' => 'Gedung Keuangan & Bank Mini Lt. 1',
            ],
        ], 'Dashboard nasabah berhasil dimuat.');
    }
}
