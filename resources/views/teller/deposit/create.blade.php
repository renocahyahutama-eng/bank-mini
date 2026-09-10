@extends('layouts.app')

@section('title', 'Setoran Tunai')

@section('content')
    <div class="page-header">
        <div>
            <h1>Form Setoran Tunai</h1>
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
                    <div class="info-row"><span class="label">Saldo Saat Ini</span><span class="value font-mono font-bold" style="color:var(--success);">Rp {{ number_format($nasabah->balance, 0, ',', '.') }}</span></div>
                </div>
            @endif

            <form action="{{ route('teller.deposit.store') }}" method="POST" id="form-deposit">
                @csrf
                
                @if(!$nasabah)
                    <div class="form-group">
                        <label for="nasabah_id">Pilih Nasabah <span style="color:var(--danger);">*</span></label>
                        <select name="nasabah_id" id="nasabah_id" class="form-control" required>
                            <option value="">Pilih Rekening Nasabah</option>
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
                    <label for="amount">Nominal Setoran (Rp) <span style="color:var(--danger);">*</span></label>
                    <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" min="1" step="1" placeholder="Masukkan jumlah setoran" required style="font-size: 1.4rem; font-weight: bold; font-family: monospace;">
                </div>

                <div class="form-group">
                    <label for="description">Keterangan Transaksi (Opsional)</label>
                    <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}" placeholder="Contoh: Tabungan mingguan, setoran awal">
                </div>

                <div class="alert alert-warning" style="margin-top:1.25rem;">
                    <div>
                        <strong>Peringatan Verifikasi Fisik:</strong><br>
                        Pastikan uang tunai fisik telah diterima, dihitung di depan nasabah, dan nominalnya sesuai sebelum menekan tombol proses.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg mt-3" style="width:100%; justify-content:center;" onclick="return confirm('Proses setoran tunai? Pastikan uang fisik telah diterima.')">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                    Proses Setoran Tunai
                </button>
            </form>
        </div>
    </div>
@endsection
