<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $periodTitle }} — Bank Mini SMKN 1 Bantul</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            color: #1e293b;
            background: #f1f5f9;
            padding: 24px;
            font-size: 12px;
            line-height: 1.4;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Action Bar (Only on Screen) */
        .action-bar {
            max-width: 860px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }

        .btn-primary {
            background: #1d68ed;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #154cb8;
        }

        .btn-secondary {
            background: #f8fafc;
            color: #475569;
            border-color: #cbd5e1;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        /* Printable Document Sheet */
        .sheet {
            max-width: 860px;
            margin: 0 auto;
            background: #ffffff;
            padding: 32px 36px 40px 36px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        }

        /* Kop Surat Resmi */
        .kop-surat {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 12px;
            border-bottom: 3px double #0f172a;
            margin-bottom: 22px;
        }

        .kop-logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .kop-content {
            flex-grow: 1;
            text-align: center;
        }

        .kop-instansi {
            font-size: 12.5px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #334155;
            margin-bottom: 2px;
        }

        .kop-sekolah {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .kop-unit {
            font-size: 13.5px;
            font-weight: 700;
            color: #1d68ed;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .kop-alamat {
            font-size: 10.5px;
            color: #64748b;
            line-height: 1.3;
        }

        /* Judul Laporan */
        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-title {
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .report-period {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
        }

        /* KPI Summary Grid (3 Cards as in Reference) */
        .kpi-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 14px;
            margin-bottom: 22px;
            page-break-inside: avoid;
        }

        .kpi-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
            background: #ffffff;
        }

        .kpi-label {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 4px;
        }

        .kpi-value {
            font-size: 17px;
            font-weight: 800;
            font-family: 'Consolas', 'Courier New', monospace;
            margin-bottom: 3px;
        }

        .kpi-info {
            font-size: 10.5px;
            color: #94a3b8;
        }

        .val-deposit {
            color: #16a34a;
        }

        .val-withdrawal {
            color: #dc2626;
        }

        .val-net {
            color: #1d68ed;
        }

        /* Table */
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 24px;
        }

        table.report-table th,
        table.report-table td {
            border: 1px solid #cbd5e1;
            padding: 7px 9px;
            vertical-align: middle;
        }

        table.report-table th {
            background-color: #f8fafc;
            color: #1e293b;
            font-weight: 700;
            text-align: left;
            font-size: 11px;
        }

        table.report-table tbody tr:nth-child(even) {
            background-color: #fafbfc;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-mono {
            font-family: 'Consolas', 'Courier New', monospace;
        }

        .font-bold {
            font-weight: 700;
        }

        .badge-type {
            font-weight: 700;
            font-size: 11px;
        }

        .type-deposit {
            color: #16a34a;
        }

        .type-withdrawal {
            color: #dc2626;
        }

        /* Signature Section (HANYA SUPERVISOR, TANPA KEPALA SEKOLAH) */
        .signature-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 36px;
            page-break-inside: avoid;
        }

        .signature-box {
            text-align: center;
            min-width: 240px;
        }

        .sig-date {
            font-size: 11.5px;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .sig-title {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 64px; /* Signature spacing */
        }

        .sig-name {
            font-size: 12.5px;
            font-weight: 800;
            color: #0f172a;
            text-decoration: underline;
            margin-bottom: 2px;
        }

        .sig-nip {
            font-size: 11px;
            color: #475569;
        }

        /* PRINT STYLING */
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm 12mm 12mm 12mm;
            }

            body {
                background: #ffffff !important;
                padding: 0 !important;
                font-size: 10.5pt;
                color: #000000 !important;
            }

            .action-bar,
            .no-print {
                display: none !important;
            }

            .sheet {
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }

            table.report-table th {
                background-color: #f1f5f9 !important;
                color: #000000 !important;
            }

            .kpi-card {
                border-color: #cbd5e1 !important;
            }

            table.report-table,
            table.report-table th,
            table.report-table td {
                border-color: #94a3b8 !important;
            }

            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    {{-- Screen Action Bar --}}
    <div class="action-bar no-print">
        <a href="{{ route('supervisor.reports.print', request()->all()) }}" class="btn btn-secondary">
            &larr; Kembali ke Filter
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            Cetak / Simpan PDF
        </button>
    </div>

    {{-- Document Sheet --}}
    <div class="sheet">

        {{-- Kop Surat Resmi SMKN 1 Bantul --}}
        <div class="kop-surat">
            <img src="{{ asset('images/logo-smkn1bantul.png') }}" alt="Logo SMKN 1 Bantul" class="kop-logo" onerror="this.src='{{ asset('images/logo.png') }}'">
            <div class="kop-content">
                <div class="kop-instansi">Pemerintah Daerah Istimewa Yogyakarta</div>
                <div class="kop-sekolah">SMK NEGERI 1 BANTUL</div>
                <div class="kop-unit">UNIT LAYANAN BANK MINI SEKOLAH</div>
                <div class="kop-alamat">Jl. Bantul No. 1, Sabdodadi, Bantul, D.I. Yogyakarta 55715 &bull; Telp: (0274) 367156</div>
            </div>
        </div>

        {{-- Judul Laporan --}}
        <div class="report-header">
            <div class="report-title">{{ $periodTitle }}</div>
            <div class="report-period">Periode: {{ $periodLabel }}</div>
        </div>

        {{-- 3 KPI Summary Cards --}}
        <div class="kpi-container">
            <div class="kpi-card">
                <div class="kpi-label">Total Setoran Tunai</div>
                <div class="kpi-value val-deposit">Rp {{ number_format($totalDeposit, 0, ',', '.') }}</div>
                <div class="kpi-info">({{ $countDeposit }} transaksi)</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Total Penarikan Tunai</div>
                <div class="kpi-value val-withdrawal">Rp {{ number_format($totalWithdrawal, 0, ',', '.') }}</div>
                <div class="kpi-info">({{ $countWithdrawal }} transaksi)</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Kas Bersih (Net Flow)</div>
                <div class="kpi-value val-net">Rp {{ number_format($netFlow, 0, ',', '.') }}</div>
                <div class="kpi-info">Total: {{ $totalTransactions }} transaksi</div>
            </div>
        </div>

        {{-- Data Table --}}
        <table class="report-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 32px;">No</th>
                    <th style="width: 100px;">Waktu</th>
                    <th style="width: 80px;">No. TRX</th>
                    <th style="width: 85px;">No. Rekening</th>
                    <th>Nasabah</th>
                    <th style="width: 85px;">Kelas</th>
                    <th style="width: 90px;">Teller</th>
                    <th class="text-center" style="width: 65px;">Jenis</th>
                    <th class="text-right" style="width: 95px;">Nominal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $index => $tx)
                    <tr>
                        <td class="text-center" style="color: #64748b;">{{ $index + 1 }}</td>
                        <td class="font-mono">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                        <td class="font-mono font-bold">TX-{{ str_pad($tx->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="font-mono font-bold" style="color: #1d68ed;">{{ $tx->nasabah->account_number ?? '-' }}</td>
                        <td class="font-bold">{{ $tx->nasabah->student_name ?? '-' }}</td>
                        <td>{{ ($tx->nasabah->class ?? '-') . ' ' . ($tx->nasabah->jurusan ?? '') }}</td>
                        <td>{{ $tx->user->name ?? 'Teller' }}</td>
                        <td class="text-center">
                            @if($tx->transaction_type === 'Deposit')
                                <span class="badge-type type-deposit">Setoran</span>
                            @else
                                <span class="badge-type type-withdrawal">Penarikan</span>
                            @endif
                        </td>
                        <td class="text-right font-mono font-bold {{ $tx->transaction_type === 'Deposit' ? 'type-deposit' : 'type-withdrawal' }}">
                            {{ number_format($tx->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 24px; color: #64748b;">
                            Tidak ada catatan transaksi mutasi kas pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($transactions->isNotEmpty())
                <tfoot>
                    <tr style="background: #f8fafc; font-weight: bold;">
                        <td colspan="8" class="text-right font-bold" style="padding: 8px 12px; font-size: 11.5px; letter-spacing: 0.5px;">TOTAL KAS BERSIH:</td>
                        <td class="text-right font-mono font-bold" style="padding: 8px 12px; font-size: 12.5px; color: #1d68ed;">
                            Rp {{ number_format($netFlow, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            @endif
        </table>

        {{-- Bagian Tanda Tangan (HANYA SUPERVISOR, TANDA TANGAN KEPALA SEKOLAH DIHILANGKAN SESUAI INSTRUKSI) --}}
        <div class="signature-container">
            <div class="signature-box">
                <div class="sig-date">Bantul, {{ now()->translatedFormat('d F Y') }}</div>
                <div class="sig-title">Supervisor Bank Mini</div>
                <div class="sig-name">( {{ auth()->user()->name ?? 'Supervisor Bank' }} )</div>
                <div class="sig-nip">NIP/ID: {{ auth()->user()->username ?? 'supervisor' }}</div>
            </div>
        </div>

    </div>

</body>
</html>

