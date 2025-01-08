<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataFaturaResource\Pages;
use App\Filament\Resources\DataFaturaResource\RelationManagers;
use App\Models\DataFatura;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataFaturaResource extends Resource
{
    protected static ?string $model = DataFatura::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Datas Faturas';

    protected static ?string $navigationGroup = 'Faturas';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->label('Fatura'),
                Forms\Components\Select::make('cartao_id')
                    ->label('Cartão')
                    ->relationship('cartao', 'nome'),
                Forms\Components\TextInput::make('valor_fatura')
                    ->label('Valor')
                    ->prefix('R$')
                    ->inputMode('decimal')
                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),

                Forms\Components\DatePicker::make('vencimento_fatura')
                    ->label('Vencimento Fatura')
                    ->date(),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->label('Fatura')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cartao.nome')
                    ->label('Cartão')
                    ->searchable(),                   
                Tables\Columns\TextColumn::make('valor_fatura')
                    ->money('BRL')
                    ->sortable()
                    ->summarize(Sum::make()->label('Total Faturas')->money('BRL')),                   
                    
                Tables\Columns\TextColumn::make('vencimento_fatura')
                    ->date()
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
            'index' => Pages\ManageDataFaturas::route('/'),
        ];
    }
}
