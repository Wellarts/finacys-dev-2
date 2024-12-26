<?php

namespace App\Filament\Resources\DespesaResource\Pages;

use App\Filament\Resources\DespesaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDespesas extends ManageRecords
{
    protected static string $resource = DespesaResource::class;

    protected static ?string $title = 'Despesas';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nova')
                ->modalHeading('Despesas'),
        ];
    }
}
