<?php

namespace App\Filament\Resources\CartaoResource\Pages;

use App\Filament\Resources\CartaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCartaos extends ManageRecords
{
    protected static string $resource = CartaoResource::class;

    protected static ?string $title = 'Cartões';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Adicionar Cartão')
                ->modalHeading('Cartões')
        ];
    }
}
