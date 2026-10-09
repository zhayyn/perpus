<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBook extends EditRecord
{
    protected static string $resource = BookResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        $title = $this->record->title;
        return Notification::make()
            ->success()
            ->title('Data Buku Diperbarui')
            ->body("\"{$title}\" berhasil disimpan.")
            ->duration(5000);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(fn () => $this->redirect(BookResource::getUrl('index'))),
        ];
    }
}
