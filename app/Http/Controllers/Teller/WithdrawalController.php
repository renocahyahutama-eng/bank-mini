<?php

namespace App\Http\Controllers\Teller;

use App\Http\Controllers\Controller;
use App\Models\AccountingJournal;
use App\Models\Nasabah;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WithdrawalController extends Controller
{
    const MINIMUM_BALANCE = 10000; // Rp 10.000

    public function create(Request $request)
    {
        $nasabah = null;
        if ($request->has('nasabah_id')) {
            $nasabah = Nasabah::where('id', $request->nasabah_id)
                ->where('status', 'Aktif')
                ->first();
        }

        $nasabahs = Nasabah::where('status', 'Aktif')->orderBy('student_name')->get();

        return view('teller.withdrawal.create', compact('nasabah', 'nasabahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nasabah_id' => 'required|exists:nasabahs,id',
            'amount' => 'required|numeric|min:1',
            'security_pin' => 'required|digits:6',
            'description' => 'nullable|string|max:255',
        ]);

        $nasabah = Nasabah::findOrFail($request->nasabah_id);

        if ($nasabah->status !== 'Aktif') {
            return back()->with('error', 'Rekening nasabah tidak aktif.')->withInput();
        }

        // Verify security PIN
        if (!Hash::check($request->security_pin, $nasabah->security_pin)) {
            return back()->with('error', 'PIN keamanan salah. Penarikan ditolak.')->withInput();
        }

        $amount = $request->amount;
        $balanceBefore = $nasabah->balance;
        $balanceAfter = $balanceBefore - $amount;

        // Check minimum balance (saldo mengendap)
        if ($balanceAfter < self::MINIMUM_BALANCE) {
            $maxWithdrawal = $balanceBefore - self::MINIMUM_BALANCE;
            return back()->with('error',
                "Saldo tidak mencukupi. Saldo saat ini: Rp " . number_format($balanceBefore, 0, ',', '.') .
                ". Saldo mengendap minimum: Rp " . number_format(self::MINIMUM_BALANCE, 0, ',', '.') .
                ". Penarikan maksimal: Rp " . number_format(max(0, $maxWithdrawal), 0, ',', '.')
            )->withInput();
        }

        DB::beginTransaction();
        try {
            // Create transaction
            $transaction = Transaction::create([
                'nasabah_id' => $nasabah->id,
                'user_id' => auth()->id(),
                'transaction_type' => 'Withdrawal',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $request->description,
            ]);

            // Update nasabah balance
            $nasabah->update(['balance' => $balanceAfter]);

            // Create double-entry journal (reverse of deposit)
            AccountingJournal::createWithdrawalEntries($transaction);

            DB::commit();

            return redirect()->route('teller.transactions.index')
                ->with('success', "Penarikan berhasil! Rp " . number_format($amount, 0, ',', '.') . " dari rekening {$nasabah->account_number} ({$nasabah->student_name})");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses penarikan: ' . $e->getMessage())->withInput();
        }
    }
}
