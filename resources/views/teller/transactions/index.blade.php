@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('teller.dashboard') }}" class="nav-item">
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
    <a href="{{ route('teller.transactions.index') }}" class="nav-item active">
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
            <h1>Riwayat Transaksi (Anda)</h1>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('teller.transactions.index') }}" method="GET" style="display:flex; flex-wrap:wrap; gap:0.65rem; align-items:center;">
                <div style="flex:1; min-width:220px;">
                    <div style="position:relative;">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama nasabah / no. rekening..." value="{{ request('search') }}" style="padding-left:2.2rem;">
                        <span style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:var(--text-muted);">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                    </div>
                </div>
                <select name="type" class="form-control" style="width:auto; min-width:140px;">
                    <option value="">Semua Tipe</option>
                    <option value="Deposit" {{ request('type') === 'Deposit' ? 'selected' : '' }}>Setoran</option>
                    <option value="Withdrawal" {{ request('type') === 'Withdrawal' ? 'selected' : '' }}>Penarikan</option>
                </select>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" style="width:auto;">
                <button type="submit" class="btn btn-primary btn-sm">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari & Filter
                </button>
                @if(request()->hasAny(['search', 'type', 'date']))
                    <a href="{{ route('teller.transactions.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>No. Rekening</th>
                        <th>Nama Nasabah</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        <tr>
                            <td>
                                <span style="font-weight:600;">{{ $tx->created_at->format('H:i') }}</span>
                                <span style="font-size:0.72rem; color:var(--text-muted); display:block;">{{ $tx->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="font-mono font-bold">{{ $tx->nasabah->account_number ?? '-' }}</td>
                            <td>{{ $tx->nasabah->student_name ?? '-' }}</td>
                            <td>
                                @if($tx->transaction_type === 'Deposit')
                                    <span class="badge badge-success">Setoran</span>
                                @else
                                    <span class="badge badge-danger">Penarikan</span>
                                @endif
                            </td>
                            <td class="font-mono font-bold {{ $tx->transaction_type === 'Deposit' ? 'text-success' : 'text-danger' }}">
                                {{ $tx->transaction_type === 'Deposit' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                            <td style="max-width:200px; font-size:0.8rem; color:var(--text-secondary);">{{ $tx->description ?: '-' }}</td>
                            <td style="text-align:center;">
                                <a href="{{ route('teller.transactions.receipt', $tx) }}" target="_blank" class="btn btn-secondary btn-sm" style="padding:0.25rem 0.65rem; font-size:0.75rem; display:inline-flex; align-items:center; gap:0.35rem;" title="Cetak Struk Digital">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    Cetak Struk
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding:2.5rem 1rem;">
                                Tidak ada transaksi yang sesuai kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="card-footer">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
@endsection
