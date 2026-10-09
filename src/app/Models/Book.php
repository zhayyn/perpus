<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'title', 'author', 'publisher', 'isbn',
        'published_year', 'description', 'cover_image', 'language',
        'total_pages', 'price', 'location', 'barcode', 'qr_code_path', 'is_active',
    ];

    protected $casts = [
        'price'      => 'decimal:2',
        'is_active'  => 'boolean',
    ];

    // Relasi: buku milik satu kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: satu buku punya banyak eksemplar fisik
    // Analogi: "Laskar Pelangi" bisa ada 5 eksemplar di perpustakaan
    public function copies(): HasMany
    {
        return $this->hasMany(BookCopy::class);
    }

    // Hitung stok yang tersedia untuk dipinjam
    public function getAvailableStockAttribute(): int
    {
        return $this->copies()->where('status', 'tersedia')->count();
    }

    // Hitung total eksemplar
    public function getTotalStockAttribute(): int
    {
        return $this->copies()->count();
    }
}
