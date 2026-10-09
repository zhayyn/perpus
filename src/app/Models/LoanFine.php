<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanFine extends Model
{
    protected $fillable = [
        'book_loan_id', 'overdue_days', 'fine_per_day',
        'total_fine', 'payment_status', 'paid_at', 'paid_by', 'notes',
    ];

    protected $casts = [
        'fine_per_day'   => 'decimal:2',
        'total_fine'     => 'decimal:2',
        'paid_at'        => 'datetime',
    ];

    // Relasi ke peminjaman
    public function loan(): BelongsTo
    {
        return $this->belongsTo(BookLoan::class, 'book_loan_id');
    }

    // Petugas yang menerima pembayaran
    public function paidByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'sudah_bayar';
    }
}
