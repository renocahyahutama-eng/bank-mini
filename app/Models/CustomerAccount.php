<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class CustomerAccount extends Authenticatable
{
    use HasApiTokens;

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
