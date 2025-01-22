<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataFaturaResource\Pages;
use App\Filament\Resources\DataFaturaResource\RelationManagers;
use App\Models\DataFatura;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
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
                    ->disabled()
                    ->label('Fatura'),
                Forms\Components\Select::make('cartao_id')
                    ->disabled()
                    ->label('Cartão')
                    ->relationship('cartao', 'nome'),
                Forms\Components\TextInput::make('valor_fatura')
                    ->disabled()
                    ->label('Valor')
                    ->prefix('R$')
                    ->inputMode('decimal')
                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),
                Forms\Components\DatePicker::make('vencimento_fatura')
                    ->disabled()
                    ->label('Vencimento Fatura')
                    ->date(),
                Forms\Components\ToggleButtons::make('pago')
                    ->label('Pago?')
                    //   ->extraInputAttributes(['tabindex' => 6])
                    ->live()
                    ->default(false)
                    ->afterStateUpdated(function (Get $get, callable $set) {
                        $set('valor_pago', $get('valor_fatura'));
                    })
                    ->boolean()
                    ->grouped(),
                Forms\Components\TextInput::make('valor_pago')
                    ->label('Valor Pago')
                    ->prefix('R$')
                    ->inputMode('decimal')
                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('vencimento_fatura')
            ->columns([

                Tables\Columns\TextColumn::make('fechado')
                    ->Label('Fechada?')
                    ->badge()
                    ->alignCenter()
                    ->color(fn(string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'success',
                    })
                    ->formatStateUsing(function ($state) {
                        if ($state == 0) {
                            return 'Não';
                        }
                        if ($state == 1) {
                            return 'Sim';
                        }
                    }),
                Tables\Columns\TextColumn::make('pago')
                    
                    ->Label('Pago?')
                    ->badge()
                    ->alignCenter()
                    ->color(fn(string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'success',
                    })
                    ->formatStateUsing(function ($state) {
                        if ($state == 0) {
                            return 'Não';
                        }
                        if ($state == 1) {
                            return 'Sim';
                        }
                    }),
                Tables\Columns\TextColumn::make('nome')
                    ->label('Fatura')
                    ->searchable(),

                Tables\Columns\TextColumn::make('valor_fatura')
                    ->label('Valor Fatura')
                    ->money('BRL')
                    ->sortable(),
                // ->summarize(Sum::make()->label('Total Faturas')->money('BRL')),                   
                Tables\Columns\TextColumn::make('valor_pago')
                    ->label('Valor Pago')
                    ->money('BRL')
                    ->sortable(),
                // ->summarize(Sum::make()->label('Total Faturas')->money('BRL')),       
                Tables\Columns\TextColumn::make('vencimento_fatura')
                    ->label('Vencimento Fatura')
                    ->alignCenter()
                    ->date('d/m/Y')
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
                Filter::make('fechado')
                    ->label('Fechadas')
                    ->toggle()
                    ->query(fn(Builder $query): Builder => $query->where('fechado', true)),
                Filter::make('pago')
                    ->label('Pagas')
                    ->toggle()
                    ->query(fn(Builder $query): Builder => $query->where('pago', true)),

                SelectFilter::make('cartao')->relationship('cartao', 'nome')->searchable(),
                Tables\Filters\Filter::make('vencimento_fatura')
                    ->form([
                        Forms\Components\DatePicker::make('vencimento_de')
                            ->label('Vencimento de:')
                            ->default(Carbon::now()->startOfMonth()->addMonths(1)),
                        Forms\Components\DatePicker::make('vencimento_ate')
                            ->default(Carbon::now()->endOfMonth()->addMonths(1))
                            ->label('Vencimento até:'),
                    ])

                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['vencimento_de'],
                                fn($query) => $query->whereDate('vencimento_fatura', '>=', $data['vencimento_de'])
                            )
                            ->when(
                                $data['vencimento_ate'],
                                fn($query) => $query->whereDate('vencimento_fatura', '<=', $data['vencimento_ate'])
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Pagar'),
                Tables\Actions\DeleteAction::make()
                    ->hidden(),
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
