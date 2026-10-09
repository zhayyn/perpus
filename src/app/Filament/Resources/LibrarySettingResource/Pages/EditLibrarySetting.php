<?php

namespace App\Filament\Resources\LibrarySettingResource\Pages;

use App\Filament\Resources\LibrarySettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLibrarySetting extends EditRecord
{
    protected static string $resource = LibrarySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
