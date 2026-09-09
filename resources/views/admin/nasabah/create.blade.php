@extends('layouts.app')

@section('title', 'Tambah Nasabah')

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
            <h1>Tambah Nasabah Baru</h1>
            <div class="breadcrumb">Admin / Nasabah / Pendaftaran Rekening Baru</div>
        </div>
        <a href="{{ route('admin.nasabah.index') }}" class="btn btn-secondary">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="alert alert-info mb-3" style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <strong>Format Nomor Rekening:</strong> <span class="font-mono" id="account-preview" style="font-size:1rem; font-weight:700; color:var(--primary);">{{ $accountNumber }}</span>
                    <div style="font-size:0.75rem; color:var(--text-secondary); margin-top:0.2rem;">Nomor rekening otomatis dibuat mengikuti format: [JURUSAN]-[TAHUN]-[NO_URUT]</div>
                </div>
                <div style="font-size:0.75rem; background:white; padding:0.35rem 0.75rem; border-radius:4px; border:1px solid #bae6fd;">
                    QR Code dibuat otomatis
                </div>
            </div>

            <form action="{{ route('admin.nasabah.store') }}" method="POST" id="form-create-nasabah">
                @csrf

                <h3 style="margin-bottom:1rem; color:var(--text-secondary); font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid var(--border-light); padding-bottom:0.4rem;">
                    1. Data Akademik & Identitas Siswa
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="jurusan">Jurusan / Program Keahlian <span style="color:var(--danger);">*</span></label>
                        <select class="form-control" id="jurusan" name="jurusan" required onchange="updateAccountPreview(this.value)">
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($jurusanList as $code => $label)
                                <option value="{{ $code }}" {{ old('jurusan', 'RPL') === $code ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-hint">Prefix nomor rekening akan disesuaikan dengan jurusan ini.</div>
                    </div>
                    <div class="form-group">
                        <label for="class">Kelas <span style="color:var(--danger);">*</span></label>
                        <input type="text" class="form-control" id="class" name="class" value="{{ old('class') }}" placeholder="Contoh: X RPL 1, XI TKJ 2" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="student_number">NIS (Nomor Induk Siswa) <span style="color:var(--danger);">*</span></label>
                        <input type="text" class="form-control" id="student_number" name="student_number" value="{{ old('student_number') }}" placeholder="Contoh: 20261001" required>
                    </div>
                    <div class="form-group">
                        <label for="student_name">Nama Lengkap Siswa <span style="color:var(--danger);">*</span></label>
                        <input type="text" class="form-control" id="student_name" name="student_name" value="{{ old('student_name') }}" placeholder="Nama sesuai identitas resmi" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">Jenis Kelamin <span style="color:var(--danger);">*</span></label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">No. Handphone / WhatsApp</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="Contoh: 081234567890">
                    </div>
                </div>

                <div class="form-group">
                    <label for="security_pin">PIN Keamanan Transaksi (6 Digit Angka) <span style="color:var(--danger);">*</span></label>
                    <input type="password" class="form-control" id="security_pin" name="security_pin" placeholder="Masukkan 6 digit angka rahasia" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="max-width:300px;">
                    <div class="form-hint">PIN wajib 6 digit angka, digunakan oleh nasabah untuk otorisasi penarikan tunai di loket.</div>
                </div>

                <h3 style="margin:1.75rem 0 1rem; color:var(--text-secondary); font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid var(--border-light); padding-bottom:0.4rem;">
                    2. Akun E-Banking Siswa (Login Portal)
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="login_username">Username Login <span style="color:var(--danger);">*</span></label>
                        <input type="text" class="form-control" id="login_username" name="login_username" value="{{ old('login_username') }}" placeholder="Username untuk akses nasabah" required>
                    </div>
                    <div class="form-group">
                        <label for="login_password">Password Login <span style="color:var(--danger);">*</span></label>
                        <input type="password" class="form-control" id="login_password" name="login_password" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>

                <div class="form-group" style="max-width:400px;">
                    <label for="login_password_confirmation">Konfirmasi Password <span style="color:var(--danger);">*</span></label>
                    <input type="password" class="form-control" id="login_password_confirmation" name="login_password_confirmation" placeholder="Ulangi password" required>
                </div>

                <div class="btn-group mt-3">
                    <button type="submit" class="btn btn-primary" id="btn-submit-nasabah">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan & Buat Akun Nasabah
                    </button>
                    <a href="{{ route('admin.nasabah.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const currentYear = '{{ date('Y') }}';
        function updateAccountPreview(jurusan) {
            const previewEl = document.getElementById('account-preview');
            if (jurusan) {
                previewEl.textContent = jurusan.toUpperCase() + '-' + currentYear + '-XXXX (Otomatis dibuat)';
            } else {
                previewEl.textContent = 'Pilih jurusan terlebih dahulu';
            }
        }
    </script>
@endsection
