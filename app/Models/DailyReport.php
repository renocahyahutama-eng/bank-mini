<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $table = 'daily_reports';

    protected $fillable = [
        'report_date',
        'teller_id',
        'supervisor_id',
        'opening_balance',
        'total_deposit',
        'total_withdrawal',
        'closing_balance',
        'status',
        'submitted_at',
        'approved_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'opening_balance' => 'decimal:2',
            'total_deposit' => 'decimal:2',
            'total_withdrawal' => 'decimal:2',
            'closing_balance' => 'decimal:2',
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    // ── Relationships ──

    public function teller()
    {
        return $this->belongsTo(User::class, 'teller_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    // ── Helpers ──

    public function isDraft(): bool
    {
        return $this->status === 'Draft';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'Submitted';
    }

    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }
}
