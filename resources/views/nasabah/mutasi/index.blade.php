@extends('layouts.app')

@section('title', 'Buku Tabungan Digital')

@section('sidebar')
    <div class="nav-label">Menu E-Banking</div>
    <a href="{{ route('nasabah.dashboard') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </span>
        Dashboard Nasabah
    </a>
    <a href="{{ route('nasabah.mutasi.index') }}" class="nav-item active">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </span>
        Mutasi Rekening
    </a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Buku Tabungan Digital</h1>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('nasabah.mutasi.index') }}" method="GET" class="d-flex gap-2 align-center">
                <select name="type" class="form-control" style="max-width: 250px;">
                    <option value="">Semua Transaksi</option>
                    <option value="Deposit" {{ request('type') === 'Deposit' ? 'selected' : '' }}>Hanya Setoran Masuk</option>
                    <option value="Withdrawal" {{ request('type') === 'Withdrawal' ? 'selected' : '' }}>Hanya Penarikan Keluar</option>
                </select>
                <button type="submit" class="btn btn-secondary">Filter</button>
                @if(request()->has('type'))
                    <a href="{{ route('nasabah.mutasi.index') }}" class="btn btn-secondary">Reset</a>
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
                        <th>Waktu Transaksi</th>
                        <th>Tipe</th>
                        <th>Kredit (Setoran)</th>
                        <th>Debit (Penarikan)</th>
                        <th>Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $i => $tx)
                        <tr>
                            <td>{{ $transactions->firstItem() + $i }}</td>
                            <td>{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($tx->transaction_type === 'Deposit')
                                    <span class="badge badge-success">Setoran</span>
                                @else
                                    <span class="badge badge-danger">Penarikan</span>
                                @endif
                            </td>
                            <td class="font-mono text-success">
                                {{ $tx->transaction_type === 'Deposit' ? 'Rp '.number_format($tx->amount, 0, ',', '.') : '-' }}
                            </td>
                            <td class="font-mono text-danger">
                                {{ $tx->transaction_type === 'Withdrawal' ? 'Rp '.number_format($tx->amount, 0, ',', '.') : '-' }}
                            </td>
                            <td class="font-mono font-bold">Rp {{ number_format($tx->balance_after, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding:2rem">Belum ada transaksi pada buku tabungan.</td>
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
