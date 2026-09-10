@extends('layouts.app')

@section('title', 'Laporan Harian')

@section('content')
    <div class="page-header">
        <div>
            <h1>Laporan Harian (Penutupan Kas)</h1>
        </div>
        <a href="{{ route('teller.reports.create') }}" class="btn btn-primary">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Laporan Hari Ini
        </a>
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
                        <th>Status Verifikasi</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td style="font-weight:600;">{{ $report->report_date->format('d/m/Y') }}</td>
                            <td class="font-mono">Rp {{ number_format($report->opening_balance, 0, ',', '.') }}</td>
                            <td class="font-mono font-bold text-success">+ Rp {{ number_format($report->total_deposit, 0, ',', '.') }}</td>
                            <td class="font-mono font-bold text-danger">- Rp {{ number_format($report->total_withdrawal, 0, ',', '.') }}</td>
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
                            <td style="text-align:center;">
                                <a href="{{ route('teller.reports.show', $report) }}" class="btn btn-sm btn-secondary">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding:2.5rem 1rem;">Belum ada laporan harian yang dibuat.</td>
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
