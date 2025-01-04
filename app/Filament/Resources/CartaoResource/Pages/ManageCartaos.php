<?php

namespace App\Filament\Resources\CartaoResource\Pages;

use App\Filament\Resources\CartaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCartaos extends ManageRecords
{
    protected static string $resource = CartaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
