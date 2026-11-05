<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionAuthorization extends Model
{
    protected $table = 'transaction_authorizations';

    protected $fillable = [
        'nasabah_id',
        'user_id',
        'transaction_type',
        'amount',
        'description',
        'status',
        'transaction_id',
        'verified_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'verified_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function teller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending' && ($this->expires_at === null || $this->expires_at->isFuture());
    }
}

