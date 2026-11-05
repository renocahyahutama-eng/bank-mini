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

        // Advanced Multi-Criteria Search (ID, Tanggal, Bulan, Tahun, Nominal, Deskripsi, Tipe)
        if ($search = trim((string) $request->input('search'))) {
            $this->applySearchFilter($query, $search);
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

    /**
     * Apply smart multi-criteria search filter to transactions query.
     * Supports: Transaction ID/Code, Date (DD, DD/MM, DD/MM/YYYY), Month (Name or Number),
     * Year, Transaction Type, Amount, and Description.
     */
    protected function applySearchFilter($query, string $search): void
    {
        $cleanSearch = trim($search);
        $lower = strtolower($cleanSearch);

        // Month dictionary in Indonesian & English
        $monthMap = [
            'januari' => 1, 'jan' => 1, 'january' => 1,
            'februari' => 2, 'feb' => 2, 'february' => 2,
            'maret' => 3, 'mar' => 3, 'march' => 3,
            'april' => 4, 'apr' => 4,
            'mei' => 5, 'may' => 5,
            'juni' => 6, 'jun' => 6, 'june' => 6,
            'juli' => 7, 'jul' => 7, 'july' => 7,
            'agustus' => 8, 'ags' => 8, 'agu' => 8, 'aug' => 8, 'august' => 8,
            'september' => 9, 'sep' => 9, 'sept' => 9,
            'oktober' => 10, 'okt' => 10, 'oct' => 10, 'october' => 10,
            'november' => 11, 'nov' => 11,
            'desember' => 12, 'des' => 12, 'dec' => 12, 'december' => 12,
        ];

        $matchedMonth = null;
        foreach ($monthMap as $mName => $mNum) {
            if (str_contains($lower, $mName)) {
                $matchedMonth = $mNum;
                break;
            }
        }

        // Check if there is a day alongside month name (e.g. "8 november" or "04 nov")
        $matchedDayWithMonth = null;
        if ($matchedMonth !== null && preg_match('/(\b\d{1,2}\b)/', $lower, $dayMatch)) {
            $matchedDayWithMonth = (int) $dayMatch[1];
        }

        // Check for slash or dash date patterns like "04/11/2026", "4/11", "2026-11-04"
        $parsedDate = null;
        $parsedDayMonth = null;
        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $cleanSearch, $dmY)) {
            $parsedDate = sprintf('%04d-%02d-%02d', (int)$dmY[3], (int)$dmY[2], (int)$dmY[1]);
        } elseif (preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $cleanSearch, $Ymd)) {
            $parsedDate = sprintf('%04d-%02d-%02d', (int)$Ymd[1], (int)$Ymd[2], (int)$Ymd[3]);
        } elseif (preg_match('/^(\d{1,2})[\/\-](\d{1,2})$/', $cleanSearch, $dm)) {
            $parsedDayMonth = ['day' => (int)$dm[1], 'month' => (int)$dm[2]];
        }

        $query->where(function ($q) use (
            $cleanSearch,
            $lower,
            $matchedMonth,
            $matchedDayWithMonth,
            $parsedDate,
            $parsedDayMonth
        ) {
            // 1. Matched exact full date (e.g. 04/11/2026 or 2026-11-04)
            if ($parsedDate) {
                $q->orWhereDate('created_at', $parsedDate);
            }

            // 2. Matched day + month (e.g. "04/11" or "4/11")
            if ($parsedDayMonth) {
                $q->orWhere(function ($sub) use ($parsedDayMonth) {
                    $sub->whereDay('created_at', $parsedDayMonth['day'])
                        ->whereMonth('created_at', $parsedDayMonth['month']);
                });
            }

            // 3. Matched day + month name (e.g. "8 november" or "4 nov")
            if ($matchedMonth !== null && $matchedDayWithMonth !== null) {
                $q->orWhere(function ($sub) use ($matchedMonth, $matchedDayWithMonth) {
                    $sub->whereDay('created_at', $matchedDayWithMonth)
                        ->whereMonth('created_at', $matchedMonth);
                });
            } elseif ($matchedMonth !== null) {
                // Matched month name alone (e.g. "november", "nov", "agustus")
                $q->orWhereMonth('created_at', $matchedMonth);
            }

            // 4. Numeric Searches
            // e.g. "8" or "08" or "19" or "2026"
            $digitsOnly = preg_replace('/[^0-9]/', '', $cleanSearch);
            if ($digitsOnly !== '') {
                $num = (int) $digitsOnly;

                // Match transaction ID
                $q->orWhere('id', $num);

                // If number is 1-31, match Day of month (e.g. searching "8" or "08")
                if ($num >= 1 && $num <= 31) {
                    $q->orWhereDay('created_at', $num);
                }

                // If number is 1-12, match Month of year (e.g. searching "11" for November)
                if ($num >= 1 && $num <= 12) {
                    $q->orWhereMonth('created_at', $num);
                }

                // If number is 4 digits in year range, match Year
                if ($num >= 2000 && $num <= 2100) {
                    $q->orWhereYear('created_at', $num);
                }

                // Match amount (e.g. nominal 50000 or 10000)
                $q->orWhere('amount', 'like', "%{$digitsOnly}%");
            }

            // 5. Transaction Code Prefix (e.g. "TX-", "TX-000019", "TX19")
            if (str_starts_with($lower, 'tx')) {
                $numPart = preg_replace('/[^0-9]/', '', $lower);
                if ($numPart !== '') {
                    $q->orWhere('id', (int) $numPart);
                }
            }

            // 6. Transaction Type keywords (e.g. "setoran", "tarik", "penarikan", "deposit", "withdrawal")
            if (str_contains($lower, 'setor') || str_contains($lower, 'deposit') || str_contains($lower, 'masuk')) {
                $q->orWhere('transaction_type', 'Deposit');
            }
            if (str_contains($lower, 'tarik') || str_contains($lower, 'withdraw') || str_contains($lower, 'keluar')) {
                $q->orWhere('transaction_type', 'Withdrawal');
            }

            // 7. General description & Teller name
            $q->orWhere('description', 'like', "%{$cleanSearch}%");
            $q->orWhereHas('user', function ($uq) use ($cleanSearch) {
                $uq->where('name', 'like', "%{$cleanSearch}%");
            });
        });
    }
}
