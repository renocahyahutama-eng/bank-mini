<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi #{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }} - E-Teller Bank Mini</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border-dash: #cbd5e1;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #f1f5f9;
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .action-bar {
            width: 100%;
            max-width: 400px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.55rem 1rem;
            font-size: 0.82rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: white;
            color: #334155;
            border-color: #cbd5e1;
        }

        .btn-secondary:hover {
            background: #f8fafc;
        }

        .receipt-card {
            width: 100%;
            max-width: 400px;
            background: white;
            border-radius: 12px;
            padding: 2rem 1.75rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            position: relative;
        }

        .receipt-header {
            text-align: center;
            padding-bottom: 1.25rem;
            border-bottom: 2px dashed var(--border-dash);
        }

        .bank-badge {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--primary);
            background: #eff6ff;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            margin-bottom: 0.5rem;
        }

        .bank-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.2rem;
        }

        .bank-subtitle {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .tx-type-badge {
            margin-top: 0.75rem;
            display: inline-block;
            padding: 0.25rem 0.85rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .tx-type-deposit {
            background: #dcfce7;
            color: #166534;
        }

        .tx-type-withdrawal {
            background: #fee2e2;
            color: #991b1b;
        }

        .receipt-info {
            padding: 1.25rem 0;
            border-bottom: 2px dashed var(--border-dash);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.82rem;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            color: var(--text-muted);
        }

        .info-val {
            font-weight: 600;
            text-align: right;
            color: #1e293b;
        }

        .info-val.mono {
            font-family: 'JetBrains Mono', monospace;
        }

        .amount-section {
            padding: 1.25rem 0;
            border-bottom: 2px dashed var(--border-dash);
            text-align: center;
        }

        .amount-label {
            font-size: 0.72rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .amount-val {
            font-size: 1.85rem;
            font-weight: 800;
            font-family: 'JetBrains Mono', monospace;
            color: #0f172a;
        }

        .balance-summary {
            padding: 1rem 0;
            background: #f8fafc;
            border-radius: 8px;
            margin-top: 1rem;
            padding: 0.85rem 1rem;
        }

        .balance-summary .info-row {
            font-size: 0.78rem;
        }

        .balance-summary .info-val {
            font-family: 'JetBrains Mono', monospace;
        }

        .receipt-footer {
            padding-top: 1.25rem;
            text-align: center;
            font-size: 0.72rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .receipt-footer .qr-placeholder {
            margin: 0.75rem auto;
            width: 80px;
            height: 80px;
            background: white;
            border: 1px solid #e2e8f0;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        /* ── Media Print ── */
        @media print {
            body {
                background: white;
                padding: 0;
                min-height: auto;
            }

            .action-bar {
                display: none !important;
            }

            .receipt-card {
                box-shadow: none;
                border: 1px dashed #94a3b8;
                max-width: 100%;
                width: 100%;
                padding: 1rem;
            }

            @page {
                size: 80mm auto;
                margin: 5mm;
            }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <a href="javascript:window.close();" class="btn btn-secondary">
            &larr; Tutup
        </a>
        <button onclick="window.print()" class="btn btn-primary" id="btn-print">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak / Simpan PDF
        </button>
    </div>

    <div class="receipt-card">
        <div class="receipt-header">
            <div class="bank-badge">E-Teller Bank Mini</div>
            <h1 class="bank-title">BANK MINI SEKOLAH</h1>
            <p class="bank-subtitle">Bukti Transaksi Loket Teller Resmi</p>
            <div>
                <span class="tx-type-badge {{ $transaction->transaction_type === 'Deposit' ? 'tx-type-deposit' : 'tx-type-withdrawal' }}">
                    {{ $transaction->transaction_type === 'Deposit' ? 'Setoran Tunai' : 'Penarikan Tunai' }}
                </span>
            </div>
        </div>

        <div class="receipt-info">
            <div class="info-row">
                <span class="info-label">No. Transaksi</span>
                <span class="info-val mono">TX-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal / Waktu</span>
                <span class="info-val mono">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Petugas (Teller)</span>
                <span class="info-val">{{ $transaction->user->name ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">No. Rekening</span>
                <span class="info-val mono" style="color:var(--primary); font-weight:700;">{{ $transaction->nasabah->account_number ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nama Nasabah</span>
                <span class="info-val">{{ $transaction->nasabah->student_name ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kelas / Jurusan</span>
                <span class="info-val">{{ $transaction->nasabah->class ?? '-' }} {{ $transaction->nasabah->jurusan ? '(' . $transaction->nasabah->jurusan . ')' : '' }}</span>
            </div>
            @if($transaction->description)
                <div class="info-row">
                    <span class="info-label">Keterangan</span>
                    <span class="info-val">{{ $transaction->description }}</span>
                </div>
            @endif
        </div>

        <div class="amount-section">
            <div class="amount-label">Nominal Transaksi</div>
            <div class="amount-val">
                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
            </div>
        </div>

        <div class="balance-summary">
            <div class="info-row">
                <span class="info-label">Saldo Sebelum</span>
                <span class="info-val">Rp {{ number_format($transaction->balance_before, 0, ',', '.') }}</span>
            </div>
            <div class="info-row" style="margin-top:0.35rem; padding-top:0.35rem; border-top:1px dashed #e2e8f0;">
                <span class="info-label" style="font-weight:700; color:#0f172a;">Saldo Akhir</span>
                <span class="info-val" style="font-weight:700; color:var(--primary); font-size:0.9rem;">
                    Rp {{ number_format($transaction->balance_after, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="receipt-footer">
            <div id="receipt-qrcode" style="display:flex; justify-content:center; margin:0.85rem 0;"></div>
            <p><strong>Terima kasih atas transaksi Anda!</strong></p>
            <p style="margin-top:0.25rem;">Struk digital ini adalah bukti transaksi yang sah dan diakui secara resmi oleh Bank Mini Sekolah.</p>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // Render QR Code pada struk
        try {
            new QRCode(document.getElementById("receipt-qrcode"), {
                text: "{{ $transaction->nasabah->account_number ?? 'TX-' . $transaction->id }}",
                width: 70,
                height: 70,
                colorDark: "#0f172a",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        } catch(e) {
            console.error(e);
        }
    </script>
</body>
</html>
