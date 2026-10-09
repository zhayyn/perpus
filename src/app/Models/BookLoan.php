<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class BookLoan extends Model
{
    protected $fillable = [
        'loan_code', 'member_id', 'book_copy_id', 'user_id',
        'loan_date', 'due_date', 'return_date', 'status', 'notes',
    ];

    protected $casts = [
        'loan_date'   => 'date',
        'due_date'    => 'date',
        'return_date' => 'date',
    ];

    /**
     * Boot: auto-generate loan_code dan set user_id petugas yang login
     * Ibarat mesin cetak struk otomatis saat kasir menekan tombol simpan
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (BookLoan $loan) {
            // Generate kode unik format: LN-YYYYMMDD-XXXXX
            $loan->loan_code = 'LN-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

            // Set user_id petugas yang sedang login (jika belum diset)
            if (empty($loan->user_id) && auth()->check()) {
                $loan->user_id = auth()->id();
            }

            // Jika loan_date kosong, pakai hari ini
            if (empty($loan->loan_date)) {
                $loan->loan_date = now()->toDateString();
            }
        });
    }

    // Relasi: peminjaman dilakukan oleh satu anggota
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    // Relasi: peminjaman untuk satu eksemplar buku
    public function bookCopy(): BelongsTo
    {
        return $this->belongsTo(BookCopy::class);
    }

    // Relasi: dilayani oleh satu petugas
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: satu peminjaman bisa menghasilkan satu denda
    public function fine(): HasOne
    {
        return $this->hasOne(LoanFine::class);
    }

    // Hitung jumlah hari terlambat
    public function getOverdueDaysAttribute(): int
    {
        $compareDate = $this->return_date ?? Carbon::today();
        if ($compareDate->gt($this->due_date)) {
            return $this->due_date->diffInDays($compareDate);
        }
        return 0;
    }

    // Apakah sudah terlambat?
    public function isOverdue(): bool
    {
        return $this->status === 'dipinjam' && Carbon::today()->gt($this->due_date);
    }
}
