<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class MutasiController extends Controller
{
    public function index(Request $request)
    {
        $customerAccount = auth()->guard('nasabah')->user();
        $nasabah = $customerAccount->nasabah;

        $query = Transaction::where('nasabah_id', $nasabah->id);

        if ($type = $request->input('type')) {
            $query->where('transaction_type', $type);
        }

        if ($search = trim((string) $request->input('search'))) {
            $cleanSearch = trim($search);
            $lower = strtolower($cleanSearch);

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

            $matchedDayWithMonth = null;
            if ($matchedMonth !== null && preg_match('/(\b\d{1,2}\b)/', $lower, $dayMatch)) {
                $matchedDayWithMonth = (int) $dayMatch[1];
            }

            $query->where(function ($q) use ($cleanSearch, $lower, $matchedMonth, $matchedDayWithMonth) {
                if ($matchedMonth !== null && $matchedDayWithMonth !== null) {
                    $q->orWhere(function ($sub) use ($matchedMonth, $matchedDayWithMonth) {
                        $sub->whereDay('created_at', $matchedDayWithMonth)
                            ->whereMonth('created_at', $matchedMonth);
                    });
                } elseif ($matchedMonth !== null) {
                    $q->orWhereMonth('created_at', $matchedMonth);
                }

                $digitsOnly = preg_replace('/[^0-9]/', '', $cleanSearch);
                if ($digitsOnly !== '') {
                    $num = (int) $digitsOnly;
                    $q->orWhere('id', $num);
                    if ($num >= 1 && $num <= 31) {
                        $q->orWhereDay('created_at', $num);
                    }
                    if ($num >= 1 && $num <= 12) {
                        $q->orWhereMonth('created_at', $num);
                    }
                    if ($num >= 2000 && $num <= 2100) {
                        $q->orWhereYear('created_at', $num);
                    }
                    $q->orWhere('amount', 'like', "%{$digitsOnly}%");
                }

                if (str_starts_with($lower, 'tx')) {
                    $numPart = preg_replace('/[^0-9]/', '', $lower);
                    if ($numPart !== '') {
                        $q->orWhere('id', (int) $numPart);
                    }
                }

                $q->orWhere('description', 'like', "%{$cleanSearch}%");
            });
        }

        $transactions = $query->orderByDesc('created_at')->paginate(20);

        return view('nasabah.mutasi.index', compact('nasabah', 'transactions'));
    }
}
