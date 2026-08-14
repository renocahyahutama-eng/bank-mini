<?php

namespace App\Http\Controllers\Teller;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    public function index()
    {
        $reports = DailyReport::with('supervisor')
            ->where('teller_id', auth()->id())
            ->orderByDesc('report_date')
            ->paginate(20);

        return view('teller.reports.index', compact('reports'));
    }

    public function create()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        // Check if report already exists for today
        $existingReport = DailyReport::where('teller_id', $user->id)
            ->where('report_date', $today)
            ->first();

        if ($existingReport) {
            return redirect()->route('teller.reports.show', $existingReport->id)
                ->with('info', 'Laporan harian untuk hari ini sudah dibuat.');
        }

        // Calculate today's totals
        $todayDeposits = Transaction::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->where('transaction_type', 'Deposit')
            ->sum('amount');

        $todayWithdrawals = Transaction::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->where('transaction_type', 'Withdrawal')
            ->sum('amount');

        // Get last approved report's closing balance as today's opening balance
        $lastReport = DailyReport::where('teller_id', $user->id)
            ->where('status', 'Approved')
            ->orderByDesc('report_date')
            ->first();

        $openingBalance = $lastReport ? $lastReport->closing_balance : 0;
        $closingBalance = $openingBalance + $todayDeposits - $todayWithdrawals;

        return view('teller.reports.create', compact(
            'openingBalance',
            'todayDeposits',
            'todayWithdrawals',
            'closingBalance'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $today = now()->toDateString();

        // Prevent duplicate
        $existing = DailyReport::where('teller_id', $user->id)
            ->where('report_date', $today)
            ->first();

        if ($existing) {
            return redirect()->route('teller.reports.show', $existing->id)
                ->with('info', 'Laporan sudah ada.');
        }

        // Calculate
        $todayDeposits = Transaction::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->where('transaction_type', 'Deposit')
            ->sum('amount');

        $todayWithdrawals = Transaction::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->where('transaction_type', 'Withdrawal')
            ->sum('amount');

        $lastReport = DailyReport::where('teller_id', $user->id)
            ->where('status', 'Approved')
            ->orderByDesc('report_date')
            ->first();

        $openingBalance = $lastReport ? $lastReport->closing_balance : 0;
        $closingBalance = $openingBalance + $todayDeposits - $todayWithdrawals;

        $report = DailyReport::create([
            'report_date' => $today,
            'teller_id' => $user->id,
            'opening_balance' => $openingBalance,
            'total_deposit' => $todayDeposits,
            'total_withdrawal' => $todayWithdrawals,
            'closing_balance' => $closingBalance,
            'status' => 'Draft',
        ]);

        return redirect()->route('teller.reports.show', $report->id)
            ->with('success', 'Laporan harian berhasil dibuat.');
    }

    public function show(DailyReport $report)
    {
        if ($report->teller_id !== auth()->id()) {
            abort(403);
        }

        $report->load(['teller', 'supervisor']);

        return view('teller.reports.show', compact('report'));
    }

    public function submit(DailyReport $report)
    {
        if ($report->teller_id !== auth()->id()) {
            abort(403);
        }

        if (!$report->isDraft() && !$report->isRejected()) {
            return back()->with('error', 'Laporan tidak dapat disubmit.');
        }

        $report->update([
            'status' => 'Submitted',
            'submitted_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Laporan berhasil disubmit untuk ditinjau Supervisor.');
    }
}
