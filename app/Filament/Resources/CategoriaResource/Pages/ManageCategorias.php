<?php

namespace App\Filament\Resources\CategoriaResource\Pages;

use App\Filament\Resources\CategoriaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCategorias extends ManageRecords
{
    protected static string $resource = CategoriaResource::class;

    protected static ?string $title = 'Categorias';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Adicionar Categoria')
                ->modalHeading('Categorias'),
        ];
    }
}
