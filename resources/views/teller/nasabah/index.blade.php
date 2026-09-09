@extends('layouts.app')

@section('title', 'Cari Nasabah & Scanner QR')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('teller.dashboard') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </span>
        Dashboard
    </a>
    <a href="{{ route('teller.nasabah.index') }}" class="nav-item active">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </span>
        Cari Nasabah / QR
    </a>
    <div class="nav-label">Transaksi Loket</div>
    <a href="{{ route('teller.deposit.create') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
        </span>
        Setoran Tunai
    </a>
    <a href="{{ route('teller.withdrawal.create') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
        </span>
        Penarikan Tunai
    </a>
    <div class="nav-label">Laporan & Riwayat</div>
    <a href="{{ route('teller.transactions.index') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </span>
        Riwayat Transaksi
    </a>
    <a href="{{ route('teller.reports.index') }}" class="nav-item">
        <span class="nav-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </span>
        Laporan Harian
    </a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Identifikasi Nasabah</h1>
            <div class="breadcrumb">Pindai QR Code atau cari data nasabah untuk transaksi loket</div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h3 style="margin-bottom:0.75rem; font-size:0.95rem; color:var(--text-primary); display:flex; align-items:center; gap:0.4rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                Opsi 1: Pemindai Kamera QR Code Nasabah
            </h3>
            
            <div class="qr-scanner-area" id="qr-reader" style="width: 100%; max-width: 480px; margin: 0 auto; display:none;"></div>
            
            <div class="text-center mt-2">
                <button type="button" class="btn btn-primary" id="btn-start-scanner">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Aktifkan Kamera Pemindai QR
                </button>
                <button type="button" class="btn btn-secondary" id="btn-stop-scanner" style="display:none;">Tutup Kamera</button>
            </div>
            
            {{-- Hasil Scan dengan QR Display --}}
            <div id="scan-result" style="display:none; margin-top:1.5rem; background:#f8fafc; border:1px solid var(--border); border-radius:10px; padding:1.25rem;">
                <div class="alert alert-success mb-2" style="font-weight:600;">
                    ✓ Nasabah Berhasil Teridentifikasi!
                </div>
                <div style="display:grid; grid-template-columns: 140px 1fr; gap:1.25rem; align-items:center;">
                    <div id="scan-qr-preview" style="background:white; padding:8px; border:1px solid var(--border); border-radius:8px; text-align:center;"></div>
                    <div class="info-box" style="margin-bottom:0;">
                        <div class="info-row"><span class="label">No. Rekening</span><span class="value font-mono" id="res-account" style="color:var(--primary); font-weight:700;"></span></div>
                        <div class="info-row"><span class="label">Nama Lengkap</span><span class="value" id="res-name"></span></div>
                        <div class="info-row"><span class="label">Kelas</span><span class="value" id="res-class"></span></div>
                        <div class="info-row"><span class="label">Saldo Saat Ini</span><span class="value font-mono font-bold" style="color:var(--success);">Rp <span id="res-balance"></span></span></div>
                    </div>
                </div>
                <div class="btn-group mt-3">
                    <a href="#" id="link-deposit" class="btn btn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                        Proses Setoran Tunai
                    </a>
                    <a href="#" id="link-withdrawal" class="btn btn-danger">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                        Proses Penarikan Tunai
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Pencarian Manual --}}
    <div class="card">
        <div class="card-body border-bottom">
            <h3 style="margin-bottom:0.75rem; font-size:0.95rem; color:var(--text-primary); display:flex; align-items:center; gap:0.4rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Opsi 2: Pencarian Manual Nasabah
            </h3>
            <form action="{{ route('teller.nasabah.index') }}" method="GET" style="display:flex; gap:0.65rem;">
                <div style="flex:1; position:relative;">
                    <input type="text" name="search" class="form-control" placeholder="Ketik nama nasabah, NIS, atau No. Rekening..." value="{{ $search ?? '' }}" style="padding-left:2.2rem;">
                    <span style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:var(--text-muted);">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                @if($search ?? false)
                    <a href="{{ route('teller.nasabah.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                @endif
            </form>
        </div>
        
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No. Rekening</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Jurusan & Kelas</th>
                        <th>Saldo</th>
                        <th style="text-align:center;">Aksi Loket</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nasabahs as $nasabah)
                        <tr>
                            <td class="font-mono font-bold" style="color:var(--primary);">{{ $nasabah->account_number }}</td>
                            <td class="font-mono">{{ $nasabah->student_number }}</td>
                            <td style="font-weight:600;">{{ $nasabah->student_name }}</td>
                            <td>
                                @if($nasabah->jurusan)
                                    <span class="badge badge-info" style="font-size:0.7rem; font-weight:700;">{{ $nasabah->jurusan }}</span>
                                @endif
                                <span style="font-size:0.82rem; color:var(--text-secondary);">{{ $nasabah->class }}</span>
                            </td>
                            <td class="font-mono font-bold">Rp {{ number_format($nasabah->balance, 0, ',', '.') }}</td>
                            <td style="text-align:center;">
                                <div class="btn-group" style="justify-content:center;">
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="showQRModal('{{ $nasabah->account_number }}', '{{ addslashes($nasabah->student_name) }}', '{{ $nasabah->class }}', '{{ number_format($nasabah->balance, 0, ',', '.') }}')" title="Lihat Profil & QR">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                        QR
                                    </button>
                                    <a href="{{ route('teller.deposit.create', ['nasabah_id' => $nasabah->id]) }}" class="btn btn-sm btn-primary" title="Setoran Tunai">
                                        Setoran
                                    </a>
                                    <a href="{{ route('teller.withdrawal.create', ['nasabah_id' => $nasabah->id]) }}" class="btn btn-sm btn-secondary" style="color:var(--danger); border-color:#fecaca;" title="Penarikan Tunai">
                                        Penarikan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding:2.5rem 1rem;">
                                @if($search ?? false)
                                    Nasabah dengan kata kunci "{{ $search }}" tidak ditemukan.
                                @else
                                    Silakan ketik kata kunci untuk mencari nasabah atau gunakan scanner QR di atas.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Pop-up QR Code Nasabah untuk Teller --}}
    <div id="modal-qr" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; backdrop-filter:blur(2px);">
        <div style="background:white; border-radius:12px; padding:1.75rem; max-width:360px; width:90%; text-align:center; box-shadow:0 10px 25px rgba(0,0,0,0.15);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                <h3 style="margin:0; font-size:1rem;">QR Code Profil Nasabah</h3>
                <button type="button" onclick="closeQRModal()" style="border:none; background:none; font-size:1.25rem; cursor:pointer; color:var(--text-muted);">&times;</button>
            </div>
            <div id="modal-qrcode-container" style="display:inline-block; padding:10px; background:white; border:1px solid var(--border); border-radius:8px;"></div>
            <div style="margin-top:0.75rem;">
                <div class="font-mono" id="modal-qr-account" style="font-size:1.15rem; font-weight:800; color:var(--primary);"></div>
                <div id="modal-qr-name" style="font-weight:600; font-size:0.9rem; margin-top:0.2rem;"></div>
                <div id="modal-qr-class" style="font-size:0.75rem; color:var(--text-muted);"></div>
                <div style="margin-top:0.5rem; font-weight:700; color:var(--success);" id="modal-qr-balance"></div>
            </div>
            <div style="margin-top:1.25rem;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeQRModal()" style="width:100%; justify-content:center;">Tutup</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnStart = document.getElementById('btn-start-scanner');
        const btnStop = document.getElementById('btn-stop-scanner');
        const readerEl = document.getElementById('qr-reader');
        const resultEl = document.getElementById('scan-result');
        let html5QrcodeScanner = null;

        btnStart.addEventListener('click', function() {
            readerEl.style.display = 'block';
            btnStart.style.display = 'none';
            btnStop.style.display = 'inline-flex';
            resultEl.style.display = 'none';

            html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", 
                { fps: 10, qrbox: {width: 250, height: 250} },
                false
            );
            
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });

        btnStop.addEventListener('click', function() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
            }
            readerEl.style.display = 'none';
            btnStart.style.display = 'inline-flex';
            btnStop.style.display = 'none';
        });

        function onScanSuccess(decodedText, decodedResult) {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
            }
            readerEl.style.display = 'none';
            btnStart.style.display = 'inline-flex';
            btnStop.style.display = 'none';
            
            fetchNasabahData(decodedText.trim());
        }

        function onScanFailure(error) {
            // scan tick failure ignored
        }

        function fetchNasabahData(accountNumber) {
            fetch('{{ route("teller.nasabah.qr-lookup") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ account_number: accountNumber })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('res-account').textContent = data.nasabah.account_number;
                    document.getElementById('res-name').textContent = data.nasabah.student_name;
                    document.getElementById('res-class').textContent = data.nasabah.class;
                    document.getElementById('res-balance').textContent = data.nasabah.balance;
                    
                    document.getElementById('link-deposit').href = `{{ url('teller/deposit') }}?nasabah_id=${data.nasabah.id}`;
                    document.getElementById('link-withdrawal').href = `{{ url('teller/withdrawal') }}?nasabah_id=${data.nasabah.id}`;
                    
                    // Render QR Code preview
                    const qrPreviewEl = document.getElementById('scan-qr-preview');
                    qrPreviewEl.innerHTML = '';
                    new QRCode(qrPreviewEl, {
                        text: data.nasabah.account_number,
                        width: 110,
                        height: 110,
                        colorDark: "#0f172a",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.M
                    });

                    resultEl.style.display = 'block';
                } else {
                    alert('Nasabah tidak ditemukan atau rekening tidak aktif.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mencari data nasabah.');
            });
        }
    });

    // Modal QR Code Profil untuk Teller
    function showQRModal(accountNumber, studentName, studentClass, balance) {
        document.getElementById('modal-qr-account').textContent = accountNumber;
        document.getElementById('modal-qr-name').textContent = studentName;
        document.getElementById('modal-qr-class').textContent = studentClass;
        document.getElementById('modal-qr-balance').textContent = 'Saldo: Rp ' + balance;

        const qrContainer = document.getElementById('modal-qrcode-container');
        qrContainer.innerHTML = '';
        new QRCode(qrContainer, {
            text: accountNumber,
            width: 150,
            height: 150,
            colorDark: "#0f172a",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        const modal = document.getElementById('modal-qr');
        modal.style.display = 'flex';
    }

    function closeQRModal() {
        document.getElementById('modal-qr').style.display = 'none';
    }
</script>
@endsection
