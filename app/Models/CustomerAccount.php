<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomerAccount extends Authenticatable
{
    protected $table = 'customer_accounts';

    protected $fillable = [
        'nasabah_id',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // ── Relationships ──

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }
}
