<?php

namespace App\Filament\Resources\BookLoanResource\Pages;

use App\Filament\Resources\BookLoanResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateBookLoan extends CreateRecord
{
    protected static string $resource = BookLoanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $code = $this->record->loan_code;
        return Notification::make()
            ->success()
            ->title('Peminjaman Berhasil Dicatat')
            ->body("Kode pinjam: {$code}")
            ->duration(5000);
    }
}
