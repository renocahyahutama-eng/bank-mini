@extends('layouts.app')

@section('title', 'Dashboard Supervisor')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('supervisor.dashboard') }}" class="nav-item active"><span class="nav-icon">📊</span> Dashboard</a>
    <div class="nav-label">Validasi & Kontrol</div>
    <a href="{{ route('supervisor.reports.index') }}" class="nav-item"><span class="nav-icon">✅</span> Validasi Laporan</a>
    <div class="nav-label">Audit & Pemantauan</div>
    <a href="{{ route('supervisor.transactions.index') }}" class="nav-item"><span class="nav-icon">💳</span> Data Transaksi</a>
    <a href="{{ route('supervisor.journals.index') }}" class="nav-item"><span class="nav-icon">📒</span> Jurnal Akuntansi</a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard Supervisor</h1>
            <div class="breadcrumb">Selamat datang, {{ auth()->user()->name }}!</div>
        </div>
        @if($pendingReports > 0)
            <a href="{{ route('supervisor.reports.index', ['status' => 'Submitted']) }}" class="btn btn-warning">Ada {{ $pendingReports }} Laporan Menunggu Validasi!</a>
        @endif
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon warning">⏳</div>
            <div class="stat-content">
                <div class="stat-label">Menunggu Validasi</div>
                <div class="stat-value">{{ $pendingReports }}</div>
                <div class="stat-desc">Laporan Teller</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">✅</div>
            <div class="stat-content">
                <div class="stat-label">Laporan Disetujui</div>
                <div class="stat-value">{{ $approvedToday }}</div>
                <div class="stat-desc">Hari Ini</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon info">💳</div>
            <div class="stat-content">
                <div class="stat-label">Transaksi Loket</div>
                <div class="stat-value">{{ $todayTransactions }}</div>
                <div class="stat-desc">Hari Ini</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon primary">📥</div>
            <div class="stat-content">
                <div class="stat-label">Total Setoran Masuk</div>
                <div class="stat-value">Rp {{ number_format($todayDeposits, 0, ',', '.') }}</div>
                <div class="stat-desc">Hari Ini</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Laporan Harian Terbaru</h3>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Laporan</th>
                        <th>Teller</th>
                        <th>Saldo Akhir (Kas Fisik)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReports as $report)
                        <tr>
                            <td>{{ $report->report_date->format('d/m/Y') }}</td>
                            <td>{{ $report->teller->name ?? '-' }}</td>
                            <td class="font-mono font-bold">Rp {{ number_format($report->closing_balance, 0, ',', '.') }}</td>
                            <td>
                                @if($report->status === 'Draft')
                                    <span class="badge badge-secondary">Draft</span>
                                @elseif($report->status === 'Submitted')
                                    <span class="badge badge-info">Menunggu Validasi</span>
                                @elseif($report->status === 'Approved')
                                    <span class="badge badge-success">Disetujui</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('supervisor.reports.show', $report) }}" class="btn btn-sm btn-secondary">Review</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding:2rem">Belum ada laporan harian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
