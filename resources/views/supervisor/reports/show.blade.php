@extends('layouts.app')

@section('title', 'Review Laporan Harian')



@section('content')
    <div class="page-header">
        <div>
            <h1>Review Laporan Penutupan Kas</h1>
            <div class="breadcrumb">Supervisor / Validasi / Detail</div>
        </div>
        <a href="{{ route('supervisor.reports.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    @if($report->status === 'Submitted')
        <div class="card mb-3" style="border: 1px solid var(--warning); background: var(--warning-light);">
            <div class="card-body">
                <h3 style="margin-bottom: 0.5rem; color: var(--warning);">Laporan Memerlukan Validasi</h3>
                <p style="margin-bottom: 1rem; color: var(--text-secondary); font-size: 0.85rem;">
                    Periksa kesesuaian antara Total Saldo Akhir Sistem dengan Uang Fisik yang diserahkan oleh Teller beserta bundel slip transaksi.
                </p>
                <div class="d-flex gap-2">
                    <form action="{{ route('supervisor.reports.approve', $report) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin setuju dan menerima uang fisik sejumlah Rp {{ number_format($report->closing_balance, 0, ',', '.') }}?')">✓ Setujui (Approve) & Terima Uang Fisik</button>
                    </form>
                    
                    <button type="button" class="btn btn-danger" onclick="document.getElementById('reject-form').style.display='block';">✕ Tolak Laporan (Ada Selisih)</button>
                </div>

                <div id="reject-form" style="display:none; margin-top:1.5rem; background:var(--bg-body); padding:1rem; border-radius:var(--radius-sm);">
                    <form action="{{ route('supervisor.reports.reject', $report) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="rejection_reason">Alasan Penolakan (Wajib Diisi)</label>
                            <textarea name="rejection_reason" id="rejection_reason" class="form-control" required placeholder="Jelaskan selisih atau kesalahan input..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">Kirim Penolakan ke Teller</button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="card mb-3" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header">
            <h3>Rekapitulasi Kas ({{ $report->report_date->format('d/m/Y') }})</h3>
            <div>
                @if($report->status === 'Draft')
                    <span class="badge badge-secondary">Draft</span>
                @elseif($report->status === 'Submitted')
                    <span class="badge badge-warning">Menunggu Validasi</span>
                @elseif($report->status === 'Approved')
                    <span class="badge badge-success">Disetujui</span>
                @else
                    <span class="badge badge-danger">Ditolak</span>
                @endif
            </div>
        </div>
        <div class="card-body">
            
            <div class="info-box">
                <div class="info-row"><span class="label">Petugas Teller</span><span class="value">{{ $report->teller->name }}</span></div>
                @if($report->status === 'Approved' || $report->status === 'Rejected')
                    <div class="info-row"><span class="label">Diperiksa Oleh</span><span class="value">{{ $report->supervisor->name ?? '-' }}</span></div>
                @endif
                @if($report->status === 'Rejected')
                    <div class="info-row"><span class="label">Alasan Penolakan</span><span class="value text-danger">{{ $report->rejection_reason }}</span></div>
                @endif
            </div>

            <div class="report-summary mt-3">
                <div class="report-summary-item">
                    <div class="rs-label">Saldo Awal</div>
                    <div class="rs-value font-mono">Rp {{ number_format($report->opening_balance, 0, ',', '.') }}</div>
                </div>
                <div class="report-summary-item">
                    <div class="rs-label">Total Setoran (+)</div>
                    <div class="rs-value deposit font-mono">Rp {{ number_format($report->total_deposit, 0, ',', '.') }}</div>
                </div>
                <div class="report-summary-item">
                    <div class="rs-label">Total Penarikan (-)</div>
                    <div class="rs-value withdrawal font-mono">Rp {{ number_format($report->total_withdrawal, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="balance-display mt-3">
                <div class="balance-label">Total Kas Fisik yang Harus Diterima Supervisor</div>
                <div class="balance-amount">Rp {{ number_format($report->closing_balance, 0, ',', '.') }}</div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Rincian Transaksi Teller pada {{ $report->report_date->format('d/m/Y') }}</h3>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Nasabah</th>
                        <th>No. Rekening</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        <tr>
                            <td>{{ $tx->created_at->format('H:i') }}</td>
                            <td>{{ $tx->nasabah->student_name ?? '-' }}</td>
                            <td class="font-mono">{{ $tx->nasabah->account_number ?? '-' }}</td>
                            <td>
                                @if($tx->transaction_type === 'Deposit')
                                    <span class="badge badge-success">Setoran</span>
                                @else
                                    <span class="badge badge-danger">Penarikan</span>
                                @endif
                            </td>
                            <td class="font-mono font-bold {{ $tx->transaction_type === 'Deposit' ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding:2rem">Tidak ada transaksi tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
