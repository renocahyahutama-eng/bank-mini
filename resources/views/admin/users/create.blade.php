@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
    <div class="page-header">
        <div>
            <h1>Tambah User Baru</h1>
            <div class="breadcrumb">Admin / User / Pendaftaran Pegawai</div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST" id="form-create-user">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username <span style="color:var(--danger);">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username login" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Nama Lengkap <span style="color:var(--danger);">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Nama lengkap pegawai" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="role">Role / Jabatan <span style="color:var(--danger);">*</span></label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="Administrator" {{ old('role') === 'Administrator' ? 'selected' : '' }}>Administrator</option>
                            <option value="Supervisor" {{ old('role') === 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                            <option value="Teller" {{ old('role') === 'Teller' ? 'selected' : '' }}>Teller</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status Akun <span style="color:var(--danger);">*</span></label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Aktif" {{ old('status') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ old('status') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password <span style="color:var(--danger);">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password <span style="color:var(--danger);">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required>
                    </div>
                </div>

                <div class="btn-group mt-3">
                    <button type="submit" class="btn btn-primary" id="btn-submit-user">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
