<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $customerAccount = auth()->guard('nasabah')->user();
        $nasabah = $customerAccount->nasabah;

        $recentTransactions = Transaction::where('nasabah_id', $nasabah->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('nasabah.dashboard', compact('nasabah', 'recentTransactions'));
    }
}
