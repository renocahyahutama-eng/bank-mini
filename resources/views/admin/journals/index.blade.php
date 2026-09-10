@extends('layouts.app')

@section('title', 'Jurnal Akuntansi')

@section('content')
    <div class="page-header">
        <div>
            <h1>Jurnal Akuntansi</h1>
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
                        <th>Nasabah</th>
                        <th>Teller</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($journals as $i => $journal)
                        <tr>
                            <td>{{ $journals->firstItem() + $i }}</td>
                            <td>
                                <span style="font-weight:600;">{{ $journal->created_at->format('H:i') }}</span>
                                <span style="font-size:0.72rem; color:var(--text-muted); display:block;">{{ $journal->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="font-mono font-bold">{{ $journal->account_code }}</td>
                            <td>
                                @if($journal->position === 'Debit')
                                    <span class="badge badge-info">Debit</span>
                                @else
                                    <span class="badge badge-warning">Credit</span>
                                @endif
                            </td>
                            <td class="font-mono font-bold">Rp {{ number_format($journal->amount, 0, ',', '.') }}</td>
                            <td>{{ $journal->transaction->nasabah->student_name ?? '-' }}</td>
                            <td>{{ $journal->transaction->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted" style="padding:2.5rem 1rem;">Belum ada jurnal akuntansi yang tercatat.</td></tr>
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
