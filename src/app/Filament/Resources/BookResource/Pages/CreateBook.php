<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateBook extends CreateRecord
{
    protected static string $resource = BookResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $title = $this->record->title;
        return Notification::make()
            ->success()
            ->title('Buku Berhasil Ditambahkan')
            ->body("\"{$title}\" sudah masuk ke koleksi perpustakaan.")
            ->duration(5000);
    }
}
