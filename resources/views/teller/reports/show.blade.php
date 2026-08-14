@extends('layouts.app')

@section('title', 'Detail Laporan Harian')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('teller.dashboard') }}" class="nav-item"><span class="nav-icon">📊</span> Dashboard</a>
    <a href="{{ route('teller.nasabah.index') }}" class="nav-item"><span class="nav-icon">🔍</span> Cari Nasabah</a>
    <div class="nav-label">Transaksi Loket</div>
    <a href="{{ route('teller.deposit.create') }}" class="nav-item"><span class="nav-icon">📥</span> Setoran Tunai</a>
    <a href="{{ route('teller.withdrawal.create') }}" class="nav-item"><span class="nav-icon">📤</span> Penarikan Tunai</a>
    <div class="nav-label">Laporan & Riwayat</div>
    <a href="{{ route('teller.transactions.index') }}" class="nav-item"><span class="nav-icon">🧾</span> Riwayat Transaksi</a>
    <a href="{{ route('teller.reports.index') }}" class="nav-item active"><span class="nav-icon">📅</span> Laporan Harian</a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Detail Laporan Harian ({{ $report->report_date->format('d/m/Y') }})</h1>
            <div class="breadcrumb">Teller / Laporan Harian / Detail</div>
        </div>
        <a href="{{ route('teller.reports.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    @if($report->status === 'Draft' || $report->status === 'Rejected')
        <div class="card mb-3" style="border: 1px solid var(--primary); background: var(--primary-light);">
            <div class="card-body text-center">
                <h3 style="margin-bottom: 0.5rem; color: var(--primary);">Laporan siap disubmit</h3>
                <p style="margin-bottom: 1rem; color: var(--text-secondary); font-size: 0.85rem;">
                    Silakan serahkan uang fisik beserta bundel slip transaksi ke Supervisor, lalu klik tombol Submit.
                </p>
                <form action="{{ route('teller.reports.submit', $report) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Kirim laporan ini ke Supervisor?')">Submit Laporan ke Supervisor</button>
                </form>
            </div>
        </div>
    @endif

    @if($report->status === 'Rejected')
        <div class="alert alert-error mb-3">
            <strong>Laporan Ditolak oleh Supervisor!</strong><br>
            Alasan: {{ $report->rejection_reason }}<br>
            <em style="font-size: 0.8rem;">Silakan perbaiki data/fisik uang, lalu submit ulang.</em>
        </div>
    @endif

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header">
            <h3>Rekapitulasi Kas</h3>
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
                    <div class="rs-value deposit font-mono">+ Rp {{ number_format($report->total_deposit, 0, ',', '.') }}</div>
                </div>
                <div class="report-summary-item">
                    <div class="rs-label">Total Penarikan</div>
                    <div class="rs-value withdrawal font-mono">- Rp {{ number_format($report->total_withdrawal, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="balance-display mt-3">
                <div class="balance-label">Saldo Akhir Sistem (Total Kas Disetor)</div>
                <div class="balance-amount">Rp {{ number_format($report->closing_balance, 0, ',', '.') }}</div>
            </div>

        </div>
    </div>
@endsection
