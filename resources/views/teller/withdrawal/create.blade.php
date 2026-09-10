@extends('layouts.app')

@section('title', 'Penarikan Tunai')

@section('content')
    <div class="page-header">
        <div>
            <h1>Form Penarikan Tunai</h1>
        </div>
        <a href="{{ route('teller.nasabah.index') }}" class="btn btn-secondary">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali Cari Nasabah
        </a>
    </div>

    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-body">
            @if($nasabah)
                <div class="info-box mb-3">
                    <div class="info-row"><span class="label">Nama Nasabah</span><span class="value">{{ $nasabah->student_name }}</span></div>
                    <div class="info-row"><span class="label">No. Rekening</span><span class="value font-mono" style="color:var(--primary); font-weight:700;">{{ $nasabah->account_number }}</span></div>
                    <div class="info-row"><span class="label">Jurusan / Kelas</span><span class="value">{{ $nasabah->class }} {{ $nasabah->jurusan ? '(' . $nasabah->jurusan . ')' : '' }}</span></div>
                    <div class="info-row"><span class="label">Saldo Saat Ini</span><span class="value font-mono font-bold" style="color:var(--primary);">Rp {{ number_format($nasabah->balance, 0, ',', '.') }}</span></div>
                    <div class="info-row"><span class="label" style="color:var(--danger);">Saldo Maksimal Ditarik (Saldo Mengendap Rp 10.000)</span><span class="value font-mono font-bold" style="color:var(--danger);">Rp {{ number_format(max(0, $nasabah->balance - 10000), 0, ',', '.') }}</span></div>
                </div>
            @endif

            <form action="{{ route('teller.withdrawal.store') }}" method="POST" id="form-withdrawal">
                @csrf
                
                @if(!$nasabah)
                    <div class="form-group">
                        <label for="nasabah_id">Pilih Rekening Nasabah <span style="color:var(--danger);">*</span></label>
                        <select name="nasabah_id" id="nasabah_id" class="form-control" required>
                            <option value="">Pilih Nasabah</option>
                            @foreach($nasabahs as $n)
                                <option value="{{ $n->id }}" {{ old('nasabah_id') == $n->id ? 'selected' : '' }}>
                                    {{ $n->account_number }} - {{ $n->student_name }} ({{ $n->class }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden" name="nasabah_id" value="{{ $nasabah->id }}">
                @endif

                <div class="form-group">
                    <label for="amount">Nominal Penarikan (Rp) <span style="color:var(--danger);">*</span></label>
                    <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" min="1" step="1" placeholder="Masukkan jumlah penarikan" required style="font-size: 1.4rem; font-weight: bold; font-family: monospace;">
                </div>

                <div class="form-group">
                    <label for="description">Keterangan Penarikan (Opsional)</label>
                    <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}" placeholder="Contoh: Pembayaran kegiatan, keperluan pribadi">
                </div>

                <hr style="border:none; border-top:1px dashed var(--border); margin:1.5rem 0;">

                <div class="form-group" style="background: #fff1f2; padding: 1.25rem; border-radius: var(--radius); border: 1px solid #fecdd3;">
                    <label for="security_pin" style="color:#be123c; font-weight:700; display:flex; align-items:center; gap:0.4rem;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Otorisasi PIN Keamanan Nasabah
                    </label>
                    <input type="password" class="form-control" id="security_pin" name="security_pin" placeholder="••••••" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="text-align: center; letter-spacing: 0.6rem; font-size: 1.5rem; background:white;">
                </div>

                <button type="submit" class="btn btn-danger btn-block btn-lg mt-3" style="width:100%; justify-content:center;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                    Proses Penarikan Tunai
                </button>
            </form>
        </div>
    </div>
@endsection
