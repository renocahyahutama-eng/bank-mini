@extends('layouts.app')

@section('title', 'Cari Nasabah')

@section('sidebar')
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('teller.dashboard') }}" class="nav-item"><span class="nav-icon">📊</span> Dashboard</a>
    <a href="{{ route('teller.nasabah.index') }}" class="nav-item active"><span class="nav-icon">🔍</span> Cari Nasabah</a>
    <div class="nav-label">Transaksi Loket</div>
    <a href="{{ route('teller.deposit.create') }}" class="nav-item"><span class="nav-icon">📥</span> Setoran Tunai</a>
    <a href="{{ route('teller.withdrawal.create') }}" class="nav-item"><span class="nav-icon">📤</span> Penarikan Tunai</a>
    <div class="nav-label">Laporan & Riwayat</div>
    <a href="{{ route('teller.transactions.index') }}" class="nav-item"><span class="nav-icon">🧾</span> Riwayat Transaksi</a>
    <a href="{{ route('teller.reports.index') }}" class="nav-item"><span class="nav-icon">📅</span> Laporan Harian</a>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Cari Nasabah</h1>
            <div class="breadcrumb">Teller / Cari Nasabah</div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h3 style="margin-bottom:1rem; font-size:1rem; color:var(--text-primary);">Opsi 1: Pemindai QR Code (Kamera)</h3>
            
            <div class="qr-scanner-area" id="qr-reader" style="width: 100%; max-width: 500px; margin: 0 auto; display:none;"></div>
            
            <div class="text-center mt-2">
                <button type="button" class="btn btn-primary" id="btn-start-scanner">📷 Buka Kamera Scanner</button>
                <button type="button" class="btn btn-secondary" id="btn-stop-scanner" style="display:none;">Tutup Kamera</button>
            </div>
            
            <div id="scan-result" style="display:none; margin-top:1.5rem;">
                <div class="alert alert-success">✓ Nasabah ditemukan!</div>
                <div class="info-box">
                    <div class="info-row"><span class="label">No. Rekening</span><span class="value font-mono" id="res-account"></span></div>
                    <div class="info-row"><span class="label">Nama Siswa</span><span class="value" id="res-name"></span></div>
                    <div class="info-row"><span class="label">Kelas</span><span class="value" id="res-class"></span></div>
                    <div class="info-row"><span class="label">Saldo</span><span class="value font-mono font-bold">Rp <span id="res-balance"></span></span></div>
                </div>
                <div class="btn-group mt-2">
                    <a href="#" id="link-deposit" class="btn btn-success">Proses Setoran</a>
                    <a href="#" id="link-withdrawal" class="btn btn-danger">Proses Penarikan</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body border-bottom">
            <h3 style="margin-bottom:1rem; font-size:1rem; color:var(--text-primary);">Opsi 2: Pencarian Manual</h3>
            <form action="{{ route('teller.nasabah.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, NIS, atau No. Rekening..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-secondary">Cari</button>
                @if($search ?? false)
                    <a href="{{ route('teller.nasabah.index') }}" class="btn btn-secondary">Reset</a>
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
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nasabahs as $nasabah)
                        <tr>
                            <td class="font-mono">{{ $nasabah->account_number }}</td>
                            <td>{{ $nasabah->student_number }}</td>
                            <td>{{ $nasabah->student_name }}</td>
                            <td>{{ $nasabah->class }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('teller.deposit.create', ['nasabah_id' => $nasabah->id]) }}" class="btn btn-sm btn-success">Setoran</a>
                                    <a href="{{ route('teller.withdrawal.create', ['nasabah_id' => $nasabah->id]) }}" class="btn btn-sm btn-danger">Penarikan</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding:2rem">
                                @if($search ?? false)
                                    Nasabah tidak ditemukan.
                                @else
                                    Silakan ketik kata kunci untuk mencari nasabah.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
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
            // Stop scanner once we have a result
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
            }
            readerEl.style.display = 'none';
            btnStart.style.display = 'inline-flex';
            btnStop.style.display = 'none';
            
            // Expected QR content: just the account number (e.g. BM-2026-0001)
            fetchNasabahData(decodedText);
        }

        function onScanFailure(error) {
            // Ignore ongoing scan failures
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
</script>
@endsection
