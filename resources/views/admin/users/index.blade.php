@extends('layouts.app')

@section('title', 'Manajemen User')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-item" id="nav-dashboard">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </span>
        Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}" class="nav-item active" id="nav-users">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </span>
        Manajemen User
    </a>
    <a href="{{ route('admin.nasabah.index') }}" class="nav-item" id="nav-nasabah">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
        </span>
        Manajemen Nasabah
    </a>
    <div class="nav-label">Laporan</div>
    <a href="{{ route('admin.transactions.index') }}" class="nav-item" id="nav-transactions">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        </span>
        Data Transaksi
    </a>
    <a href="{{ route('admin.journals.index') }}" class="nav-item" id="nav-journals">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </span>
        Jurnal Akuntansi
    </a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Manajemen User</h1>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary" id="btn-add-user">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah User
        </a>
    </div>

    {{-- Search Bar --}}
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET" style="display:flex; gap:0.65rem; align-items:center;">
                <div style="flex:1; position:relative;">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, username, atau role pegawai..." value="{{ request('search') }}" style="padding-left:2.2rem;">
                    <span style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:var(--text-muted);">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Reset</a>
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
                        <th>Username</th>
                        <th>Nama Pegawai</th>
                        <th>Role Akses</th>
                        <th>Status</th>
                        <th>Terdaftar</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $i }}</td>
                            <td class="font-mono font-bold">{{ $user->username }}</td>
                            <td>
                                <div style="font-weight:600;">{{ $user->name }}</div>
                                @if($user->id === auth()->id())
                                    <span style="font-size:0.7rem; color:var(--primary);">(Akun Anda)</span>
                                @endif
                            </td>
                            <td>
                                @if($user->role === 'Administrator')
                                    <span class="badge badge-primary">{{ $user->role }}</span>
                                @elseif($user->role === 'Supervisor')
                                    <span class="badge badge-warning">{{ $user->role }}</span>
                                @else
                                    <span class="badge badge-info">{{ $user->role }}</span>
                                @endif
                            </td>
                            <td>
                                @if($user->status === 'Aktif')
                                    <span class="badge badge-success" style="display:inline-flex; align-items:center; gap:0.3rem;">
                                        <span class="status-dot active" style="margin:0;"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge badge-danger" style="display:inline-flex; align-items:center; gap:0.3rem;">
                                        <span class="status-dot inactive" style="margin:0;"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td style="text-align:center;">
                                <div class="btn-group" style="justify-content:center;">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-secondary" id="btn-edit-user-{{ $user->id }}" title="Edit User">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Edit
                                    </a>

                                    @if($user->id !== auth()->id())
                                        {{-- Fitur Hapus Ditiadakan, Diganti Toggle Aktif/Nonaktif --}}
                                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" onsubmit="return confirm('Ubah status user {{ $user->name }} menjadi {{ $user->status === 'Aktif' ? 'Nonaktif' : 'Aktif' }}?')">
                                            @csrf
                                            @method('PATCH')
                                            @if($user->status === 'Aktif')
                                                <button type="submit" class="btn btn-sm" style="background:#fff1f2; color:#be123c; border:1px solid #fecdd3;" title="Nonaktifkan User">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                    Nonaktifkan
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-sm" style="background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;" title="Aktifkan User">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Aktifkan
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding:2.5rem 1rem;">
                                Tidak ada data user yang sesuai pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
