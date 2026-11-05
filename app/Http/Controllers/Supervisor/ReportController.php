<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
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

    /**
     * Tampilan Filter & Pratinjau Cetak Laporan (Harian, Mingguan, Bulanan).
     */
    public function printReport(Request $request)
    {
        $data = $this->buildReportData($request);
        return view('supervisor.reports.print', $data);
    }

    /**
     * Halaman Bersih Cetak / Ekspor PDF Sesuai Format Resmi Bank Mini SMKN 1 Bantul.
     */
    public function printReportView(Request $request)
    {
        $data = $this->buildReportData($request);
        return view('supervisor.reports.print_view', $data);
    }

    /**
     * Membangun dataset laporan mutasi kas berdasarkan filter periode & parameter.
     */
    protected function buildReportData(Request $request): array
    {
        $period = $request->input('period', 'daily');
        if (!in_array($period, ['daily', 'weekly', 'monthly'])) {
            $period = 'daily';
        }

        $now = Carbon::now();
        $query = Transaction::with(['nasabah', 'user']);

        // 1. Periode Harian
        if ($period === 'daily') {
            $date = $request->input('date', $now->format('Y-m-d'));
            $parsedDate = Carbon::parse($date);

            // Cegah tanggal di masa depan
            if ($parsedDate->isFuture()) {
                $parsedDate = $now->copy();
            }

            $query->whereDate('created_at', $parsedDate);

            $periodTitle = 'LAPORAN TRANSAKSI MUTASI KAS HARIAN';
            $periodLabel = 'Harian (' . $parsedDate->translatedFormat('d/m/Y') . ')';
            $startDate = $parsedDate->format('Y-m-d');
            $endDate = $parsedDate->format('Y-m-d');
            $dateSelected = $startDate;
        }
        // 2. Periode Mingguan
        elseif ($period === 'weekly') {
            $startDateInput = $request->input('start_date');
            $endDateInput = $request->input('end_date');

            if ($startDateInput && $endDateInput) {
                $start = Carbon::parse($startDateInput)->startOfDay();
                $end = Carbon::parse($endDateInput)->endOfDay();

                // Cegah rentang tanggal di masa depan
                if ($end->isFuture()) {
                    $end = $now->copy()->endOfDay();
                }
                if ($start->isFuture()) {
                    $start = $now->copy()->startOfDay();
                }
            } else {
                $start = $now->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
                $end = $now->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();
                if ($end->isFuture()) {
                    $end = $now->copy()->endOfDay();
                }
            }

            $query->whereBetween('created_at', [$start, $end]);

            $periodTitle = 'LAPORAN TRANSAKSI MUTASI KAS MINGGUAN';
            $periodLabel = 'Mingguan (' . $start->translatedFormat('d/m/Y') . ' - ' . $end->translatedFormat('d/m/Y') . ')';
            $startDate = $start->format('Y-m-d');
            $endDate = $end->format('Y-m-d');
            $dateSelected = $startDate;
        }
        // 3. Periode Bulanan
        else {
            $month = (int) $request->input('month', $now->month);
            $year = (int) $request->input('year', $now->year);

            // Cegah pemilihan tahun dan bulan di masa depan
            if ($year > $now->year) {
                $year = $now->year;
            }
            if ($year == $now->year && $month > $now->month) {
                $month = $now->month;
            }

            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);

            $dateObj = Carbon::createFromDate($year, $month, 1);
            $periodTitle = 'LAPORAN TRANSAKSI MUTASI KAS BULANAN';
            $periodLabel = 'Bulanan (' . $dateObj->translatedFormat('F Y') . ')';
            $startDate = $dateObj->copy()->startOfMonth()->format('Y-m-d');
            $endDate = $dateObj->copy()->endOfMonth()->format('Y-m-d');
            $dateSelected = $startDate;
        }

        // Filter Tipe Transaksi opsional (Semua, Deposit, Withdrawal)
        $type = $request->input('type');
        if ($type && in_array($type, ['Deposit', 'Withdrawal'])) {
            $query->where('transaction_type', $type);
        }

        // Filter Teller opsional
        $tellerId = $request->input('teller_id');
        if ($tellerId) {
            $query->where('user_id', $tellerId);
        }

        // Urutan kronologis
        $transactions = $query->orderBy('created_at', 'asc')->get();

        // Hitung Ringkasan Kas (KPI Summary)
        $totalDeposit = $transactions->where('transaction_type', 'Deposit')->sum('amount');
        $countDeposit = $transactions->where('transaction_type', 'Deposit')->count();

        $totalWithdrawal = $transactions->where('transaction_type', 'Withdrawal')->sum('amount');
        $countWithdrawal = $transactions->where('transaction_type', 'Withdrawal')->count();

        $netFlow = $totalDeposit - $totalWithdrawal;
        $totalTransactions = $transactions->count();

        $tellers = User::where('role', 'Teller')->orderBy('name')->get();

        $selectedMonth = (int) ($request->input('month') ?? $now->month);
        $selectedYear = (int) ($request->input('year') ?? $now->year);

        return compact(
            'period',
            'periodTitle',
            'periodLabel',
            'startDate',
            'endDate',
            'dateSelected',
            'selectedMonth',
            'selectedYear',
            'transactions',
            'totalDeposit',
            'countDeposit',
            'totalWithdrawal',
            'countWithdrawal',
            'netFlow',
            'totalTransactions',
            'tellers',
            'type',
            'tellerId'
        );
    }
}
