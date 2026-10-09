<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $name = $this->record->name;
        return Notification::make()
            ->success()
            ->title('Kategori Berhasil Dibuat')
            ->body("Kategori \"{$name}\" sudah ditambahkan.")
            ->duration(5000);
    }
}
