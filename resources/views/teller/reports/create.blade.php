@extends('layouts.app')

@section('title', 'Buat Laporan Harian')

@section('content')
    <div class="page-header">
        <div>
            <h1>Penutupan Kas ({{ date('d/m/Y') }})</h1>
            <div class="breadcrumb">Loket Teller / Rekapitulasi Kas Tutup Hari</div>
        </div>
        <a href="{{ route('teller.reports.index') }}" class="btn btn-secondary">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="card" style="max-width: 700px; margin: 0 auto;">
        <div class="card-body">
            <div class="alert alert-info">
                Sistem telah merekap mutasi transaksi loket Anda hari ini. Pastikan saldo akhir sistem ini sama persis dengan uang fisik kas yang ada.
            </div>

            <div class="report-summary mt-3">
                <div class="report-summary-item">
                    <div class="rs-label">Saldo Awal (Kas Pagi)</div>
                    <div class="rs-value font-mono">Rp {{ number_format($openingBalance, 0, ',', '.') }}</div>
                </div>
                <div class="report-summary-item">
                    <div class="rs-label">Total Setoran (+)</div>
                    <div class="rs-value deposit font-mono" style="color:var(--success);">+ Rp {{ number_format($todayDeposits, 0, ',', '.') }}</div>
                </div>
                <div class="report-summary-item">
                    <div class="rs-label">Total Penarikan (-)</div>
                    <div class="rs-value withdrawal font-mono" style="color:var(--danger);">- Rp {{ number_format($todayWithdrawals, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="balance-display mb-3">
                <div class="balance-label">Saldo Akhir Sistem (Kas Sore)</div>
                <div class="balance-amount">Rp {{ number_format($closingBalance, 0, ',', '.') }}</div>
            </div>

            <div class="alert alert-warning">
                <strong>Catatan Penting:</strong> Setelah laporan dikirim ke Supervisor (Submitted), loket ditutup untuk hari ini hingga disetujui.
            </div>

            <form action="{{ route('teller.reports.store') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-block btn-lg mt-3" style="width:100%; justify-content:center;" onclick="return confirm('Simpan draft laporan penutupan kas hari ini?')">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Draft Laporan Penutupan Kas
                </button>
            </form>
        </div>
    </div>
@endsection
