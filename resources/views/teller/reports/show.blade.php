@extends('layouts.app')

@section('title', 'Detail Laporan Harian')

@section('content')
    <div class="page-header">
        <div>
            <h1>Detail Laporan Harian ({{ $report->report_date->format('d/m/Y') }})</h1>
        </div>
        <a href="{{ route('teller.reports.index') }}" class="btn btn-secondary">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    @if($report->status === 'Draft' || $report->status === 'Rejected')
        <div class="card mb-3" style="border: 1px solid var(--primary); background: var(--primary-light);">
            <div class="card-body text-center">
                <h3 style="margin-bottom: 0.5rem; color: var(--primary);">Laporan siap diajukan</h3>
                <p style="margin-bottom: 1rem; color: var(--text-secondary); font-size: 0.85rem;">
                    Silakan serahkan uang fisik kas loket ke Supervisor, lalu klik tombol ajukan di bawah ini.
                </p>
                <form action="{{ route('teller.reports.submit', $report) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Kirim laporan ini ke Supervisor?')">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Submit Laporan ke Supervisor
                    </button>
                </form>
            </div>
        </div>
    @endif

    @if($report->status === 'Rejected')
        <div class="alert alert-error mb-3">
            <strong>Laporan Ditolak oleh Supervisor!</strong><br>
            Alasan: {{ $report->rejection_reason }}<br>
            <em style="font-size: 0.8rem;">Silakan cocokkan kembali fisik uang kas dan submit ulang.</em>
        </div>
    @endif

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h3 style="margin:0;">Rekapitulasi Kas Loket</h3>
            <div>
                @if($report->status === 'Draft')
                    <span class="badge badge-secondary">Draft</span>
                @elseif($report->status === 'Submitted')
                    <span class="badge badge-info">Menunggu Validasi Supervisor</span>
                @elseif($report->status === 'Approved')
                    <span class="badge badge-success">Telah Disetujui ({{ $report->approved_at->format('d/m/Y H:i') }})</span>
                @else
                    <span class="badge badge-danger">Ditolak</span>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="info-box">
                <div class="info-row"><span class="label">Tanggal Laporan</span><span class="value">{{ $report->report_date->format('d/m/Y') }}</span></div>
                <div class="info-row"><span class="label">Petugas Teller</span><span class="value">{{ $report->teller->name }}</span></div>
                @if($report->supervisor)
                    <div class="info-row"><span class="label">Diperiksa Oleh</span><span class="value">{{ $report->supervisor->name }}</span></div>
                @endif
            </div>

            <div class="report-summary mt-3">
                <div class="report-summary-item">
                    <div class="rs-label">Saldo Awal</div>
                    <div class="rs-value font-mono">Rp {{ number_format($report->opening_balance, 0, ',', '.') }}</div>
                </div>
                <div class="report-summary-item">
                    <div class="rs-label">Total Setoran</div>
                    <div class="rs-value deposit font-mono" style="color:var(--success);">+ Rp {{ number_format($report->total_deposit, 0, ',', '.') }}</div>
                </div>
                <div class="report-summary-item">
                    <div class="rs-label">Total Penarikan</div>
                    <div class="rs-value withdrawal font-mono" style="color:var(--danger);">- Rp {{ number_format($report->total_withdrawal, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="balance-display mt-3">
                <div class="balance-label">Saldo Akhir Sistem (Total Kas Disetor)</div>
                <div class="balance-amount">Rp {{ number_format($report->closing_balance, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
@endsection
