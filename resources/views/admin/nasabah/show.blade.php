@extends('layouts.app')

@section('title', 'Detail Nasabah')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-item"><span class="nav-icon">📊</span> Dashboard</a>
    <a href="{{ route('admin.users.index') }}" class="nav-item"><span class="nav-icon">👥</span> Manajemen User</a>
    <a href="{{ route('admin.nasabah.index') }}" class="nav-item active"><span class="nav-icon">🎓</span> Manajemen Nasabah</a>
    <div class="nav-label">Laporan</div>
    <a href="{{ route('admin.transactions.index') }}" class="nav-item"><span class="nav-icon">💳</span> Data Transaksi</a>
    <a href="{{ route('admin.journals.index') }}" class="nav-item"><span class="nav-icon">📒</span> Jurnal Akuntansi</a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Detail Nasabah</h1>
            <div class="breadcrumb">Admin / Nasabah / Detail</div>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.nasabah.edit', $nasabah) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('admin.nasabah.index') }}" class="btn btn-secondary">← Kembali</a>
        </div>
    </div>

    <div class="balance-display">
        <div class="balance-label">Saldo Saat Ini</div>
        <div class="balance-amount">Rp {{ number_format($nasabah->balance, 0, ',', '.') }}</div>
        <div class="balance-account">{{ $nasabah->account_number }} — {{ $nasabah->student_name }}</div>
    </div>

    <div class="card mb-2">
        <div class="card-header"><h3>Informasi Nasabah</h3></div>
        <div class="card-body">
            <div class="info-box">
                <div class="info-row"><span class="label">No. Rekening</span><span class="value font-mono">{{ $nasabah->account_number }}</span></div>
                <div class="info-row"><span class="label">NIS</span><span class="value">{{ $nasabah->student_number }}</span></div>
                <div class="info-row"><span class="label">Nama Siswa</span><span class="value">{{ $nasabah->student_name }}</span></div>
                <div class="info-row"><span class="label">Kelas</span><span class="value">{{ $nasabah->class }}</span></div>
                <div class="info-row"><span class="label">Jenis Kelamin</span><span class="value">{{ $nasabah->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                <div class="info-row"><span class="label">No. HP</span><span class="value">{{ $nasabah->phone_number ?: '-' }}</span></div>
                <div class="info-row"><span class="label">Status</span><span class="value"><span class="status-dot {{ $nasabah->status === 'Aktif' ? 'active' : 'inactive' }}"></span>{{ $nasabah->status }}</span></div>
                <div class="info-row"><span class="label">Username Login</span><span class="value font-mono">{{ $nasabah->customerAccount->username ?? '-' }}</span></div>
                <div class="info-row"><span class="label">Terdaftar</span><span class="value">{{ $nasabah->created_at->format('d/m/Y H:i') }}</span></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Riwayat Transaksi</h3></div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Saldo Sebelum</th>
                        <th>Saldo Sesudah</th>
                        <th>Teller</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nasabah->transactions as $tx)
                        <tr>
                            <td>{{ $tx->created_at->format('d/m/Y H:i') }}</td>
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
                        <tr><td colspan="6" class="text-center text-muted" style="padding:2rem">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
