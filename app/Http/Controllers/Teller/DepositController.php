<?php

namespace App\Http\Controllers\Teller;

use App\Http\Controllers\Controller;
use App\Models\AccountingJournal;
use App\Models\Nasabah;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    public function create(Request $request)
    {
        $nasabah = null;
        if ($request->has('nasabah_id')) {
            $nasabah = Nasabah::where('id', $request->nasabah_id)
                ->where('status', 'Aktif')
                ->first();
        }

        $nasabahs = Nasabah::where('status', 'Aktif')->orderBy('student_name')->get();

        return view('teller.deposit.create', compact('nasabah', 'nasabahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nasabah_id' => 'required|exists:nasabahs,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $nasabah = Nasabah::findOrFail($request->nasabah_id);

        if ($nasabah->status !== 'Aktif') {
            return back()->with('error', 'Rekening nasabah tidak aktif.')->withInput();
        }

        DB::beginTransaction();
        try {
            $balanceBefore = $nasabah->balance;
            $amount = $request->amount;
            $balanceAfter = $balanceBefore + $amount;

            // Create transaction
            $transaction = Transaction::create([
                'nasabah_id' => $nasabah->id,
                'user_id' => auth()->id(),
                'transaction_type' => 'Deposit',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $request->description,
            ]);

            // Update nasabah balance
            $nasabah->update(['balance' => $balanceAfter]);

            // Create double-entry journal
            AccountingJournal::createDepositEntries($transaction);

            DB::commit();

            return redirect()->route('teller.transactions.index')
                ->with('success', "Setoran berhasil! Rp " . number_format($amount, 0, ',', '.') . " ke rekening {$nasabah->account_number} ({$nasabah->student_name})");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses setoran: ' . $e->getMessage())->withInput();
        }
    }
}
