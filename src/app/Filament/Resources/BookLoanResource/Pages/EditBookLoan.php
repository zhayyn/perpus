<?php

namespace App\Filament\Resources\BookLoanResource\Pages;

use App\Filament\Resources\BookLoanResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBookLoan extends EditRecord
{
    protected static string $resource = BookLoanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        $code   = $this->record->loan_code;
        $status = $this->record->status;
        return Notification::make()
            ->success()
            ->title('Data Peminjaman Diperbarui')
            ->body("Kode: {$code} | Status: {$status}")
            ->duration(5000);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(fn () => $this->redirect(BookLoanResource::getUrl('index'))),
        ];
    }
}
