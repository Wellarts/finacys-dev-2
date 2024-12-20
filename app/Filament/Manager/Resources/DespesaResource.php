<?php

namespace App\Filament\Manager\Resources;

use App\Filament\Manager\Resources\DespesaResource\Pages;
use App\Filament\Manager\Resources\DespesaResource\RelationManagers;
use App\Models\Despesa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DespesaResource extends Resource
{
    protected static ?string $model = Despesa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('valor_total')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('pago')
                    ->required(),
                Forms\Components\DatePicker::make('data_vencimento')
                    ->required(),
                Forms\Components\DatePicker::make('data_pagamento')
                    ->required(),
                Forms\Components\TextInput::make('descricao')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('categoria_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('conta_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('anexo')
                    ->maxLength(255),
                Forms\Components\Toggle::make('ignorado'),
                Forms\Components\Toggle::make('parcelado')
                    ->required(),
                Forms\Components\TextInput::make('forma_parcelamento')
                    ->maxLength(255),
                Forms\Components\TextInput::make('qtd_parcela')
                    ->numeric(),
                Forms\Components\TextInput::make('valor_parcela')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('valor_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('pago')
                    ->boolean(),
                Tables\Columns\TextColumn::make('data_vencimento')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_pagamento')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descricao')
                    ->searchable(),
                Tables\Columns\TextColumn::make('categoria_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('conta_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('anexo')
                    ->searchable(),
                Tables\Columns\IconColumn::make('ignorado')
                    ->boolean(),
                Tables\Columns\IconColumn::make('parcelado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('forma_parcelamento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qtd_parcela')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valor_parcela')
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
            'index' => Pages\ManageDespesas::route('/'),
        ];
    }
}
