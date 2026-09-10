@extends('layouts.app')

@section('title', 'Data Transaksi')



@section('content')
    <div class="page-header">
        <div>
            <h1>Data Transaksi</h1>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('supervisor.transactions.index') }}" method="GET" class="d-flex gap-2 align-center">
                <select name="type" class="form-control" style="max-width: 200px;">
                    <option value="">Semua Tipe</option>
                    <option value="Deposit" {{ request('type') === 'Deposit' ? 'selected' : '' }}>Setoran</option>
                    <option value="Withdrawal" {{ request('type') === 'Withdrawal' ? 'selected' : '' }}>Penarikan</option>
                </select>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" style="max-width: 200px;">
                <button type="submit" class="btn btn-secondary">Filter</button>
                @if(request()->hasAny(['type', 'date']))
                    <a href="{{ route('supervisor.transactions.index') }}" class="btn btn-secondary">Reset</a>
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
                        <th>Teller</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $i => $tx)
                        <tr>
                            <td>{{ $transactions->firstItem() + $i }}</td>
                            <td>{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                            <td class="font-mono">{{ $tx->nasabah->account_number ?? '-' }}</td>
                            <td>{{ $tx->nasabah->student_name ?? '-' }}</td>
                            <td>
                                @if($tx->transaction_type === 'Deposit')
                                    <span class="badge badge-success">Setoran</span>
                                @else
                                    <span class="badge badge-danger">Penarikan</span>
                                @endif
                            </td>
                            <td class="font-mono font-bold">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                            <td>{{ $tx->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted" style="padding:2rem">Belum ada transaksi.</td></tr>
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
