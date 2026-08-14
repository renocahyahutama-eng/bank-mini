@extends('layouts.app')

@section('title', 'Penarikan Tunai')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('teller.dashboard') }}" class="nav-item"><span class="nav-icon">📊</span> Dashboard</a>
    <a href="{{ route('teller.nasabah.index') }}" class="nav-item"><span class="nav-icon">🔍</span> Cari Nasabah</a>
    <div class="nav-label">Transaksi Loket</div>
    <a href="{{ route('teller.deposit.create') }}" class="nav-item"><span class="nav-icon">📥</span> Setoran Tunai</a>
    <a href="{{ route('teller.withdrawal.create') }}" class="nav-item active"><span class="nav-icon">📤</span> Penarikan Tunai</a>
    <div class="nav-label">Laporan & Riwayat</div>
    <a href="{{ route('teller.transactions.index') }}" class="nav-item"><span class="nav-icon">🧾</span> Riwayat Transaksi</a>
    <a href="{{ route('teller.reports.index') }}" class="nav-item"><span class="nav-icon">📅</span> Laporan Harian</a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Form Penarikan Tunai</h1>
            <div class="breadcrumb">Teller / Penarikan</div>
        </div>
        <a href="{{ route('teller.nasabah.index') }}" class="btn btn-secondary">← Kembali Cari Nasabah</a>
    </div>

    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-body">
            
            @if($nasabah)
                <div class="info-box mb-3">
                    <div class="info-row"><span class="label">Nasabah</span><span class="value">{{ $nasabah->student_name }}</span></div>
                    <div class="info-row"><span class="label">No. Rekening</span><span class="value font-mono">{{ $nasabah->account_number }}</span></div>
                    <div class="info-row"><span class="label">Saldo Saat Ini</span><span class="value font-mono font-bold">Rp {{ number_format($nasabah->balance, 0, ',', '.') }}</span></div>
                    <div class="info-row"><span class="label text-danger">Saldo Tersedia (Dikurangi Rp 10.000)</span><span class="value font-mono text-danger">Rp {{ number_format(max(0, $nasabah->balance - 10000), 0, ',', '.') }}</span></div>
                </div>
            @endif

            <form action="{{ route('teller.withdrawal.store') }}" method="POST" id="form-withdrawal">
                @csrf
                
                @if(!$nasabah)
                    <div class="form-group">
                        <label for="nasabah_id">Pilih Nasabah</label>
                        <select name="nasabah_id" id="nasabah_id" class="form-control" required>
                            <option value="">-- Pilih Nasabah --</option>
                            @foreach($nasabahs as $n)
                                <option value="{{ $n->id }}" {{ old('nasabah_id') == $n->id ? 'selected' : '' }}>
                                    {{ $n->account_number }} - {{ $n->student_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden" name="nasabah_id" value="{{ $nasabah->id }}">
                @endif

                <div class="form-group">
                    <label for="amount">Nominal Penarikan (Rp)</label>
                    <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" min="1" step="1" required style="font-size: 1.5rem; font-weight: bold; font-family: monospace;">
                </div>

                <div class="form-group">
                    <label for="description">Keterangan (Opsional)</label>
                    <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}">
                </div>

                <hr style="border:none; border-top:1px dashed var(--border); margin:1.5rem 0;">

                <div class="form-group" style="background: var(--bg-body); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--danger-light);">
                    <label for="security_pin" class="text-danger">🔐 Otorisasi Nasabah</label>
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem;">Silakan minta nasabah untuk memasukkan 6 digit PIN keamanan.</p>
                    <input type="password" class="form-control" id="security_pin" name="security_pin" placeholder="PIN 6 Digit" maxlength="6" required style="text-align: center; letter-spacing: 0.5rem; font-size: 1.5rem;">
                </div>

                <button type="submit" class="btn btn-danger btn-block btn-lg mt-2">Proses Penarikan</button>
            </form>
        </div>
    </div>
@endsection
