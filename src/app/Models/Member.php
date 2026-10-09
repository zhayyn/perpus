<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_code', 'name', 'email', 'phone', 'gender', 'address',
        'birth_date', 'type', 'institution', 'photo', 'qr_code_path',
        'membership_start', 'membership_end', 'is_active', 'notes',
    ];

    protected $casts = [
        'birth_date'       => 'date',
        'membership_start' => 'date',
        'membership_end'   => 'date',
        'is_active'        => 'boolean',
    ];

    // Relasi: satu anggota bisa punya banyak riwayat pinjam
    public function loans(): HasMany
    {
        return $this->hasMany(BookLoan::class);
    }

    // Pinjaman yang sedang aktif
    public function activeLoans(): HasMany
    {
        return $this->loans()->whereIn('status', ['dipinjam', 'terlambat']);
    }

    // Cek apakah keanggotaan masih aktif
    public function isMembershipActive(): bool
    {
        if (! $this->is_active) {
            return false;
        }
        if ($this->membership_end && $this->membership_end->isPast()) {
            return false;
        }
        return true;
    }

    // Total denda belum terbayar
    public function getUnpaidFinesAttribute(): float
    {
        return $this->loans()
            ->with('fine')
            ->get()
            ->sum(fn($loan) => $loan->fine?->total_fine ?? 0);
    }
}
