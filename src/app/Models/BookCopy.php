<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookCopy extends Model
{
    protected $fillable = [
        'book_id', 'copy_code', 'condition', 'status', 'acquired_at', 'notes',
    ];

    protected $casts = [
        'acquired_at' => 'date',
    ];

    // Relasi: eksemplar milik satu buku
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    // Relasi: satu eksemplar bisa punya banyak riwayat peminjaman
    public function loans(): HasMany
    {
        return $this->hasMany(BookLoan::class);
    }

    // Pinjaman aktif saat ini
    public function activeLoan()
    {
        return $this->loans()->where('status', 'dipinjam')->latest()->first();
    }

    public function isAvailable(): bool
    {
        return $this->status === 'tersedia';
    }
}
