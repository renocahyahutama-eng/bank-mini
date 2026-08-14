@extends('layouts.app')

@section('title', 'Edit Nasabah')

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
            <h1>Edit Nasabah: {{ $nasabah->student_name }}</h1>
            <div class="breadcrumb">Admin / Nasabah / Edit</div>
        </div>
        <a href="{{ route('admin.nasabah.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="info-box mb-2">
                <div class="info-row">
                    <span class="label">No. Rekening</span>
                    <span class="value font-mono">{{ $nasabah->account_number }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Saldo Saat Ini</span>
                    <span class="value font-mono">Rp {{ number_format($nasabah->balance, 0, ',', '.') }}</span>
                </div>
            </div>

            <form action="{{ route('admin.nasabah.update', $nasabah) }}" method="POST" id="form-edit-nasabah">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="student_number">NIS</label>
                        <input type="text" class="form-control" id="student_number" name="student_number" value="{{ old('student_number', $nasabah->student_number) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="student_name">Nama Siswa</label>
                        <input type="text" class="form-control" id="student_name" name="student_name" value="{{ old('student_name', $nasabah->student_name) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="class">Kelas</label>
                        <input type="text" class="form-control" id="class" name="class" value="{{ old('class', $nasabah->class) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Jenis Kelamin</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="L" {{ old('gender', $nasabah->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender', $nasabah->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone_number">No. HP</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $nasabah->phone_number) }}">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Aktif" {{ old('status', $nasabah->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ old('status', $nasabah->status) === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="security_pin">PIN Keamanan Baru (6 digit)</label>
                    <input type="password" class="form-control" id="security_pin" name="security_pin" placeholder="Kosongkan jika tidak diubah" maxlength="6">
                    <div class="form-hint">Kosongkan jika tidak ingin mengubah PIN.</div>
                </div>

                <h3 style="margin:1.5rem 0 1rem; color:var(--text-secondary); font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px;">Akun Login Nasabah</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="login_username">Username Login</label>
                        <input type="text" class="form-control" id="login_username" name="login_username" value="{{ old('login_username', $nasabah->customerAccount->username ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="login_password">Password Login Baru</label>
                        <input type="password" class="form-control" id="login_password" name="login_password" placeholder="Kosongkan jika tidak diubah">
                    </div>
                </div>

                <div class="form-group">
                    <label for="login_password_confirmation">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="login_password_confirmation" name="login_password_confirmation" placeholder="Ulangi password baru">
                </div>

                <div class="btn-group mt-2">
                    <button type="submit" class="btn btn-primary" id="btn-update-nasabah">Simpan Perubahan</button>
                    <a href="{{ route('admin.nasabah.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
