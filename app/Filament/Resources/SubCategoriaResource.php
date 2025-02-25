<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubCategoriaResource\Pages;
use App\Filament\Resources\SubCategoriaResource\RelationManagers;
use App\Models\SubCategoria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Facades\Filament;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Enums\Alignment;

class SubCategoriaResource extends Resource
{
    protected static ?string $model = SubCategoria::class;

    protected static ?string $navigationIcon = 'heroicon-s-rectangle-group';

    protected static ?string $navigationLabel = 'SubCategorias';

    protected static ?string $navigationGroup = 'Cadastros';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('categoria_id')
                    ->required()
                    ->relationship(
                        name: 'categoria',
                        titleAttribute: 'nome',
                        modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                    ),
                Forms\Components\TextInput::make('nome')
                    ->label('SubCategoria')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('categoria.nome')
            ->columns([
                Tables\Columns\TextColumn::make('categoria.nome')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('categoria.cor')
                    ->label('Cor')
                    ->sortable()
                    ->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('nome')
                    ->sortable()
                    ->label('SubCategoria')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('categoria_id')
                    ->label('Categoria')
                    ->relationship('categoria', 'nome'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSubCategorias::route('/'),
        ];
    }
}
