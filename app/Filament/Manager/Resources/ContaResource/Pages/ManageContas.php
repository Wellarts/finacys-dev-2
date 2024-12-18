<?php

namespace App\Filament\Manager\Resources\ContaResource\Pages;

use App\Filament\Manager\Resources\ContaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageContas extends ManageRecords
{
    protected static string $resource = ContaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
