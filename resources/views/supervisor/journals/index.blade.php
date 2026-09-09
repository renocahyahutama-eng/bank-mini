@extends('layouts.app')

@section('title', 'Jurnal Akuntansi')



@section('content')
    <div class="page-header">
        <div>
            <h1>Jurnal Akuntansi (Audit Trail)</h1>
            <div class="breadcrumb">Supervisor / Jurnal Akuntansi</div>
        </div>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Waktu</th>
                        <th>Kode Akun</th>
                        <th>Posisi</th>
                        <th>Nominal</th>
                        <th>Referensi Transaksi</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($journals as $i => $journal)
                        <tr>
                            <td>{{ $journals->firstItem() + $i }}</td>
                            <td>{{ $journal->created_at->format('d/m/Y H:i') }}</td>
                            <td class="font-mono font-bold">{{ $journal->account_code === '101' ? '101 - Kas' : '201 - Tabungan' }}</td>
                            <td>
                                @if($journal->position === 'Debit')
                                    <span class="badge badge-primary">Debit</span>
                                @else
                                    <span class="badge badge-info">Kredit</span>
                                @endif
                            </td>
                            <td class="font-mono">Rp {{ number_format($journal->amount, 0, ',', '.') }}</td>
                            <td>{{ $journal->transaction->transaction_type }} - {{ $journal->transaction->nasabah->account_number ?? '-' }}</td>
                            <td>{{ $journal->transaction->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted" style="padding:2rem">Belum ada jurnal.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($journals->hasPages())
            <div class="card-footer">
                {{ $journals->links() }}
            </div>
        @endif
    </div>
@endsection
