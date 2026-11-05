@extends('layouts.app')

@section('title', 'Cetak Laporan Mutasi Kas')

@section('styles')
<style>
    .period-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .period-tab-btn {
        padding: 8px 18px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #374151;
        font-weight: 600;
        font-size: 13.5px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .period-tab-btn:hover {
        background: #f3f4f6;
        color: #111827;
    }
    .period-tab-btn.active {
        background: #1d68ed;
        color: #ffffff;
        border-color: #1d68ed;
        box-shadow: 0 2px 6px rgba(29, 104, 237, 0.25);
    }
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }
    .kpi-box {
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .kpi-title {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 6px;
    }
    .kpi-amount {
        font-size: 22px;
        font-weight: 700;
        font-family: monospace;
        letter-spacing: -0.5px;
    }
    .kpi-subtitle {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 4px;
    }
    .badge-deposit {
        background: #dcfce7;
        color: #15803d;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
    }
    .badge-withdrawal {
        background: #fee2e2;
        color: #b91c1c;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
    }
</style>
@endsection

@section('content')
    <div class="page-header d-flex justify-between align-center flex-wrap gap-2 mb-3">
        <div>
            <h1 style="font-size: 22px; font-weight: 700; color: #1e293b; margin: 0 0 4px 0;">Cetak Laporan Mutasi Kas Bank Mini</h1>
            <p style="color: #64748b; font-size: 13px; margin: 0;">Laporan mutasi kas resmi harian, mingguan, dan bulanan siap cetak & simpan PDF.</p>
        </div>
        <div>
            <a href="{{ route('supervisor.reports.print-preview', request()->all()) }}" target="_blank" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:8px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Buka & Cetak PDF Resmi</span>
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('supervisor.reports.print') }}" method="GET" id="reportFilterForm">
                <input type="hidden" name="period" id="inputPeriod" value="{{ $period }}">

                {{-- Periode Switcher Tabs (Hanya Harian, Mingguan, Bulanan) --}}
                <div class="period-tabs">
                    <button type="button" class="period-tab-btn {{ $period === 'daily' ? 'active' : '' }}" onclick="switchPeriod('daily', event)">
                        Laporan Harian
                    </button>
                    <button type="button" class="period-tab-btn {{ $period === 'weekly' ? 'active' : '' }}" onclick="switchPeriod('weekly', event)">
                        Laporan Mingguan
                    </button>
                    <button type="button" class="period-tab-btn {{ $period === 'monthly' ? 'active' : '' }}" onclick="switchPeriod('monthly', event)">
                        Laporan Bulanan
                    </button>
                </div>

                <div class="row" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end;">
                    {{-- Input Periode Harian (Maksimal Hari Ini, Tidak Bisa Masa Depan) --}}
                    <div id="sectionDaily" class="period-input-group" style="display: {{ $period === 'daily' ? 'block' : 'none' }}; min-width: 200px;">
                        <label class="form-label" style="font-size: 12.5px; font-weight: 600; margin-bottom: 5px; display: block;">Tanggal Transaksi</label>
                        <input type="date" name="date" class="form-control" value="{{ $startDate }}" max="{{ now()->format('Y-m-d') }}" style="padding: 7px 12px;">
                    </div>

                    {{-- Input Periode Mingguan (Maksimal Hari Ini, Tidak Bisa Masa Depan) --}}
                    <div id="sectionWeekly" class="period-input-group" style="display: {{ $period === 'weekly' ? 'flex' : 'none' }}; gap: 8px;">
                        <div>
                            <label class="form-label" style="font-size: 12.5px; font-weight: 600; margin-bottom: 5px; display: block;">Dari Tanggal</label>
                            <input type="date" name="start_date" id="startDateWeekly" class="form-control" value="{{ $startDate }}" max="{{ now()->format('Y-m-d') }}" style="padding: 7px 12px;">
                        </div>
                        <div>
                            <label class="form-label" style="font-size: 12.5px; font-weight: 600; margin-bottom: 5px; display: block;">Sampai Tanggal</label>
                            <input type="date" name="end_date" id="endDateWeekly" class="form-control" value="{{ $endDate }}" max="{{ now()->format('Y-m-d') }}" style="padding: 7px 12px;">
                        </div>
                    </div>

                    {{-- Input Periode Bulanan (Hanya Sampai Bulan Berjalan, Tidak Bisa Masa Depan) --}}
                    <div id="sectionMonthly" class="period-input-group" style="display: {{ $period === 'monthly' ? 'flex' : 'none' }}; gap: 8px;">
                        <div style="min-width: 150px;">
                            <label class="form-label" style="font-size: 12.5px; font-weight: 600; margin-bottom: 5px; display: block;">Bulan</label>
                            <select name="month" id="selectMonth" class="form-control" style="padding: 7px 12px;">
                                @php
                                    $months = [
                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                    ];
                                    $currentYear = now()->year;
                                    $currentMonth = now()->month;
                                @endphp
                                @foreach($months as $mNum => $mName)
                                    @php
                                        $isFuture = ($selectedYear == $currentYear && $mNum > $currentMonth);
                                    @endphp
                                    <option value="{{ $mNum }}" {{ $selectedMonth == $mNum ? 'selected' : '' }} {{ $isFuture ? 'disabled' : '' }}>
                                        {{ $mName }} {{ $isFuture ? '(Masa Depan)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div style="min-width: 110px;">
                            <label class="form-label" style="font-size: 12.5px; font-weight: 600; margin-bottom: 5px; display: block;">Tahun</label>
                            <select name="year" id="selectYear" class="form-control" style="padding: 7px 12px;" onchange="handleYearChange()">
                                @for($y = now()->year; $y >= now()->year - 4; $y--)
                                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    {{-- Filter Jenis Transaksi --}}
                    <div style="min-width: 150px;">
                        <label class="form-label" style="font-size: 12.5px; font-weight: 600; margin-bottom: 5px; display: block;">Jenis Transaksi</label>
                        <select name="type" class="form-control" style="padding: 7px 12px;">
                            <option value="">Semua Transaksi</option>
                            <option value="Deposit" {{ $type === 'Deposit' ? 'selected' : '' }}>Setoran Saja</option>
                            <option value="Withdrawal" {{ $type === 'Withdrawal' ? 'selected' : '' }}>Penarikan Saja</option>
                        </select>
                    </div>

                    {{-- Filter Teller --}}
                    <div style="min-width: 160px;">
                        <label class="form-label" style="font-size: 12.5px; font-weight: 600; margin-bottom: 5px; display: block;">Petugas Teller</label>
                        <select name="teller_id" class="form-control" style="padding: 7px 12px;">
                            <option value="">Semua Teller</option>
                            @foreach($tellers as $teller)
                                <option value="{{ $teller->id }}" {{ $tellerId == $teller->id ? 'selected' : '' }}>{{ $teller->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Submit --}}
                    <div style="display:flex; gap:8px;">
                        <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">Tampilkan</button>
                        <a href="{{ route('supervisor.reports.print') }}" class="btn btn-secondary" style="padding: 8px 14px;">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Ringkasan Kas (3 KPI Card) --}}
    <div class="kpi-grid">
        <div class="kpi-box">
            <div class="kpi-title">Total Setoran Tunai</div>
            <div class="kpi-amount text-success" style="color: #15803d;">Rp {{ number_format($totalDeposit, 0, ',', '.') }}</div>
            <div class="kpi-subtitle">({{ $countDeposit }} transaksi setoran)</div>
        </div>

        <div class="kpi-box">
            <div class="kpi-title">Total Penarikan Tunai</div>
            <div class="kpi-amount text-danger" style="color: #b91c1c;">Rp {{ number_format($totalWithdrawal, 0, ',', '.') }}</div>
            <div class="kpi-subtitle">({{ $countWithdrawal }} transaksi penarikan)</div>
        </div>

        <div class="kpi-box">
            <div class="kpi-title">Kas Bersih (Net Flow)</div>
            <div class="kpi-amount text-primary" style="color: #1d68ed;">Rp {{ number_format($netFlow, 0, ',', '.') }}</div>
            <div class="kpi-subtitle">Total: {{ $totalTransactions }} transaksi</div>
        </div>
    </div>

    {{-- Tabel Pratinjau Laporan --}}
    <div class="card">
        <div class="card-header d-flex justify-between align-center" style="padding: 14px 20px; border-bottom: 1px solid #e5e7eb;">
            <div>
                <span style="font-weight: 700; color: #1e293b; font-size: 15px;">{{ $periodTitle }}</span>
                <span class="badge badge-info" style="margin-left: 8px; font-size: 12px;">{{ $periodLabel }}</span>
            </div>
            <div>
                <span style="font-size: 13px; color: #64748b;">Ditemukan: <strong>{{ $totalTransactions }}</strong> transaksi</span>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th>Waktu</th>
                        <th>No. TRX</th>
                        <th>No. Rekening</th>
                        <th>Nasabah</th>
                        <th>Kelas</th>
                        <th>Teller</th>
                        <th style="text-align: center;">Jenis</th>
                        <th style="text-align: right;">Nominal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $tx)
                        <tr>
                            <td style="text-align: center; color: #64748b;">{{ $index + 1 }}</td>
                            <td class="font-mono" style="font-size: 12.5px;">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                            <td class="font-mono" style="font-weight: 700; color: #1e293b;">TX-{{ str_pad($tx->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="font-mono" style="color: #1d68ed; font-weight: 600;">{{ $tx->nasabah->account_number ?? '-' }}</td>
                            <td style="font-weight: 600;">{{ $tx->nasabah->student_name ?? '-' }}</td>
                            <td>{{ ($tx->nasabah->class ?? '-') . ' ' . ($tx->nasabah->jurusan ?? '') }}</td>
                            <td style="color: #475569;">{{ $tx->user->name ?? 'Teller' }}</td>
                            <td style="text-align: center;">
                                @if($tx->transaction_type === 'Deposit')
                                    <span class="badge-deposit">Setoran</span>
                                @else
                                    <span class="badge-withdrawal">Penarikan</span>
                                @endif
                            </td>
                            <td class="font-mono font-bold" style="text-align: right; color: {{ $tx->transaction_type === 'Deposit' ? '#15803d' : '#b91c1c' }};">
                                {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 40px; color: #64748b;">
                                <div style="font-size: 13px; margin-top: 4px;">Tidak ada transaksi Pada Periode Ini</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($transactions->isNotEmpty())
                    <tfoot>
                        <tr style="background: #f8fafc; font-weight: 700;">
                            <td colspan="8" style="text-align: right; padding: 12px 16px; font-size: 13px; letter-spacing: 0.5px;">TOTAL KAS BERSIH:</td>
                            <td class="font-mono font-bold" style="text-align: right; padding: 12px 16px; font-size: 15px; color: #1d68ed;">
                                Rp {{ number_format($netFlow, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const currentSystemYear = {{ now()->year }};
    const currentSystemMonth = {{ now()->month }};

    function switchPeriod(periodName, event) {
        document.getElementById('inputPeriod').value = periodName;

        // Update tab buttons
        document.querySelectorAll('.period-tab-btn').forEach(btn => btn.classList.remove('active'));
        if (event && event.currentTarget) {
            event.currentTarget.classList.add('active');
        }

        // Hide all period input sections
        document.getElementById('sectionDaily').style.display = 'none';
        document.getElementById('sectionWeekly').style.display = 'none';
        document.getElementById('sectionMonthly').style.display = 'none';

        // Show active input section
        if (periodName === 'daily') {
            document.getElementById('sectionDaily').style.display = 'block';
        } else if (periodName === 'weekly') {
            document.getElementById('sectionWeekly').style.display = 'flex';
        } else if (periodName === 'monthly') {
            document.getElementById('sectionMonthly').style.display = 'flex';
            handleYearChange();
        }
    }

    function handleYearChange() {
        const yearSelect = document.getElementById('selectYear');
        const monthSelect = document.getElementById('selectMonth');
        if (!yearSelect || !monthSelect) return;

        const selectedYear = parseInt(yearSelect.value, 10);
        const options = monthSelect.options;

        for (let i = 0; i < options.length; i++) {
            const mVal = parseInt(options[i].value, 10);
            if (selectedYear === currentSystemYear && mVal > currentSystemMonth) {
                options[i].disabled = true;
                if (!options[i].text.includes('(Masa Depan)')) {
                    options[i].text = options[i].text + ' (Masa Depan)';
                }
            } else {
                options[i].disabled = false;
                options[i].text = options[i].text.replace(' (Masa Depan)', '');
            }
        }

        // If currently selected month is disabled, switch to current month
        if (selectedYear === currentSystemYear && parseInt(monthSelect.value, 10) > currentSystemMonth) {
            monthSelect.value = currentSystemMonth;
        }
    }
</script>
@endsection
