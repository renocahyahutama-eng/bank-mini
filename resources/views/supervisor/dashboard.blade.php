@extends('layouts.app')

@section('title', 'Dashboard Supervisor')

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard Supervisor</h1>
            <div class="breadcrumb">{{ auth()->user()->name }}</div>
        </div>
        @if($pendingReports > 0)
            <a href="{{ route('supervisor.reports.index', ['status' => 'Submitted']) }}" class="btn btn-warning" style="display:inline-flex; align-items:center; gap:0.4rem;">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Ada {{ $pendingReports }} Laporan Menunggu Validasi
            </a>
        @endif
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon warning">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Menunggu Validasi</div>
                <div class="stat-value">{{ $pendingReports }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Laporan Disetujui</div>
                <div class="stat-value">{{ $approvedToday }}</div>
                <div class="stat-desc">Hari Ini</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon info">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Transaksi Loket</div>
                <div class="stat-value">{{ $todayTransactions }}</div>
                <div class="stat-desc">Hari Ini</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Setoran Masuk</div>
                <div class="stat-value" style="font-size:1.15rem;">Rp {{ number_format($todayDeposits, 0, ',', '.') }}</div>
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
                        <th style="text-align:center;">Aksi</th>
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
                            <td style="text-align:center;">
                                <a href="{{ route('supervisor.reports.show', $report) }}" class="btn btn-sm btn-secondary">Review</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding:2.5rem 1rem;">Belum ada laporan harian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
