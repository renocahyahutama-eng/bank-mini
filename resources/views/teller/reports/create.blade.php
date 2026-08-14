@extends('layouts.app')

@section('title', 'Buat Laporan Harian')

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
            <h1>Penutupan Kas ({{ date('d/m/Y') }})</h1>
            <div class="breadcrumb">Teller / Laporan Harian / Buat Draft</div>
        </div>
        <a href="{{ route('teller.reports.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card" style="max-width: 700px; margin: 0 auto;">
        <div class="card-body">
            <div class="alert alert-info">
                ℹ Sistem telah menghitung mutasi transaksi hari ini. Pastikan hitungan sistem sesuai dengan uang fisik di laci Anda.
            </div>

            <div class="report-summary mt-3">
                <div class="report-summary-item">
                    <div class="rs-label">Saldo Awal (Kas Pagi)</div>
                    <div class="rs-value font-mono">Rp {{ number_format($openingBalance, 0, ',', '.') }}</div>
                </div>
                <div class="report-summary-item">
                    <div class="rs-label">Total Setoran (+)</div>
                    <div class="rs-value deposit font-mono">Rp {{ number_format($todayDeposits, 0, ',', '.') }}</div>
                </div>
                <div class="report-summary-item">
                    <div class="rs-label">Total Penarikan (-)</div>
                    <div class="rs-value withdrawal font-mono">Rp {{ number_format($todayWithdrawals, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="balance-display mb-3">
                <div class="balance-label">Saldo Akhir Sistem (Kas Sore)</div>
                <div class="balance-amount">Rp {{ number_format($closingBalance, 0, ',', '.') }}</div>
            </div>

            <div class="alert alert-warning">
                ⚠ <strong>Peringatan!</strong> Draft laporan akan dicatat pada sistem. Setelah laporan dikirim (Submitted), Anda tidak bisa lagi melayani transaksi setoran/penarikan untuk hari ini hingga laporan diperiksa oleh Supervisor.
            </div>

            <form action="{{ route('teller.reports.store') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-block btn-lg mt-2" onclick="return confirm('Simpan draft laporan penutupan kas hari ini?')">Simpan Draft Laporan</button>
            </form>
        </div>
    </div>
@endsection
