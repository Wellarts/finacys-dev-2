<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConfigResource\Pages;
use App\Filament\Resources\ConfigResource\RelationManagers;
use App\Models\Config;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConfigResource extends Resource
{
    protected static ?string $model = Config::class;

    protected static ?string $navigationIcon = 'heroicon-s-cog-6-tooth';

    protected static ?string $navigationLabel = 'Configurações';

    protected static ?string $navigationGroup = 'Parâmetros';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('despesa_conta_id')
                    ->label('Conta de Despesa Padrão')
                    ->required()
                    ->relationship(
                        name: 'contaDespesa',
                        titleAttribute: 'descricao',
                        modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                    ),
                Forms\Components\Select::make('despesa_categoria_id')
                    ->label('Categoria de Despesa Padrão')
                    ->required()
                    ->relationship(
                        name: 'categoriaDespesa',
                        titleAttribute: 'nome',
                        modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                    ),
                // Forms\Components\Select::make('despesa_sub_categoria_id')
                //     ->required()
                //     ->relationship(
                //         name: 'sub_categoria',
                //         titleAttribute: 'nome',
                //         modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                //     ),
                Forms\Components\Select::make('receita_conta_id')
                    ->label('Conta de Receita Padrão')
                    ->required()
                    ->relationship(
                        name: 'contaReceita',
                        titleAttribute: 'descricao',
                        modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                    ),
                Forms\Components\Select::make('receita_categoria_id')
                    ->label('Categoria de Receita Padrão')
                    ->required()
                    ->relationship(
                        name: 'categoriaReceita',
                        titleAttribute: 'nome',
                        modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                    ),
                Forms\Components\Select::make('cartao_id')
                    ->required()
                    ->relationship(
                        name: 'cartao',
                        titleAttribute: 'nome',
                        modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contaDespesa.descricao')
                    ->label('Conta de Despesa')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('categoriaDespesa.nome')
                    ->label('Categoria de Despesa')
                    ->numeric()
                    ->sortable(),                
                Tables\Columns\TextColumn::make('contaReceita.descricao')
                    ->label('Conta de Receita')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('categoriaReceita.nome')
                    ->label('Categoria de Receita')
                    ->numeric()
                    ->sortable(),                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ManageConfigs::route('/'),
        ];
    }
}
