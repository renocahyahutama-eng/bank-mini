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

    <div class="card" style="max-width: 650px; margin: 0 auto;">
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
                    <input type="hidden" name="nasabah_id" id="nasabah_id" value="{{ $nasabah->id }}">
                @endif

                <div class="form-group">
                    <label for="amount">Nominal Penarikan (Rp) <span style="color:var(--danger);">*</span></label>
                    <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" min="1000" step="1000" placeholder="Minimal Rp 1.000 (kelipatan ribuan)" required style="font-size: 1.4rem; font-weight: bold; font-family: monospace;">
                    <small class="text-muted" style="font-size: 0.8rem; display: block; margin-top: 0.35rem;">
                        * Nominal penarikan minimal <strong>Rp 1.000</strong> dan harus dalam <strong>kelipatan ribuan penuh</strong> (tidak menerima pecahan koin 1 rupiah atau 500 rupiah).
                    </small>
                </div>

                <div class="form-group">
                    <label for="description">Keterangan Penarikan (Opsional)</label>
                    <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}" placeholder="Contoh: Penarikan uang saku / kegiatan">
                </div>

                <!-- Live Mobile PIN Authorization Section -->
                <div style="background: #f0f7ff; border: 1.5px solid #93c5fd; border-radius: 12px; padding: 1.25rem; margin: 1.5rem 0;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 0.75rem;">
                        <span style="color: #1d4ed8; font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; gap: 0.4rem;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Otorisasi PIN via Mobile App Nasabah
                        </span>
                        <span style="background: #dbeafe; color: #1e40af; font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: 6px; font-weight: 600;">Direkomendasikan</span>
                    </div>
                    <p style="font-size: 0.85rem; color: #475569; margin-bottom: 1rem; line-height: 1.4;">
                        Kirim notifikasi otorisasi ke aplikasi mobile nasabah. Nasabah akan menginputkan 6-digit PIN keamanan langsung dari HP mereka.
                    </p>

                    <button type="button" id="btn-request-mobile-pin" class="btn btn-primary btn-block" style="width:100%; justify-content:center; padding: 0.75rem; font-weight: 600;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Kirim Permintaan PIN ke Mobile App Nasabah
                    </button>

                    <!-- Status Box when Mobile PIN is requested -->
                    <div id="mobile-auth-status-box" style="display:none; margin-top: 1rem; background: white; border: 1px solid #bfdbfe; border-radius: 8px; padding: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="spinner" style="width: 22px; height: 22px; border: 3px solid #e2e8f0; border-top-color: #2563eb; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                            <div style="flex:1;">
                                <div id="auth-status-title" style="font-weight: 600; color: #1e293b; font-size: 0.9rem;">Menunggu Nasabah menginput PIN...</div>
                                <div id="auth-status-desc" style="font-size: 0.8rem; color: #64748b;">Notifikasi telah dikirim ke perangkat nasabah. Sisa waktu: <span id="auth-countdown" style="font-weight:700; color:#ef4444;">300</span>s</div>
                            </div>
                            <button type="button" id="btn-cancel-mobile-pin" class="btn btn-sm btn-outline-danger" style="font-size:0.75rem; padding: 0.25rem 0.5rem;">Batal</button>
                        </div>
                    </div>
                </div>

                <!-- Fallback Manual PIN Input -->
                <details style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem; background: #fafafa; margin-bottom: 1.5rem;">
                    <summary style="font-size: 0.85rem; font-weight: 600; color: #64748b; cursor: pointer;">
                        Opsi Alternatif: Masukkan PIN Manual di Loket Teller
                    </summary>
                    <div style="margin-top: 0.75rem;">
                        <div class="form-group mb-0">
                            <label for="security_pin" style="color:#be123c; font-weight:600; font-size:0.85rem;">
                                PIN Keamanan Nasabah (Manual di Komputer Teller)
                            </label>
                            <input type="password" class="form-control" id="security_pin" name="security_pin" placeholder="••••••" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" style="text-align: center; letter-spacing: 0.6rem; font-size: 1.3rem; background:white;">
                        </div>
                        <button type="submit" class="btn btn-danger btn-block mt-2" style="width:100%; justify-content:center;">
                            Proses Penarikan Manual
                        </button>
                    </div>
                </details>
            </form>
        </div>
    </div>

    <style>
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnRequest = document.getElementById('btn-request-mobile-pin');
        const statusBox = document.getElementById('mobile-auth-status-box');
        const statusTitle = document.getElementById('auth-status-title');
        const statusDesc = document.getElementById('auth-status-desc');
        const countdownEl = document.getElementById('auth-countdown');
        const btnCancel = document.getElementById('btn-cancel-mobile-pin');
        const amountInput = document.getElementById('amount');
        const descInput = document.getElementById('description');
        const nasabahSelect = document.getElementById('nasabah_id');

        let currentAuthId = null;
        let pollInterval = null;
        let countdownInterval = null;
        let remainingSeconds = 300;

        btnRequest.addEventListener('click', function() {
            const nasabahId = nasabahSelect.value;
            const amount = amountInput.value;
            const desc = descInput.value;

            if (!nasabahId) {
                alert('Silakan pilih rekening nasabah terlebih dahulu.');
                return;
            }
            if (!amount || Number(amount) < 1000) {
                alert('Nominal penarikan minimal Rp 1.000 (tidak boleh 1 rupiah atau 500 rupiah).');
                amountInput.focus();
                return;
            }
            if (Number(amount) % 1000 !== 0) {
                alert('Nominal penarikan harus dalam kelipatan ribuan penuh (contoh: Rp 1.000, Rp 2.000, Rp 5.000, dst.), tidak boleh menggunakan pecahan seperti 500 rupiah.');
                amountInput.focus();
                return;
            }

            btnRequest.disabled = true;
            btnRequest.innerHTML = 'Mengirim Permintaan ke Mobile App...';

            fetch('{{ route("teller.withdrawal.request-pin") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    nasabah_id: nasabahId,
                    amount: amount,
                    description: desc
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    currentAuthId = data.authorization_id;
                    statusBox.style.display = 'block';
                    btnRequest.style.display = 'none';
                    startPolling(currentAuthId);
                } else {
                    alert('Gagal: ' + data.message);
                    btnRequest.disabled = false;
                    btnRequest.innerHTML = 'Kirim Permintaan PIN ke Mobile App Nasabah';
                }
            })
            .catch(err => {
                alert('Terjadi kesalahan koneksi.');
                btnRequest.disabled = false;
                btnRequest.innerHTML = 'Kirim Permintaan PIN ke Mobile App Nasabah';
            });
        });

        function startPolling(authId) {
            remainingSeconds = 300;
            countdownEl.innerText = remainingSeconds;

            countdownInterval = setInterval(() => {
                remainingSeconds--;
                if (countdownEl) countdownEl.innerText = remainingSeconds;
                if (remainingSeconds <= 0) {
                    stopPolling();
                    statusTitle.innerText = 'Permintaan Kedaluwarsa';
                    statusTitle.style.color = '#ef4444';
                    statusDesc.innerText = 'Waktu otorisasi telah habis. Silakan coba kembali.';
                    resetButtons();
                }
            }, 1000);

            pollInterval = setInterval(() => {
                fetch('{{ url("teller/withdrawal/check-status") }}/' + authId)
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'approved') {
                        stopPolling();
                        statusTitle.innerText = 'Otorisasi PIN Berhasil!';
                        statusTitle.style.color = '#10b981';
                        statusDesc.innerText = 'Nasabah telah memasukkan PIN yang sesuai. Sedang mencetak struk...';
                        
                        setTimeout(() => {
                            if (res.transaction_id) {
                                window.location.href = '{{ url("teller/transactions") }}/' + res.transaction_id + '/receipt';
                            } else {
                                window.location.href = '{{ route("teller.transactions.index") }}';
                            }
                        }, 1200);
                    } else if (res.status === 'rejected') {
                        stopPolling();
                        statusTitle.innerText = 'Transaksi Ditolak';
                        statusTitle.style.color = '#ef4444';
                        statusDesc.innerText = 'Nasabah menolak transaksi penarikan di aplikasi Flutter.';
                        resetButtons();
                    } else if (res.status === 'expired') {
                        stopPolling();
                        statusTitle.innerText = 'Otorisasi Kedaluwarsa';
                        statusTitle.style.color = '#ef4444';
                        resetButtons();
                    }
                })
                .catch(() => {});
            }, 2000);
        }

        function stopPolling() {
            if (pollInterval) clearInterval(pollInterval);
            if (countdownInterval) clearInterval(countdownInterval);
        }

        function resetButtons() {
            btnRequest.disabled = false;
            btnRequest.style.display = 'flex';
            btnRequest.innerHTML = 'Kirim Ulang Permintaan PIN ke Mobile App';
        }

        btnCancel.addEventListener('click', function() {
            if (!currentAuthId) return;
            fetch('{{ url("teller/withdrawal/cancel-request") }}/' + currentAuthId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).finally(() => {
                stopPolling();
                statusBox.style.display = 'none';
                resetButtons();
            });
        });
    });
    </script>
@endsection
