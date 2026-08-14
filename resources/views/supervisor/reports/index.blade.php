@extends('layouts.app')

@section('title', 'Validasi Laporan')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('supervisor.dashboard') }}" class="nav-item"><span class="nav-icon">📊</span> Dashboard</a>
    <div class="nav-label">Validasi & Kontrol</div>
    <a href="{{ route('supervisor.reports.index') }}" class="nav-item active"><span class="nav-icon">✅</span> Validasi Laporan</a>
    <div class="nav-label">Audit & Pemantauan</div>
    <a href="{{ route('supervisor.transactions.index') }}" class="nav-item"><span class="nav-icon">💳</span> Data Transaksi</a>
    <a href="{{ route('supervisor.journals.index') }}" class="nav-item"><span class="nav-icon">📒</span> Jurnal Akuntansi</a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Validasi Laporan Teller</h1>
            <div class="breadcrumb">Supervisor / Validasi Laporan</div>
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
                <button type="submit" class="btn btn-secondary">Filter</button>
                @if(request()->has('status'))
                    <a href="{{ route('supervisor.reports.index') }}" class="btn btn-secondary">Reset</a>
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>{{ $report->report_date->format('d/m/Y') }}</td>
                            <td>{{ $report->teller->name ?? '-' }}</td>
                            <td class="font-mono text-success">Rp {{ number_format($report->total_deposit, 0, ',', '.') }}</td>
                            <td class="font-mono text-danger">Rp {{ number_format($report->total_withdrawal, 0, ',', '.') }}</td>
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
                            <td>
                                <a href="{{ route('supervisor.reports.show', $report) }}" class="btn btn-sm {{ $report->status === 'Submitted' ? 'btn-primary' : 'btn-secondary' }}">
                                    {{ $report->status === 'Submitted' ? 'Validasi' : 'Lihat' }}
                                </a>
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
