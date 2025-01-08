<?php

namespace App\Filament\Resources\DataFaturaResource\Pages;

use App\Filament\Resources\DataFaturaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDataFaturas extends ManageRecords
{
    protected static string $resource = DataFaturaResource::class;

    protected static ?string $title = 'Datas Faturas';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nova Fatura')
                ->modalHeading('Faturas'),
        ];
    }
}
