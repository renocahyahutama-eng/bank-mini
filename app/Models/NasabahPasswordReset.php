<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NasabahPasswordReset extends Model
{
    protected $table = 'nasabah_password_resets';

    protected $fillable = [
        'email',
        'otp',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}

