@extends('layouts.app')

@section('title', 'Dashboard Teller')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('teller.dashboard') }}" class="nav-item active">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </span>
        Dashboard
    </a>
    <a href="{{ route('teller.nasabah.index') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </span>
        Cari Nasabah / QR
    </a>
    <div class="nav-label">Transaksi Loket</div>
    <a href="{{ route('teller.deposit.create') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
        </span>
        Setoran Tunai
    </a>
    <a href="{{ route('teller.withdrawal.create') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
        </span>
        Penarikan Tunai
    </a>
    <div class="nav-label">Laporan & Riwayat</div>
    <a href="{{ route('teller.transactions.index') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </span>
        Riwayat Transaksi
    </a>
    <a href="{{ route('teller.reports.index') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </span>
        Laporan Harian
    </a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard Loket Teller</h1>
            <div class="breadcrumb">Selamat bertugas, {{ auth()->user()->name }}!</div>
        </div>
        <div style="display:flex; gap:0.5rem; align-items:center;">
            @if(!$todayReport)
                <a href="{{ route('teller.reports.create') }}" class="btn btn-primary btn-sm">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Tutup Kas Hari Ini
                </a>
            @else
                <a href="{{ route('teller.reports.show', $todayReport) }}" class="btn btn-secondary btn-sm">
                    Lihat Laporan Hari Ini ({{ $todayReport->status }})
                </a>
            @endif
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon info">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Transaksi Hari Ini</div>
                <div class="stat-value">{{ $todayTransactions }}</div>
                <div class="stat-desc">Diproses oleh Anda</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Setoran Masuk Hari Ini</div>
                <div class="stat-value" style="color:var(--success);">Rp {{ number_format($todayDeposits, 0, ',', '.') }}</div>
                <div class="stat-desc">Total dana diterima</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon danger">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Penarikan Keluar Hari Ini</div>
                <div class="stat-value" style="color:var(--danger);">Rp {{ number_format($todayWithdrawals, 0, ',', '.') }}</div>
                <div class="stat-desc">Total dana ditarik</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3 style="display:flex; align-items:center; gap:0.5rem; margin:0;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Transaksi Hari Ini (Anda)
                </h3>
                <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem;">Hanya menampilkan transaksi yang Anda proses hari ini</div>
            </div>
            <a href="{{ route('teller.transactions.index') }}" class="btn btn-secondary btn-sm" id="btn-view-all-teller-tx">
                Lihat Semua Transaksi
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>No. Rekening</th>
                        <th>Nasabah</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $tx)
                        <tr>
                            <td><span style="font-weight:600;">{{ $tx->created_at->format('H:i') }}</span></td>
                            <td class="font-mono font-bold">{{ $tx->nasabah->account_number ?? '-' }}</td>
                            <td>{{ $tx->nasabah->student_name ?? '-' }}</td>
                            <td>
                                @if($tx->transaction_type === 'Deposit')
                                    <span class="badge badge-success">Setoran</span>
                                @else
                                    <span class="badge badge-danger">Penarikan</span>
                                @endif
                            </td>
                            <td class="font-mono font-bold" style="color: {{ $tx->transaction_type === 'Deposit' ? 'var(--success)' : 'var(--danger)' }};">
                                {{ $tx->transaction_type === 'Deposit' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                            <td>
                                <a href="{{ route('teller.transactions.receipt', $tx) }}" target="_blank" class="btn btn-secondary btn-sm" style="padding:0.25rem 0.6rem; font-size:0.75rem;" title="Cetak Struk">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    Struk
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding:2.5rem 1rem;">
                                <div>Belum ada transaksi hari ini.</div>
                                <div style="font-size:0.78rem; margin-top:0.35rem;">
                                    <a href="{{ route('teller.transactions.index') }}" style="color:var(--primary); font-weight:600;">Lihat semua riwayat transaksi loket &rarr;</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
