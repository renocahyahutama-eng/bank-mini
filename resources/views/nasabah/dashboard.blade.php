@extends('layouts.app')

@section('title', 'Dashboard Nasabah')

@section('sidebar')
    <div class="nav-label">Menu E-Banking</div>
    <a href="{{ route('nasabah.dashboard') }}" class="nav-item active">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </span>
        Dashboard Nasabah
    </a>
    <a href="{{ route('nasabah.mutasi.index') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </span>
        Mutasi Rekening
    </a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard Nasabah</h1>
            <div class="breadcrumb">Selamat datang, {{ $nasabah->student_name }}!</div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns: 1fr 340px; gap:1.25rem; align-items:start; margin-bottom:1.5rem;">
        {{-- Kolom Kiri: Saldo & Info Profil Detail --}}
        <div>
            {{-- Balance Card --}}
            <div class="balance-display" style="margin-bottom:1rem; position:relative; overflow:hidden;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <div class="balance-label">Total Saldo Tersedia</div>
                        <div class="balance-amount">Rp {{ number_format($nasabah->balance, 0, ',', '.') }}</div>
                        <div class="balance-account">{{ $nasabah->account_number }}</div>
                    </div>
                    @if($nasabah->jurusan)
                        <span class="badge badge-info" style="font-size:0.8rem; font-weight:800; padding:0.35rem 0.75rem; background:rgba(255,255,255,0.2); color:#fff; border:1px solid rgba(255,255,255,0.3);">
                            {{ $nasabah->jurusan }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Detail Informasi Nasabah --}}
            <div class="card">
                <div class="card-header" style="border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
                    <h3 style="margin:0; font-size:0.95rem; display:flex; align-items:center; gap:0.4rem;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Detail Profil Nasabah
                    </h3>
                    <span class="badge {{ $nasabah->status === 'Aktif' ? 'badge-success' : 'badge-danger' }}">
                        {{ $nasabah->status }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="info-box" style="margin-bottom:0;">
                        <div class="info-row">
                            <span class="label">No. Rekening</span>
                            <span class="value font-mono font-bold" style="color:var(--primary);">{{ $nasabah->account_number }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">NIS (No. Induk Siswa)</span>
                            <span class="value font-mono">{{ $nasabah->student_number }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Nama Lengkap</span>
                            <span class="value font-bold">{{ $nasabah->student_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Jurusan</span>
                            <span class="value">
                                @if($nasabah->jurusan)
                                    <span class="badge badge-info" style="font-weight:700;">{{ $nasabah->jurusan }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">Kelas</span>
                            <span class="value">{{ $nasabah->class }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Jenis Kelamin</span>
                            <span class="value">{{ $nasabah->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">No. Telepon / WhatsApp</span>
                            <span class="value">{{ $nasabah->phone_number ?: '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Username E-Banking</span>
                            <span class="value font-mono">{{ $nasabah->customerAccount->username ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Status Akun</span>
                            <span class="value">
                                <span class="status-dot {{ $nasabah->status === 'Aktif' ? 'active' : 'inactive' }}"></span>
                                {{ $nasabah->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Kartu QR Code Pribadi Nasabah --}}
        <div class="card" id="card-qr" style="text-align:center;">
            <div class="card-header" style="border-bottom:1px solid var(--border);">
                <h3 style="margin:0; font-size:0.95rem; display:flex; align-items:center; justify-content:center; gap:0.4rem;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    QR Code Nasabah Anda
                </h3>
            </div>
            <div class="card-body" style="padding:1.5rem 1rem;">
                <div id="nasabah-qrcode" style="display:inline-block; padding:12px; background:white; border:2px solid var(--border); border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.06);"></div>

                <div style="margin-top:1rem;">
                    <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-muted);">Nomor Rekening</div>
                    <div class="font-mono" style="font-size:1.15rem; font-weight:800; color:var(--primary); margin-top:0.15rem;">{{ $nasabah->account_number }}</div>
                    <div style="font-size:0.85rem; font-weight:600; color:var(--text-primary); margin-top:0.25rem;">{{ $nasabah->student_name }}</div>
                    <div style="font-size:0.78rem; color:var(--text-muted); margin-top:0.1rem;">
                        {{ $nasabah->class }} @if($nasabah->jurusan) &bull; <span class="badge badge-info" style="font-size:0.68rem; font-weight:700;">{{ $nasabah->jurusan }}</span> @endif
                    </div>
                </div>

                <div style="margin-top:1rem; font-size:0.74rem; color:var(--text-muted); background:var(--bg-body); padding:0.6rem 0.75rem; border-radius:8px; border:1px solid var(--border); line-height:1.4;">
                    Tunjukkan QR Code ini ke petugas loket Teller untuk setoran atau penarikan cepat tanpa perlu mengetik nomor rekening.
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

    {{-- Riwayat Transaksi Terbaru --}}
    <div class="card">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h3 style="margin:0; font-size:0.95rem; display:flex; align-items:center; gap:0.4rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Riwayat Transaksi Terbaru
            </h3>
            <a href="{{ route('nasabah.mutasi.index') }}" class="btn btn-secondary btn-sm">
                Lihat Semua Mutasi
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Waktu (WIB)</th>
                        <th>Tipe</th>
                        <th>Kredit (Masuk)</th>
                        <th>Debit (Keluar)</th>
                        <th>Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $tx)
                        <tr>
                            <td><span style="font-weight:600;">{{ $tx->created_at->format('d/m/Y H:i') }} WIB</span></td>
                            <td>
                                @if($tx->transaction_type === 'Deposit')
                                    <span class="badge badge-success">Setoran</span>
                                @else
                                    <span class="badge badge-danger">Penarikan</span>
                                @endif
                            </td>
                            <td class="font-mono text-success" style="font-weight:600;">
                                {{ $tx->transaction_type === 'Deposit' ? '+ Rp '.number_format($tx->amount, 0, ',', '.') : '-' }}
                            </td>
                            <td class="font-mono text-danger" style="font-weight:600;">
                                {{ $tx->transaction_type === 'Withdrawal' ? '- Rp '.number_format($tx->amount, 0, ',', '.') : '-' }}
                            </td>
                            <td class="font-mono font-bold">Rp {{ number_format($tx->balance_after, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding:2.5rem 1rem;">
                                Belum ada riwayat transaksi pada rekening ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        const accountNumber = '{{ $nasabah->account_number }}';
        const qrContainer = document.getElementById('nasabah-qrcode');

        // Render QR Code
        new QRCode(qrContainer, {
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
                        body { font-family: 'Inter', system-ui, sans-serif; text-align: center; padding: 25px; }
                        .card { border: 2px solid #0f172a; border-radius: 14px; padding: 22px; max-width: 320px; margin: 0 auto; box-shadow: 0 4px 10px rgba(0,0,0,0.08); }
                        .title { font-size: 15px; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px; }
                        .subtitle { font-size: 11px; color: #64748b; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px; }
                        .acc-num { font-family: monospace; font-size: 19px; font-weight: 800; margin-top: 14px; color: #2563eb; }
                        .name { font-size: 15px; font-weight: 700; margin-top: 4px; color: #0f172a; }
                        .class { font-size: 12px; color: #475569; margin-top: 2px; }
                    </style>
                </head>
                <body>
                    <div class="card">
                        <img src="{{ asset('images/logo.png') }}" style="width:48px; height:48px; object-fit:contain; margin:0 auto 10px; display:block;" />
                        <div class="title">Bank Mini SMKN 1 Bantul</div>
                        <div class="subtitle">Kartu QR Rekening Nasabah</div>
                        <img src="${qrImgSrc}" style="width:160px; height:160px; margin: 0 auto; display:block;" />
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
