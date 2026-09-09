@extends('layouts.app')

@section('title', 'Dashboard Nasabah')



@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard Nasabah</h1>
            <div class="breadcrumb">Selamat datang, {{ $nasabah->student_name }}!</div>
        </div>
    </div>

    <div class="balance-display mb-3">
        <div class="balance-label">Total Saldo Tersedia</div>
        <div class="balance-amount">Rp {{ number_format($nasabah->balance, 0, ',', '.') }}</div>
        <div class="balance-account">No. Rekening: {{ $nasabah->account_number }}</div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h3>Informasi Profil</h3>
        </div>
        <div class="card-body">
            <div class="info-box" style="margin-bottom: 0;">
                <div class="info-row"><span class="label">Nama Lengkap</span><span class="value">{{ $nasabah->student_name }}</span></div>
                <div class="info-row"><span class="label">NIS</span><span class="value">{{ $nasabah->student_number }}</span></div>
                <div class="info-row"><span class="label">Kelas</span><span class="value">{{ $nasabah->class }}</span></div>
                <div class="info-row"><span class="label">Status Rekening</span><span class="value"><span class="status-dot {{ $nasabah->status === 'Aktif' ? 'active' : 'inactive' }}"></span>{{ $nasabah->status }}</span></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Riwayat Transaksi Terbaru</h3>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Tipe</th>
                        <th>Kredit (Masuk)</th>
                        <th>Debit (Keluar)</th>
                        <th>Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $tx)
                        <tr>
                            <td>{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($tx->transaction_type === 'Deposit')
                                    <span class="badge badge-success">Setoran</span>
                                @else
                                    <span class="badge badge-danger">Penarikan</span>
                                @endif
                            </td>
                            <td class="font-mono text-success">
                                {{ $tx->transaction_type === 'Deposit' ? 'Rp '.number_format($tx->amount, 0, ',', '.') : '-' }}
                            </td>
                            <td class="font-mono text-danger">
                                {{ $tx->transaction_type === 'Withdrawal' ? 'Rp '.number_format($tx->amount, 0, ',', '.') : '-' }}
                            </td>
                            <td class="font-mono font-bold">Rp {{ number_format($tx->balance_after, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding:2rem">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer text-center">
            <a href="{{ route('nasabah.mutasi.index') }}" class="btn btn-secondary btn-sm">Lihat Semua Mutasi</a>
        </div>
    </div>
@endsection
