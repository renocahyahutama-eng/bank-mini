@extends('layouts.app')

@section('title', 'Data Transaksi')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </span>
        Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </span>
        Manajemen User
    </a>
    <a href="{{ route('admin.nasabah.index') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
        </span>
        Manajemen Nasabah
    </a>
    <div class="nav-label">Laporan</div>
    <a href="{{ route('admin.transactions.index') }}" class="nav-item active">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        </span>
        Data Transaksi
    </a>
    <a href="{{ route('admin.journals.index') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </span>
        Jurnal Akuntansi
    </a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Data Seluruh Transaksi</h1>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.transactions.index') }}" method="GET" style="display:flex; flex-wrap:wrap; gap:0.65rem; align-items:center;">
                <div style="flex:1; min-width:220px;">
                    <div style="position:relative;">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama nasabah, no. rekening, teller..." value="{{ request('search') }}" style="padding-left:2.2rem;">
                        <span style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:var(--text-muted);">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                    </div>
                </div>
                <select name="type" class="form-control" style="width:auto; min-width:130px;">
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
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Waktu</th>
                        <th>No. Rekening</th>
                        <th>Nasabah</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Saldo Sebelum</th>
                        <th>Saldo Sesudah</th>
                        <th>Teller</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $i => $tx)
                        <tr>
                            <td>{{ $transactions->firstItem() + $i }}</td>
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
                            <td class="font-mono" style="color:var(--text-muted);">Rp {{ number_format($tx->balance_before, 0, ',', '.') }}</td>
                            <td class="font-mono font-bold">Rp {{ number_format($tx->balance_after, 0, ',', '.') }}</td>
                            <td>{{ $tx->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted" style="padding:2.5rem 1rem;">Tidak ada data transaksi yang ditemukan.</td></tr>
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
