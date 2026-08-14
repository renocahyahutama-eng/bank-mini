<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['nasabah', 'user']);

        if ($type = $request->input('type')) {
            $query->where('transaction_type', $type);
        }

        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }

        $transactions = $query->orderByDesc('created_at')->paginate(20);

        return view('supervisor.transactions.index', compact('transactions'));
    }
}
