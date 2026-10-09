<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Book;
use App\Models\Member;
use App\Models\BookLoan;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Judul Buku', Book::count())
                ->description('Jumlah seluruh judul buku')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Total Anggota Aktif', Member::where('is_active', true)->count())
                ->description('Anggota perpustakaan yang aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([4, 12, 8, 14, 5, 18, 12]),

            Stat::make('Peminjaman Aktif', BookLoan::where('status', 'dipinjam')->count())
                ->description('Buku yang sedang dipinjam')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning')
                ->chart([1, 4, 2, 8, 5, 2, 4]),
        ];
    }
}
