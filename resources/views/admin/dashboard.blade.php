@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-item active" id="nav-dashboard">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </span>
        Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}" class="nav-item" id="nav-users">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </span>
        Manajemen User
    </a>
    <a href="{{ route('admin.nasabah.index') }}" class="nav-item" id="nav-nasabah">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
        </span>
        Manajemen Nasabah
    </a>
    <div class="nav-label">Laporan</div>
    <a href="{{ route('admin.transactions.index') }}" class="nav-item" id="nav-transactions">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        </span>
        Data Transaksi
    </a>
    <a href="{{ route('admin.journals.index') }}" class="nav-item" id="nav-journals">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </span>
        Jurnal Akuntansi
    </a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard Administrator</h1>
            <div class="breadcrumb">Ringkasan statistik & keuangan bank hari ini</div>
        </div>
        <div style="font-size:0.85rem; color:var(--text-secondary); background:white; padding:0.5rem 0.85rem; border:1px solid var(--border); border-radius:var(--radius-sm);">
            📅 {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    {{-- Stats Grid Utama --}}
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
        {{-- Total Saldo Bank --}}
        <div class="stat-card">
            <div class="stat-icon primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Saldo Bank</div>
                <div class="stat-value" style="font-size:1.35rem; color:var(--primary);">Rp {{ number_format($totalBankBalance, 0, ',', '.') }}</div>
                <div class="stat-desc">Dana seluruh nasabah saat ini</div>
            </div>
        </div>

        {{-- Kas Bersih Harian --}}
        <div class="stat-card">
            <div class="stat-icon {{ $dailyNetCash >= 0 ? 'success' : 'danger' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Kas Bersih Hari Ini</div>
                <div class="stat-value" style="font-size:1.35rem; color:{{ $dailyNetCash >= 0 ? 'var(--success)' : 'var(--danger)' }};">
                    {{ $dailyNetCash >= 0 ? '+' : '' }}Rp {{ number_format($dailyNetCash, 0, ',', '.') }}
                </div>
                <div class="stat-desc">Setoran minus penarikan hari ini</div>
            </div>
        </div>

        {{-- Rekening Aktif --}}
        <div class="stat-card">
            <div class="stat-icon success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Rekening Aktif</div>
                <div class="stat-value">{{ $activeAccounts }}</div>
                <div class="stat-desc">Dari total {{ $totalNasabah }} nasabah</div>
            </div>
        </div>

        {{-- Rekening Nonaktif --}}
        <div class="stat-card">
            <div class="stat-icon danger">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Rekening Nonaktif</div>
                <div class="stat-value">{{ $inactiveAccounts }}</div>
                <div class="stat-desc">Nasabah ditangguhkan</div>
            </div>
        </div>
    </div>

    {{-- Detail Aliran Kas Hari Ini & Info User --}}
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-top:-0.5rem;">
        <div class="stat-card">
            <div class="stat-icon success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Setoran Hari Ini</div>
                <div class="stat-value" style="font-size:1.15rem;">Rp {{ number_format($todayDeposits, 0, ',', '.') }}</div>
                <div class="stat-desc">Arus dana masuk</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon danger">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Penarikan Hari Ini</div>
                <div class="stat-value" style="font-size:1.15rem;">Rp {{ number_format($todayWithdrawals, 0, ',', '.') }}</div>
                <div class="stat-desc">Arus dana keluar</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon info">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Pegawai Bank</div>
                <div class="stat-value">{{ $totalUsers }}</div>
                <div class="stat-desc">User sistem terdaftar</div>
            </div>
        </div>
    </div>

    {{-- Transaksi Hari Ini --}}
    <div class="card">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3 style="display:flex; align-items:center; gap:0.5rem; margin:0;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Transaksi Hari Ini
                    <span class="badge badge-info" style="font-size:0.75rem;">{{ $recentTransactions->count() }} transaksi</span>
                </h3>
                <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem;">Hanya menampilkan transaksi yang berlangsung hari ini</div>
            </div>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary btn-sm" id="btn-view-all-tx">
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
                        <th>Tipe Transaksi</th>
                        <th>Nominal</th>
                        <th>Teller</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $tx)
                        <tr>
                            <td>
                                <span style="font-weight:600;">{{ $tx->created_at->format('H:i') }}</span>
                                <span style="font-size:0.72rem; color:var(--text-muted); display:block;">{{ $tx->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="font-mono" style="font-weight:600;">{{ $tx->nasabah->account_number ?? '-' }}</td>
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
                            <td>{{ $tx->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding:2.5rem 1rem;">
                                <div style="font-size:1.5rem; margin-bottom:0.5rem; opacity:0.5;">
                                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <div>Belum ada transaksi pada hari ini.</div>
                                <div style="font-size:0.78rem; margin-top:0.35rem;">
                                    <a href="{{ route('admin.transactions.index') }}" style="color:var(--primary); font-weight:600;">Klik di sini untuk melihat semua riwayat transaksi &rarr;</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
