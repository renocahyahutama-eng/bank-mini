<?php

namespace App\Http\Controllers\Teller;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['nasabah', 'user'])
            ->where('user_id', auth()->id());

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('nasabah', function ($nq) use ($search) {
                    $nq->where('student_name', 'like', "%{$search}%")
                       ->orWhere('account_number', 'like', "%{$search}%");
                })->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($type = $request->input('type')) {
            $query->where('transaction_type', $type);
        }

        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }

        $transactions = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('teller.transactions.index', compact('transactions', 'search'));
    }

    /**
     * Tampilan struk digital transaksi yang dapat dicetak/disimpan sebagai PDF.
     */
    public function receipt(Transaction $transaction)
    {
        // Pastikan relasi ter-load
        $transaction->load(['nasabah', 'user']);

        return view('teller.transactions.receipt', compact('transaction'));
    }
}
