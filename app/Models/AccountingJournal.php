<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountingJournal extends Model
{
    protected $table = 'accounting_journals';

    protected $fillable = [
        'transaction_id',
        'account_code',
        'position',
        'amount',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    // ── Relationships ──

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // ── Account Codes ──

    const KAS = '101';
    const TABUNGAN = '201';

    /**
     * Create double-entry journal for a deposit transaction.
     * Deposit: Debit Kas 101, Credit Tabungan 201
     */
    public static function createDepositEntries(Transaction $transaction): void
    {
        self::create([
            'transaction_id' => $transaction->id,
            'account_code' => self::KAS,
            'position' => 'Debit',
            'amount' => $transaction->amount,
        ]);

        self::create([
            'transaction_id' => $transaction->id,
            'account_code' => self::TABUNGAN,
            'position' => 'Credit',
            'amount' => $transaction->amount,
        ]);
    }

    /**
     * Create double-entry journal for a withdrawal transaction.
     * Withdrawal: Debit Tabungan 201, Credit Kas 101
     */
    public static function createWithdrawalEntries(Transaction $transaction): void
    {
        self::create([
            'transaction_id' => $transaction->id,
            'account_code' => self::TABUNGAN,
            'position' => 'Debit',
            'amount' => $transaction->amount,
        ]);

        self::create([
            'transaction_id' => $transaction->id,
            'account_code' => self::KAS,
            'position' => 'Credit',
            'amount' => $transaction->amount,
        ]);
    }
}
