@extends('layouts.app')

@section('title', 'Tambah Nasabah')

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
            <h1>Tambah Nasabah Baru</h1>
            <div class="breadcrumb">Admin / Nasabah / Tambah</div>
        </div>
        <a href="{{ route('admin.nasabah.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="alert alert-info mb-2">
                ℹ No. Rekening akan dibuat otomatis: <strong>{{ $accountNumber }}</strong>
            </div>

            <form action="{{ route('admin.nasabah.store') }}" method="POST" id="form-create-nasabah">
                @csrf

                <h3 style="margin-bottom:1rem; color:var(--text-secondary); font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px;">Data Siswa</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="student_number">NIS (Nomor Induk Siswa)</label>
                        <input type="text" class="form-control" id="student_number" name="student_number" value="{{ old('student_number') }}" placeholder="Contoh: 12345" required>
                    </div>
                    <div class="form-group">
                        <label for="student_name">Nama Siswa</label>
                        <input type="text" class="form-control" id="student_name" name="student_name" value="{{ old('student_name') }}" placeholder="Nama lengkap siswa" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="class">Kelas</label>
                        <input type="text" class="form-control" id="class" name="class" value="{{ old('class') }}" placeholder="Contoh: XII RPL 1" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Jenis Kelamin</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="">Pilih</option>
                            <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone_number">No. HP (Opsional)</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="form-group">
                        <label for="security_pin">PIN Keamanan (6 digit)</label>
                        <input type="password" class="form-control" id="security_pin" name="security_pin" placeholder="6 digit angka" maxlength="6" required>
                        <div class="form-hint">PIN digunakan untuk otorisasi penarikan tunai.</div>
                    </div>
                </div>

                <h3 style="margin:1.5rem 0 1rem; color:var(--text-secondary); font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px;">Akun Login Nasabah</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="login_username">Username Login</label>
                        <input type="text" class="form-control" id="login_username" name="login_username" value="{{ old('login_username') }}" placeholder="Username untuk login nasabah" required>
                    </div>
                    <div class="form-group">
                        <label for="login_password">Password Login</label>
                        <input type="password" class="form-control" id="login_password" name="login_password" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="login_password_confirmation">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="login_password_confirmation" name="login_password_confirmation" placeholder="Ulangi password" required>
                </div>

                <div class="btn-group mt-2">
                    <button type="submit" class="btn btn-primary" id="btn-submit-nasabah">Simpan Nasabah</button>
                    <a href="{{ route('admin.nasabah.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
