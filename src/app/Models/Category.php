<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'color', 'icon', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Auto-generate slug dari name
    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn ($c) => $c->slug ??= Str::slug($c->name));
    }

    // Relasi: satu kategori punya banyak buku
    // Analogi: satu rak "Fiksi" bisa menampung banyak buku fiksi
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function activeBooksCount(): int
    {
        return $this->books()->where('is_active', true)->count();
    }
}
