<?php

namespace App\Filament\Resources\LoanFineResource\Pages;

use App\Filament\Resources\LoanFineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoanFines extends ListRecords
{
    protected static string $resource = LoanFineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
