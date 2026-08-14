<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // ── Relationships ──

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function dailyReportsAsTeller()
    {
        return $this->hasMany(DailyReport::class, 'teller_id');
    }

    public function dailyReportsAsSupervisor()
    {
        return $this->hasMany(DailyReport::class, 'supervisor_id');
    }

    // ── Helpers ──

    public function isAdmin(): bool
    {
        return $this->role === 'Administrator';
    }

    public function isSupervisor(): bool
    {
        return $this->role === 'Supervisor';
    }

    public function isTeller(): bool
    {
        return $this->role === 'Teller';
    }

    public function isActive(): bool
    {
        return $this->status === 'Aktif';
    }
}
