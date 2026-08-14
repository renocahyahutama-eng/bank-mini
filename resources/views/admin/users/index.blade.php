@extends('layouts.app')

@section('title', 'Manajemen User')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-item" id="nav-dashboard">
        <span class="nav-icon">📊</span> Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}" class="nav-item active" id="nav-users">
        <span class="nav-icon">👥</span> Manajemen User
    </a>
    <a href="{{ route('admin.nasabah.index') }}" class="nav-item" id="nav-nasabah">
        <span class="nav-icon">🎓</span> Manajemen Nasabah
    </a>
    <div class="nav-label">Laporan</div>
    <a href="{{ route('admin.transactions.index') }}" class="nav-item" id="nav-transactions">
        <span class="nav-icon">💳</span> Data Transaksi
    </a>
    <a href="{{ route('admin.journals.index') }}" class="nav-item" id="nav-journals">
        <span class="nav-icon">📒</span> Jurnal Akuntansi
    </a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Manajemen User</h1>
            <div class="breadcrumb">Admin / User</div>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary" id="btn-add-user">+ Tambah User</a>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="font-mono">{{ $user->username }}</td>
                            <td>{{ $user->name }}</td>
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
                                <span class="status-dot {{ $user->status === 'Aktif' ? 'active' : 'inactive' }}"></span>
                                {{ $user->status }}
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-secondary" id="btn-edit-user-{{ $user->id }}">Edit</a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin hapus user {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" id="btn-delete-user-{{ $user->id }}">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding:2rem">Belum ada user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
