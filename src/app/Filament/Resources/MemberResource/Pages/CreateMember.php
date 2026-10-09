<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $name = $this->record->name;
        $code = $this->record->member_code;
        return Notification::make()
            ->success()
            ->title('Anggota Baru Terdaftar')
            ->body("Selamat datang, {$name}! Kode: {$code}")
            ->duration(5000);
    }
}
