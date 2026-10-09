<?php

namespace App\Filament\Resources\LibrarySettingResource\Pages;

use App\Filament\Resources\LibrarySettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLibrarySettings extends ListRecords
{
    protected static string $resource = LibrarySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
