@extends('layouts.app')

@section('title', 'Edit Nasabah')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </span>
        Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </span>
        Manajemen User
    </a>
    <a href="{{ route('admin.nasabah.index') }}" class="nav-item active">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
        </span>
        Manajemen Nasabah
    </a>
    <div class="nav-label">Laporan</div>
    <a href="{{ route('admin.transactions.index') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        </span>
        Data Transaksi
    </a>
    <a href="{{ route('admin.journals.index') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </span>
        Jurnal Akuntansi
    </a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit Nasabah: {{ $nasabah->student_name }}</h1>
        </div>
        <div style="display:flex; gap:0.5rem;">
            <a href="{{ route('admin.nasabah.show', $nasabah) }}" class="btn btn-secondary">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                Lihat QR Code
            </a>
            <a href="{{ route('admin.nasabah.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="info-box mb-3">
                <div class="info-row">
                    <span class="label">Nomor Rekening</span>
                    <span class="value font-mono" style="color:var(--primary); font-weight:700;">{{ $nasabah->account_number }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Saldo Saat Ini</span>
                    <span class="value font-mono" style="color:var(--success); font-weight:700;">Rp {{ number_format($nasabah->balance, 0, ',', '.') }}</span>
                </div>
            </div>

            <form action="{{ route('admin.nasabah.update', $nasabah) }}" method="POST" id="form-edit-nasabah">
                @csrf
                @method('PUT')

                <h3 style="margin-bottom:1rem; color:var(--text-secondary); font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid var(--border-light); padding-bottom:0.4rem;">
                    1. Data Akademik & Identitas
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="jurusan">Jurusan / Program Keahlian</label>
                        <select class="form-control" id="jurusan" name="jurusan">
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($jurusanList as $code => $label)
                                <option value="{{ $code }}" {{ old('jurusan', $nasabah->jurusan) === $code ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="class">Kelas</label>
                        <input type="text" class="form-control" id="class" name="class" value="{{ old('class', $nasabah->class) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="student_number">NIS (Nomor Induk Siswa)</label>
                        <input type="text" class="form-control" id="student_number" name="student_number" value="{{ old('student_number', $nasabah->student_number) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="student_name">Nama Lengkap Siswa</label>
                        <input type="text" class="form-control" id="student_name" name="student_name" value="{{ old('student_name', $nasabah->student_name) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">Jenis Kelamin</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="L" {{ old('gender', $nasabah->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender', $nasabah->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">No. HP / WhatsApp</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $nasabah->phone_number) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Status Rekening</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Aktif" {{ old('status', $nasabah->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ old('status', $nasabah->status) === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="security_pin">PIN Keamanan Baru (6 Digit)</label>
                        <input type="password" class="form-control" id="security_pin" name="security_pin" placeholder="Kosongkan jika tidak ingin diubah" maxlength="6">
                        <div class="form-hint">Kosongkan jika tidak ingin mengubah PIN keamanan lama.</div>
                    </div>
                </div>

                <h3 style="margin:1.5rem 0 1rem; color:var(--text-secondary); font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid var(--border-light); padding-bottom:0.4rem;">
                    2. Akun E-Banking Siswa
                </h3>

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

                <div class="form-group" style="max-width:400px;">
                    <label for="login_password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="login_password_confirmation" name="login_password_confirmation" placeholder="Ulangi password baru">
                </div>

                <div class="btn-group mt-3">
                    <button type="submit" class="btn btn-primary" id="btn-update-nasabah">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.nasabah.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
