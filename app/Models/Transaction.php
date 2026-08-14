<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'nasabah_id',
        'user_id',
        'transaction_type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance_before' => 'decimal:2',
            'balance_after' => 'decimal:2',
        ];
    }

    // ── Relationships ──

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function journals()
    {
        return $this->hasMany(AccountingJournal::class);
    }

    // ── Helpers ──

    public function isDeposit(): bool
    {
        return $this->transaction_type === 'Deposit';
    }

    public function isWithdrawal(): bool
    {
        return $this->transaction_type === 'Withdrawal';
    }
}
