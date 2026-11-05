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
            'amount' => [
                'required',
                'numeric',
                'min:1000',
                function ($attribute, $value, $fail) {
                    if (fmod((float) $value, 1000) != 0) {
                        $fail('Nominal setoran harus dalam kelipatan ribuan penuh (contoh: Rp 1.000, Rp 2.000, Rp 5.000), tidak boleh menggunakan pecahan seperti 1 rupiah atau 500 rupiah.');
                    }
                },
            ],
            'description' => 'nullable|string|max:255',
        ], [
            'amount.min' => 'Nominal setoran minimal Rp 1.000 (tidak boleh 1 rupiah atau 500 rupiah).',
            'amount.numeric' => 'Nominal setoran harus berupa angka.',
            'amount.required' => 'Nominal setoran wajib diisi.',
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
