<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingReports = DailyReport::where('status', 'Submitted')->count();
        $approvedToday = DailyReport::where('status', 'Approved')
            ->whereDate('approved_at', now()->toDateString())
            ->count();

        $todayTransactions = Transaction::whereDate('created_at', now()->toDateString())->count();
        $todayDeposits = Transaction::whereDate('created_at', now()->toDateString())
            ->where('transaction_type', 'Deposit')->sum('amount');
        $todayWithdrawals = Transaction::whereDate('created_at', now()->toDateString())
            ->where('transaction_type', 'Withdrawal')->sum('amount');

        $recentReports = DailyReport::with('teller')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('supervisor.dashboard', compact(
            'pendingReports',
            'approvedToday',
            'todayTransactions',
            'todayDeposits',
            'todayWithdrawals',
            'recentReports'
        ));
    }
}
