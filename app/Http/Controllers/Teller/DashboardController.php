<?php

namespace App\Http\Controllers\Teller;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\DailyReport;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        $todayTransactions = Transaction::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->count();

        $todayDeposits = Transaction::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->where('transaction_type', 'Deposit')
            ->sum('amount');

        $todayWithdrawals = Transaction::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->where('transaction_type', 'Withdrawal')
            ->sum('amount');

        $todayReport = DailyReport::where('teller_id', $user->id)
            ->where('report_date', $today)
            ->first();

        $recentTransactions = Transaction::with('nasabah')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('teller.dashboard', compact(
            'todayTransactions',
            'todayDeposits',
            'todayWithdrawals',
            'todayReport',
            'recentTransactions'
        ));
    }
}
