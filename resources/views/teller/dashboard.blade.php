@extends('layouts.app')

@section('title', 'Dashboard Teller')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('teller.dashboard') }}" class="nav-item active"><span class="nav-icon">📊</span> Dashboard</a>
    <a href="{{ route('teller.nasabah.index') }}" class="nav-item"><span class="nav-icon">🔍</span> Cari Nasabah</a>
    <div class="nav-label">Transaksi Loket</div>
    <a href="{{ route('teller.deposit.create') }}" class="nav-item"><span class="nav-icon">📥</span> Setoran Tunai</a>
    <a href="{{ route('teller.withdrawal.create') }}" class="nav-item"><span class="nav-icon">📤</span> Penarikan Tunai</a>
    <div class="nav-label">Laporan & Riwayat</div>
    <a href="{{ route('teller.transactions.index') }}" class="nav-item"><span class="nav-icon">🧾</span> Riwayat Transaksi</a>
    <a href="{{ route('teller.reports.index') }}" class="nav-item"><span class="nav-icon">📅</span> Laporan Harian</a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard Teller</h1>
            <div class="breadcrumb">Selamat datang di loket, {{ auth()->user()->name }}!</div>
        </div>
        <div>
            @if(!$todayReport)
                <a href="{{ route('teller.reports.create') }}" class="btn btn-primary">Buat Laporan Harian (Tutup Kas)</a>
            @else
                <a href="{{ route('teller.reports.show', $todayReport) }}" class="btn btn-secondary">Lihat Laporan Hari Ini ({{ $todayReport->status }})</a>
            @endif
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon info">🧾</div>
            <div class="stat-content">
                <div class="stat-label">Transaksi Hari Ini</div>
                <div class="stat-value">{{ $todayTransactions }}</div>
                <div class="stat-desc">Oleh Anda</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">📥</div>
            <div class="stat-content">
                <div class="stat-label">Setoran Masuk (Hari Ini)</div>
                <div class="stat-value">Rp {{ number_format($todayDeposits, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon danger">📤</div>
            <div class="stat-content">
                <div class="stat-label">Penarikan Keluar (Hari Ini)</div>
                <div class="stat-value">Rp {{ number_format($todayWithdrawals, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Transaksi Terbaru (Anda)</h3>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Nasabah</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $tx)
                        <tr>
                            <td>{{ $tx->created_at->format('H:i') }}</td>
                            <td>{{ $tx->nasabah->student_name ?? '-' }} ({{ $tx->nasabah->account_number ?? '-' }})</td>
                            <td>
                                @if($tx->transaction_type === 'Deposit')
                                    <span class="badge badge-success">Setoran</span>
                                @else
                                    <span class="badge badge-danger">Penarikan</span>
                                @endif
                            </td>
                            <td class="font-mono font-bold">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                            <td><span class="badge badge-success">Sukses</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding:2rem">Belum ada transaksi hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
