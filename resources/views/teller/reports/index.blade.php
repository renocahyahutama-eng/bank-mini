@extends('layouts.app')

@section('title', 'Laporan Harian')

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
            <h1>Laporan Harian (Penutupan Kas)</h1>
            <div class="breadcrumb">Teller / Laporan Harian</div>
        </div>
        <a href="{{ route('teller.reports.create') }}" class="btn btn-primary">Buat Laporan Hari Ini</a>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Laporan</th>
                        <th>Saldo Awal</th>
                        <th>Total Setoran</th>
                        <th>Total Penarikan</th>
                        <th>Saldo Akhir (Kas Fisik)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>{{ $report->report_date->format('d/m/Y') }}</td>
                            <td class="font-mono">Rp {{ number_format($report->opening_balance, 0, ',', '.') }}</td>
                            <td class="font-mono text-success">Rp {{ number_format($report->total_deposit, 0, ',', '.') }}</td>
                            <td class="font-mono text-danger">Rp {{ number_format($report->total_withdrawal, 0, ',', '.') }}</td>
                            <td class="font-mono font-bold text-primary">Rp {{ number_format($report->closing_balance, 0, ',', '.') }}</td>
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
                                <a href="{{ route('teller.reports.show', $report) }}" class="btn btn-sm btn-secondary">Lihat Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding:2rem">Belum ada laporan harian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())
            <div class="card-footer">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
@endsection
