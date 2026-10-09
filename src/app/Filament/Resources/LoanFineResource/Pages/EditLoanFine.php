<?php

namespace App\Filament\Resources\LoanFineResource\Pages;

use App\Filament\Resources\LoanFineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoanFine extends EditRecord
{
    protected static string $resource = LoanFineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
