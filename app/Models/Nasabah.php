<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
    protected $table = 'nasabahs';

    protected $fillable = [
        'account_number',
        'student_number',
        'student_name',
        'class',
        'jurusan',
        'gender',
        'phone_number',
        'security_pin',
        'balance',
        'status',
    ];

    protected $hidden = [
        'security_pin',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
        ];
    }

    // ── Relationships ──

    public function customerAccount()
    {
        return $this->hasOne(CustomerAccount::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // ── Helpers ──

    public function isActive(): bool
    {
        return $this->status === 'Aktif';
    }

    /**
     * Daftar jurusan yang tersedia.
     */
    public static function getJurusanList(): array
    {
        return [
            'RPL' => 'RPL - Rekayasa Perangkat Lunak',
            'TKJ' => 'TKJ - Teknik Komputer & Jaringan',
            'DKV' => 'DKV - Desain Komunikasi Visual',
            'MM' => 'MM - Multimedia',
            'OTKP' => 'OTKP - Otomatisasi Tata Kelola Perkantoran',
            'AKL' => 'AKL - Akuntansi & Keuangan Lembaga',
            'BDP' => 'BDP - Bisnis Daring & Pemasaran',
            'TB' => 'TB - Tata Busana',
            'TSM' => 'TSM - Teknik Sepeda Motor',
        ];
    }

    /**
     * Generate a new account number: {JURUSAN}-{year}-{sequential}
     * Contoh: RPL-2026-0001
     */
    public static function generateAccountNumber(string $jurusan = 'RPL'): string
    {
        $jurusan = strtoupper(trim($jurusan ?: 'RPL'));
        $year = date('Y');
        $prefix = $jurusan . '-' . $year . '-';

        $lastNasabah = self::where('account_number', 'like', $prefix . '%')
            ->orderByDesc('account_number')
            ->first();

        if ($lastNasabah) {
            $lastNumber = (int) substr($lastNasabah->account_number, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
