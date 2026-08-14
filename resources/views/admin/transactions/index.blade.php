@extends('layouts.app')

@section('title', 'Data Transaksi')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-item"><span class="nav-icon">📊</span> Dashboard</a>
    <a href="{{ route('admin.users.index') }}" class="nav-item"><span class="nav-icon">👥</span> Manajemen User</a>
    <a href="{{ route('admin.nasabah.index') }}" class="nav-item"><span class="nav-icon">🎓</span> Manajemen Nasabah</a>
    <div class="nav-label">Laporan</div>
    <a href="{{ route('admin.transactions.index') }}" class="nav-item active"><span class="nav-icon">💳</span> Data Transaksi</a>
    <a href="{{ route('admin.journals.index') }}" class="nav-item"><span class="nav-icon">📒</span> Jurnal Akuntansi</a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Data Transaksi</h1>
            <div class="breadcrumb">Admin / Transaksi</div>
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
                            <td class="font-mono">Rp {{ number_format($tx->balance_before, 0, ',', '.') }}</td>
                            <td class="font-mono">Rp {{ number_format($tx->balance_after, 0, ',', '.') }}</td>
                            <td>{{ $tx->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted" style="padding:2rem">Belum ada transaksi.</td></tr>
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
