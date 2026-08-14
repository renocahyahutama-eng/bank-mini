@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-item active" id="nav-dashboard">
        <span class="nav-icon">📊</span> Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}" class="nav-item" id="nav-users">
        <span class="nav-icon">👥</span> Manajemen User
    </a>
    <a href="{{ route('admin.nasabah.index') }}" class="nav-item" id="nav-nasabah">
        <span class="nav-icon">🎓</span> Manajemen Nasabah
    </a>
    <div class="nav-label">Laporan</div>
    <a href="{{ route('admin.transactions.index') }}" class="nav-item" id="nav-transactions">
        <span class="nav-icon">💳</span> Data Transaksi
    </a>
    <a href="{{ route('admin.journals.index') }}" class="nav-item" id="nav-journals">
        <span class="nav-icon">📒</span> Jurnal Akuntansi
    </a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard Administrator</h1>
            <div class="breadcrumb">Selamat datang, {{ auth()->user()->name }}!</div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">👥</div>
            <div class="stat-content">
                <div class="stat-label">Total User</div>
                <div class="stat-value">{{ $totalUsers }}</div>
                <div class="stat-desc">Pegawai terdaftar</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">🎓</div>
            <div class="stat-content">
                <div class="stat-label">Total Nasabah</div>
                <div class="stat-value">{{ $totalNasabah }}</div>
                <div class="stat-desc">Siswa terdaftar</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon info">💳</div>
            <div class="stat-content">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value">{{ $totalTransactions }}</div>
                <div class="stat-desc">Seluruh transaksi</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">💰</div>
            <div class="stat-content">
                <div class="stat-label">Total Setoran</div>
                <div class="stat-value">Rp {{ number_format($totalDeposits, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon danger">📤</div>
            <div class="stat-content">
                <div class="stat-label">Total Penarikan</div>
                <div class="stat-value">Rp {{ number_format($totalWithdrawals, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Transaksi Terbaru</h3>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Nasabah</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Teller</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $tx)
                        <tr>
                            <td>{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $tx->nasabah->student_name ?? '-' }}</td>
                            <td>
                                @if($tx->transaction_type === 'Deposit')
                                    <span class="badge badge-success">Setoran</span>
                                @else
                                    <span class="badge badge-danger">Penarikan</span>
                                @endif
                            </td>
                            <td class="font-mono">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                            <td>{{ $tx->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding:2rem">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
