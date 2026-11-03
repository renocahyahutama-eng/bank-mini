<?php

namespace App\Http\Controllers\Api\Nasabah;

use App\Http\Controllers\Api\ApiController;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends ApiController
{
    /**
     * Get Paginated Transaction History (Mutasi) with Filters.
     */
    public function index(Request $request): JsonResponse
    {
        $nasabah = $this->getNasabah($request);

        if (!$nasabah || $nasabah->status !== 'Aktif') {
            return $this->errorResponse('Rekening nasabah tidak aktif.', 403);
        }

        $query = Transaction::with('user:id,name')
            ->where('nasabah_id', $nasabah->id);

        // Filter by Transaction Type (Deposit / Withdrawal)
        if ($type = $request->input('type')) {
            $formattedType = ucfirst(strtolower($type));
            if (in_array($formattedType, ['Deposit', 'Withdrawal'])) {
                $query->where('transaction_type', $formattedType);
            }
        }

        // Filter by Date Range
        if ($startDate = $request->input('start_date')) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate = $request->input('end_date')) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Search in description or ID
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $cleanSearch = preg_replace('/[^0-9]/', '', $search);
                if ($cleanSearch) {
                    $q->orWhere('id', (int) $cleanSearch);
                }
                $q->orWhere('description', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 15);
        $perPage = max(1, min($perPage, 50)); // Clamp between 1 and 50

        $paginated = $query->orderByDesc('created_at')->paginate($perPage);

        $items = collect($paginated->items())->map(function ($tx) {
            return [
                'id' => $tx->id,
                'transaction_code' => 'TX-' . str_pad($tx->id, 6, '0', STR_PAD_LEFT),
                'type' => $tx->transaction_type,
                'type_label' => $tx->transaction_type === 'Deposit' ? 'Setoran Tunai' : 'Penarikan Tunai',
                'amount' => (float) $tx->amount,
                'amount_formatted' => ($tx->transaction_type === 'Deposit' ? '+ ' : '- ') . 'Rp ' . number_format((float) $tx->amount, 0, ',', '.'),
                'balance_before' => (float) $tx->balance_before,
                'balance_before_formatted' => 'Rp ' . number_format((float) $tx->balance_before, 0, ',', '.'),
                'balance_after' => (float) $tx->balance_after,
                'balance_after_formatted' => 'Rp ' . number_format((float) $tx->balance_after, 0, ',', '.'),
                'description' => $tx->description ?: ($tx->transaction_type === 'Deposit' ? 'Setoran tabungan via Teller' : 'Penarikan tabungan via Teller'),
                'teller_name' => $tx->user->name ?? 'Petugas Teller',
                'created_at' => $tx->created_at->format('Y-m-d H:i:s'),
                'created_at_formatted' => $tx->created_at->translatedFormat('d M Y, H:i') . ' WIB',
            ];
        });

        return $this->successResponse([
            'transactions' => $items,
            'pagination' => [
                'current_page' => $paginated->currentPage(),
                'last_page'    => $paginated->lastPage(),
                'per_page'     => $paginated->perPage(),
                'total'        => $paginated->total(),
                'has_more'     => $paginated->hasMorePages(),
            ],
            'filters' => [
                'type' => $request->input('type'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'search' => $request->input('search'),
            ],
        ], 'Riwayat transaksi berhasil dimuat.');
    }

    /**
     * Get Specific Transaction Detail (Struk Digital).
     */
    public function show(Request $request, int|string $id): JsonResponse
    {
        $nasabah = $this->getNasabah($request);

        if (!$nasabah || $nasabah->status !== 'Aktif') {
            return $this->errorResponse('Rekening nasabah tidak aktif.', 403);
        }

        // Strip prefix if client passes "TX-000001"
        $numericId = (int) preg_replace('/[^0-9]/', '', (string) $id);

        $tx = Transaction::with('user:id,name')
            ->where('id', $numericId)
            ->where('nasabah_id', $nasabah->id)
            ->first();

        if (!$tx) {
            return $this->errorResponse('Transaksi tidak ditemukan atau bukan milik rekening ini.', 404);
        }

        return $this->successResponse([
            'transaction' => [
                'id' => $tx->id,
                'transaction_code' => 'TX-' . str_pad($tx->id, 6, '0', STR_PAD_LEFT),
                'receipt_number' => 'STRUK-' . date('Ymd', strtotime($tx->created_at)) . '-' . str_pad($tx->id, 6, '0', STR_PAD_LEFT),
                'type' => $tx->transaction_type,
                'type_label' => $tx->transaction_type === 'Deposit' ? 'Setoran Tunai' : 'Penarikan Tunai',
                'amount' => (float) $tx->amount,
                'amount_formatted' => 'Rp ' . number_format((float) $tx->amount, 0, ',', '.'),
                'balance_before' => (float) $tx->balance_before,
                'balance_before_formatted' => 'Rp ' . number_format((float) $tx->balance_before, 0, ',', '.'),
                'balance_after' => (float) $tx->balance_after,
                'balance_after_formatted' => 'Rp ' . number_format((float) $tx->balance_after, 0, ',', '.'),
                'description' => $tx->description ?: ($tx->transaction_type === 'Deposit' ? 'Setoran tabungan via Teller' : 'Penarikan tabungan via Teller'),
                'teller_name' => $tx->user->name ?? 'Petugas Teller',
                'timestamp' => $tx->created_at->format('Y-m-d H:i:s'),
                'date_formatted' => $tx->created_at->translatedFormat('d F Y'),
                'time_formatted' => $tx->created_at->format('H:i:s') . ' WIB',
            ],
            'nasabah' => [
                'account_number' => $nasabah->account_number,
                'student_name' => $nasabah->student_name,
                'student_number' => $nasabah->student_number,
                'class' => $nasabah->class,
                'jurusan' => $nasabah->jurusan,
            ],
            'institution' => [
                'name' => 'Bank Mini SMKN 1 Bantul',
                'address' => 'Jl. Sabdodadi, Bantul, D.I. Yogyakarta',
                'notice' => 'Struk digital ini sah dan tersimpan secara resmi pada sistem E-Teller Bank Mini SMKN 1 Bantul.',
            ],
        ], 'Detail transaksi berhasil dimuat.');
    }
}
