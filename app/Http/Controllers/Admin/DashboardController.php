<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalNasabah = Nasabah::count();
        $activeAccounts = Nasabah::where('status', 'Aktif')->count();
        $inactiveAccounts = Nasabah::where('status', 'Nonaktif')->count();
        $totalBankBalance = Nasabah::sum('balance');

        $today = now()->toDateString();
        $todayDeposits = Transaction::whereDate('created_at', $today)->where('transaction_type', 'Deposit')->sum('amount');
        $todayWithdrawals = Transaction::whereDate('created_at', $today)->where('transaction_type', 'Withdrawal')->sum('amount');
        $dailyNetCash = $todayDeposits - $todayWithdrawals;

        $recentTransactions = Transaction::with(['nasabah', 'user'])
            ->whereDate('created_at', $today)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalNasabah',
            'activeAccounts',
            'inactiveAccounts',
            'totalBankBalance',
            'todayDeposits',
            'todayWithdrawals',
            'dailyNetCash',
            'recentTransactions'
        ));
    }
}
