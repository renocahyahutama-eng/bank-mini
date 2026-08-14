<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = DailyReport::with('teller');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $reports = $query->orderByDesc('report_date')->paginate(20);

        return view('supervisor.reports.index', compact('reports'));
    }

    public function show(DailyReport $report)
    {
        $report->load(['teller', 'supervisor']);

        // Get transactions for this teller on report date
        $transactions = Transaction::with('nasabah')
            ->where('user_id', $report->teller_id)
            ->whereDate('created_at', $report->report_date)
            ->orderBy('created_at')
            ->get();

        return view('supervisor.reports.show', compact('report', 'transactions'));
    }

    public function approve(DailyReport $report)
    {
        if (!$report->isSubmitted()) {
            return back()->with('error', 'Laporan tidak dalam status Submitted.');
        }

        $report->update([
            'status' => 'Approved',
            'supervisor_id' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Laporan berhasil disetujui (Approved).');
    }

    public function reject(Request $request, DailyReport $report)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if (!$report->isSubmitted()) {
            return back()->with('error', 'Laporan tidak dalam status Submitted.');
        }

        $report->update([
            'status' => 'Rejected',
            'supervisor_id' => auth()->id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Laporan ditolak (Rejected). Teller perlu memperbaiki.');
    }
}
