@extends('layouts.app')

@section('title', 'Manajemen Nasabah')

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
            <h1>Manajemen Nasabah</h1>
            <div class="breadcrumb">Admin / Nasabah</div>
        </div>
        <a href="{{ route('admin.nasabah.create') }}" class="btn btn-primary" id="btn-add-nasabah">+ Tambah Nasabah</a>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Rekening</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Saldo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nasabahs as $i => $nasabah)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="font-mono">{{ $nasabah->account_number }}</td>
                            <td>{{ $nasabah->student_number }}</td>
                            <td>{{ $nasabah->student_name }}</td>
                            <td>{{ $nasabah->class }}</td>
                            <td class="font-mono font-bold">Rp {{ number_format($nasabah->balance, 0, ',', '.') }}</td>
                            <td>
                                <span class="status-dot {{ $nasabah->status === 'Aktif' ? 'active' : 'inactive' }}"></span>
                                {{ $nasabah->status }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.nasabah.show', $nasabah) }}" class="btn btn-sm btn-secondary" id="btn-view-nasabah-{{ $nasabah->id }}">Detail</a>
                                    <a href="{{ route('admin.nasabah.edit', $nasabah) }}" class="btn btn-sm btn-primary" id="btn-edit-nasabah-{{ $nasabah->id }}">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted" style="padding:2rem">Belum ada nasabah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
