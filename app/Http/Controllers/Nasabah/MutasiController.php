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

        $transactions = $query->orderByDesc('created_at')->paginate(20);

        return view('nasabah.mutasi.index', compact('nasabah', 'transactions'));
    }
}
