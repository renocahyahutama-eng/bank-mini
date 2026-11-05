@extends('layouts.app')

@section('title', 'Validasi Laporan')

@section('content')
    <div class="page-header d-flex justify-between align-center flex-wrap gap-2 mb-3">
        <div>
            <h1>Validasi Laporan Kas Teller</h1>
        </div>
        <div>
            <a href="{{ route('supervisor.reports.print') }}" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center; gap:6px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Laporan Transaksi</span>
            </a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('supervisor.reports.index') }}" method="GET" class="d-flex gap-2 align-center">
                <select name="status" class="form-control" style="max-width: 250px;">
                    <option value="">Semua Status</option>
                    <option value="Submitted" {{ request('status') === 'Submitted' ? 'selected' : '' }}>Menunggu Validasi (Submitted)</option>
                    <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                @if(request()->has('status'))
                    <a href="{{ route('supervisor.reports.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Laporan</th>
                        <th>Teller</th>
                        <th>Total Setoran</th>
                        <th>Total Penarikan</th>
                        <th>Saldo Akhir (Fisik)</th>
                        <th>Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>{{ $report->report_date->format('d/m/Y') }}</td>
                            <td style="font-weight:600;">{{ $report->teller->name ?? '-' }}</td>
                            <td class="font-mono font-bold text-success">+ Rp {{ number_format($report->total_deposit, 0, ',', '.') }}</td>
                            <td class="font-mono font-bold text-danger">- Rp {{ number_format($report->total_withdrawal, 0, ',', '.') }}</td>
                            <td class="font-mono font-bold text-primary">Rp {{ number_format($report->closing_balance, 0, ',', '.') }}</td>
                            <td>
                                @if($report->status === 'Draft')
                                    <span class="badge badge-secondary">Draft</span>
                                @elseif($report->status === 'Submitted')
                                    <span class="badge badge-warning">Menunggu Validasi</span>
                                @elseif($report->status === 'Approved')
                                    <span class="badge badge-success">Disetujui</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <a href="{{ route('supervisor.reports.show', $report) }}" class="btn btn-sm {{ $report->status === 'Submitted' ? 'btn-primary' : 'btn-secondary' }}">
                                    {{ $report->status === 'Submitted' ? 'Validasi' : 'Lihat' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding:2.5rem 1rem;">Belum ada laporan harian.</td>
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
