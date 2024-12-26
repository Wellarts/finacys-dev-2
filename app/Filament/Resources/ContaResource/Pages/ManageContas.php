<?php

namespace App\Filament\Resources\ContaResource\Pages;

use App\Filament\Resources\ContaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageContas extends ManageRecords
{
    protected static string $resource = ContaResource::class;

    protected static ?string $title = 'Contas';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nova')
                ->modalHeading('Contas'),
        ];
    }
}
