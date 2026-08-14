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
        $totalTransactions = Transaction::count();
        $totalDeposits = Transaction::where('transaction_type', 'Deposit')->sum('amount');
        $totalWithdrawals = Transaction::where('transaction_type', 'Withdrawal')->sum('amount');
        $recentTransactions = Transaction::with(['nasabah', 'user'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalNasabah',
            'totalTransactions',
            'totalDeposits',
            'totalWithdrawals',
            'recentTransactions'
        ));
    }
}
