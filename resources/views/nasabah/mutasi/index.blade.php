@extends('layouts.app')

@section('title', 'Buku Tabungan Digital')



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
