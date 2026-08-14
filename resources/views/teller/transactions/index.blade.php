@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('teller.dashboard') }}" class="nav-item"><span class="nav-icon">📊</span> Dashboard</a>
    <a href="{{ route('teller.nasabah.index') }}" class="nav-item"><span class="nav-icon">🔍</span> Cari Nasabah</a>
    <div class="nav-label">Transaksi Loket</div>
    <a href="{{ route('teller.deposit.create') }}" class="nav-item"><span class="nav-icon">📥</span> Setoran Tunai</a>
    <a href="{{ route('teller.withdrawal.create') }}" class="nav-item"><span class="nav-icon">📤</span> Penarikan Tunai</a>
    <div class="nav-label">Laporan & Riwayat</div>
    <a href="{{ route('teller.transactions.index') }}" class="nav-item active"><span class="nav-icon">🧾</span> Riwayat Transaksi</a>
    <a href="{{ route('teller.reports.index') }}" class="nav-item"><span class="nav-icon">📅</span> Laporan Harian</a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Riwayat Transaksi (Anda)</h1>
            <div class="breadcrumb">Teller / Riwayat Transaksi</div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('teller.transactions.index') }}" method="GET" class="d-flex gap-2 align-center">
                <select name="type" class="form-control" style="max-width: 200px;">
                    <option value="">Semua Tipe</option>
                    <option value="Deposit" {{ request('type') === 'Deposit' ? 'selected' : '' }}>Setoran</option>
                    <option value="Withdrawal" {{ request('type') === 'Withdrawal' ? 'selected' : '' }}>Penarikan</option>
                </select>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" style="max-width: 200px;">
                <button type="submit" class="btn btn-secondary">Filter</button>
                @if(request()->hasAny(['type', 'date']))
                    <a href="{{ route('teller.transactions.index') }}" class="btn btn-secondary">Reset</a>
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        <tr>
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
                            <td class="font-mono font-bold {{ $tx->transaction_type === 'Deposit' ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                            <td>{{ $tx->description ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding:2rem">Belum ada riwayat transaksi.</td>
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
