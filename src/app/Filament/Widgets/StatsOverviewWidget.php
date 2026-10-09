<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\BookLoan;
use App\Models\Member;
use App\Models\LoanFine;
use App\Models\LibrarySetting;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $activeLoans    = BookLoan::where('status', 'dipinjam')->count();
        $overdueLoans   = BookLoan::where('status', 'terlambat')
                            ->orWhere(function ($q) {
                                $q->where('status', 'dipinjam')
                                  ->where('due_date', '<', today());
                            })->count();
                            
        // 1. Ambil denda dari tabel loan_fines yang belum dibayar (Buku sudah dikembalikan tapi belum bayar)
        $unpaidFines = LoanFine::where('payment_status', 'belum_bayar')->sum('total_fine');

        // 2. Kalkulasi denda berjalan (Buku masih dibawa dan terlambat)
        $finePerDay = LibrarySetting::get('fine_per_day', 1000);
        $ongoingFines = 0;
        
        $overdueActiveLoans = BookLoan::whereIn('status', ['dipinjam', 'terlambat'])
            ->where('due_date', '<', today())
            ->get();
            
        foreach ($overdueActiveLoans as $loan) {
            $daysLate = $loan->due_date->diffInDays(today());
            $ongoingFines += ($daysLate * $finePerDay);
        }

        $totalUnpaidFines = $unpaidFines + $ongoingFines;

        return [
            Stat::make('Total Buku', Book::where('is_active', true)->count())
                ->description('Judul buku aktif')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success')
                ->chart([3, 5, 8, 12, 15, 18, Book::where('is_active', true)->count()]),

            Stat::make('Total Anggota', Member::where('is_active', true)->count())
                ->description('Anggota aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([5, 8, 12, 15, 18, Member::where('is_active', true)->count()]),

            Stat::make('Sedang Dipinjam', $activeLoans)
                ->description("{$overdueLoans} terlambat dikembalikan")
                ->descriptionIcon('heroicon-m-clock')
                ->color($overdueLoans > 0 ? 'warning' : 'success'),

            Stat::make('Estimasi Denda Terkumpul', 'Rp ' . number_format($totalUnpaidFines, 0, ',', '.'))
                ->description('Total denda (berjalan + belum dibayar)')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color($totalUnpaidFines > 0 ? 'danger' : 'success'),
        ];
    }
}
