@extends('layouts.app')

@section('title', 'Detail Nasabah')

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
            <h1>Detail Profil Nasabah</h1>
            <div class="breadcrumb">{{ $nasabah->student_name }}</div>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.nasabah.edit', $nasabah) }}" class="btn btn-secondary">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                Edit Data
            </a>
            <a href="{{ route('admin.nasabah.index') }}" class="btn btn-secondary">← Kembali</a>
        </div>
    </div>

    <div style="display:grid; grid-template-columns: 1fr 320px; gap:1.25rem; align-items:start; margin-bottom:1.5rem;">
        {{-- Kolom Kiri: Balance & Info --}}
        <div>
            <div class="balance-display" style="margin-bottom:1rem;">
                <div class="balance-label">Total Saldo Tersimpan</div>
                <div class="balance-amount">Rp {{ number_format($nasabah->balance, 0, ',', '.') }}</div>
                <div class="balance-account">{{ $nasabah->account_number }}</div>
            </div>

            <div class="card">
                <div class="card-header" style="border-bottom:1px solid var(--border);">
                    <h3 style="margin:0; font-size:0.95rem;">Informasi Data Nasabah</h3>
                </div>
                <div class="card-body">
                    <div class="info-box" style="margin-bottom:0;">
                        <div class="info-row"><span class="label">No. Rekening</span><span class="value font-mono" style="color:var(--primary);">{{ $nasabah->account_number }}</span></div>
                        <div class="info-row"><span class="label">NIS (No. Induk Siswa)</span><span class="value font-mono">{{ $nasabah->student_number }}</span></div>
                        <div class="info-row"><span class="label">Nama Lengkap</span><span class="value">{{ $nasabah->student_name }}</span></div>
                        <div class="info-row"><span class="label">Jurusan</span><span class="value"><span class="badge badge-info">{{ $nasabah->jurusan ?: '-' }}</span></span></div>
                        <div class="info-row"><span class="label">Kelas</span><span class="value">{{ $nasabah->class }}</span></div>
                        <div class="info-row"><span class="label">Jenis Kelamin</span><span class="value">{{ $nasabah->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                        <div class="info-row"><span class="label">No. HP / WhatsApp</span><span class="value">{{ $nasabah->phone_number ?: '-' }}</span></div>
                        <div class="info-row">
                            <span class="label">Status Rekening</span>
                            <span class="value">
                                @if($nasabah->status === 'Aktif')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row"><span class="label">Username Login Siswa</span><span class="value font-mono">{{ $nasabah->customerAccount->username ?? '-' }}</span></div>
                        <div class="info-row"><span class="label">Tanggal Registrasi</span><span class="value">{{ $nasabah->created_at->format('d/m/Y H:i') }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Kartu QR Code Unik Nasabah --}}
        <div class="card" id="card-qr" style="text-align:center;">
            <div class="card-header" style="border-bottom:1px solid var(--border);">
                <h3 style="margin:0; font-size:0.95rem; display:flex; align-items:center; justify-content:center; gap:0.4rem;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    QR Code Nasabah
                </h3>
            </div>
            <div class="card-body" style="padding:1.5rem 1rem;">
                <div id="qrcode-container" style="display:inline-block; padding:12px; background:white; border:2px solid var(--border); border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.04);"></div>

                <div style="margin-top:1rem;">
                    <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-muted);">Nomor Rekening</div>
                    <div class="font-mono" style="font-size:1.1rem; font-weight:800; color:var(--primary); margin-top:0.15rem;">{{ $nasabah->account_number }}</div>
                    <div style="font-size:0.82rem; font-weight:600; color:var(--text-primary); margin-top:0.25rem;">{{ $nasabah->student_name }}</div>
                    <div style="font-size:0.75rem; color:var(--text-muted);">{{ $nasabah->class }} {{ $nasabah->jurusan ? '(' . $nasabah->jurusan . ')' : '' }}</div>
                </div>

                <div style="margin-top:1.25rem; display:flex; flex-direction:column; gap:0.5rem;">
                    <button type="button" class="btn btn-primary btn-sm" onclick="printQRCode()" style="width:100%; justify-content:center;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak Kartu QR
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="downloadQRCode()" style="width:100%; justify-content:center;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Unduh Gambar QR
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Transaksi Nasabah --}}
    <div class="card">
        <div class="card-header">
            <h3 style="margin:0;">Riwayat Transaksi Nasabah</h3>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Saldo Sebelum</th>
                        <th>Saldo Sesudah</th>
                        <th>Petugas (Teller)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nasabah->transactions as $tx)
                        <tr>
                            <td>{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($tx->transaction_type === 'Deposit')
                                    <span class="badge badge-success">Setoran</span>
                                @else
                                    <span class="badge badge-danger">Penarikan</span>
                                @endif
                            </td>
                            <td class="font-mono font-bold {{ $tx->transaction_type === 'Deposit' ? 'text-success' : 'text-danger' }}">
                                {{ $tx->transaction_type === 'Deposit' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                            <td class="font-mono">Rp {{ number_format($tx->balance_before, 0, ',', '.') }}</td>
                            <td class="font-mono font-bold">Rp {{ number_format($tx->balance_after, 0, ',', '.') }}</td>
                            <td>{{ $tx->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding:2rem;">Belum ada riwayat transaksi pada rekening ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Script QR Code --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        const qrContainer = document.getElementById("qrcode-container");
        const accountNumber = "{{ $nasabah->account_number }}";

        const qrcode = new QRCode(qrContainer, {
            text: accountNumber,
            width: 160,
            height: 160,
            colorDark: "#0f172a",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        function downloadQRCode() {
            const canvas = qrContainer.querySelector('canvas');
            if (canvas) {
                const link = document.createElement('a');
                link.download = 'QR_' + accountNumber + '.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            } else {
                const img = qrContainer.querySelector('img');
                if (img) {
                    const link = document.createElement('a');
                    link.download = 'QR_' + accountNumber + '.png';
                    link.href = img.src;
                    link.click();
                }
            }
        }

        function printQRCode() {
            const printWindow = window.open('', '', 'width=450,height=550');
            const canvas = qrContainer.querySelector('canvas');
            const qrImgSrc = canvas ? canvas.toDataURL('image/png') : (qrContainer.querySelector('img')?.src || '');

            printWindow.document.write(`
                <html>
                <head>
                    <title>Kartu QR Nasabah - ${accountNumber}</title>
                    <style>
                        body { font-family: 'Inter', sans-serif; text-align: center; padding: 20px; }
                        .card { border: 2px solid #0f172a; border-radius: 12px; padding: 20px; max-width: 300px; margin: 0 auto; }
                        .title { font-size: 14px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }
                        .subtitle { font-size: 11px; color: #666; margin-bottom: 15px; }
                        .acc-num { font-family: monospace; font-size: 18px; font-weight: bold; margin-top: 12px; }
                        .name { font-size: 14px; font-weight: 600; margin-top: 4px; }
                        .class { font-size: 12px; color: #555; }
                    </style>
                </head>
                <body>
                    <div class="card">
                        <div class="title">BANK MINI SEKOLAH</div>
                        <div class="subtitle">KARTU QR NASABAH</div>
                        <img src="${qrImgSrc}" style="width:160px; height:160px;" />
                        <div class="acc-num">${accountNumber}</div>
                        <div class="name">{{ $nasabah->student_name }}</div>
                        <div class="class">{{ $nasabah->class }} {{ $nasabah->jurusan ? '(' . $nasabah->jurusan . ')' : '' }}</div>
                    </div>
                    <script>
                        window.onload = function() { window.print(); window.close(); }
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>
@endsection
