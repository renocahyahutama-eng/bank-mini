@extends('layouts.app')

@section('title', 'Jurnal Akuntansi')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-item"><span class="nav-icon">📊</span> Dashboard</a>
    <a href="{{ route('admin.users.index') }}" class="nav-item"><span class="nav-icon">👥</span> Manajemen User</a>
    <a href="{{ route('admin.nasabah.index') }}" class="nav-item"><span class="nav-icon">🎓</span> Manajemen Nasabah</a>
    <div class="nav-label">Laporan</div>
    <a href="{{ route('admin.transactions.index') }}" class="nav-item"><span class="nav-icon">💳</span> Data Transaksi</a>
    <a href="{{ route('admin.journals.index') }}" class="nav-item active"><span class="nav-icon">📒</span> Jurnal Akuntansi</a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Jurnal Akuntansi</h1>
            <div class="breadcrumb">Admin / Jurnal Akuntansi</div>
        </div>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Waktu</th>
                        <th>Kode Akun</th>
                        <th>Posisi</th>
                        <th>Nominal</th>
                        <th>Nasabah</th>
                        <th>Teller</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($journals as $i => $journal)
                        <tr>
                            <td>{{ $journals->firstItem() + $i }}</td>
                            <td>{{ $journal->created_at->format('d/m/Y H:i') }}</td>
                            <td class="font-mono font-bold">{{ $journal->account_code === '101' ? '101 - Kas' : '201 - Tabungan' }}</td>
                            <td>
                                @if($journal->position === 'Debit')
                                    <span class="badge badge-primary">Debit</span>
                                @else
                                    <span class="badge badge-info">Kredit</span>
                                @endif
                            </td>
                            <td class="font-mono">Rp {{ number_format($journal->amount, 0, ',', '.') }}</td>
                            <td>{{ $journal->transaction->nasabah->student_name ?? '-' }}</td>
                            <td>{{ $journal->transaction->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted" style="padding:2rem">Belum ada jurnal.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($journals->hasPages())
            <div class="card-footer">
                {{ $journals->links() }}
            </div>
        @endif
    </div>
@endsection
