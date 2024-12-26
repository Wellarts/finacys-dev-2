<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DespesaResource\Pages;
use App\Models\Despesa;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Grid;
use Carbon\Carbon;

class DespesaResource extends Resource
{
    protected static ?string $model = Despesa::class;

    protected static ?string $navigationIcon = 'heroicon-s-arrow-down-on-square-stack';

    protected static ?string $navigationLabel = 'Despesas';

    protected static ?string $navigationGroup = 'Lançamentos';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(null)
            ->schema([

                Tabs::make('Despesa')
                    ->columns(null)
                    ->tabs([
                        Tabs\Tab::make('Despesa')
                            ->columns([
                                'xl' => 3,
                                '2xl' => 3,
                            ])
                            ->schema([

                                Forms\Components\TextInput::make('valor_total')
                                    ->label('Valor Total')
                                    ->numeric()
                                    ->prefix('R$')
                                    ->inputMode('decimal')
                                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),
                                Forms\Components\TextInput::make('descricao')
                                    ->label('Descrição')
                                    ->columnSpan([
                                        'xl' => 2,
                                        '2xl' => 2,
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('conta_id')
                                    ->label('Conta')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->relationship(
                                        name: 'conta',
                                        titleAttribute: 'descricao',
                                        modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                                    ),

                                Forms\Components\Select::make('categoria_id')
                                    ->label('Categoria')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->relationship(
                                        name: 'categoria',
                                        titleAttribute: 'nome',
                                        modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                                    ),
                                Forms\Components\Select::make('sub_categoria_id')
                                    ->label('SubCategoria')
                                    ->searchable()
                                    ->preload()
                                    ->label('SubCategoria')
                                    ->relationship(
                                        name: 'subCategoria',
                                        titleAttribute: 'nome',
                                        modifyQueryUsing: fn(Builder $query, Get $get) => $query->where('categoria_id', $get('categoria_id'))->whereBelongsTo(Filament::getTenant()),
                                    ),
                                Forms\Components\ToggleButtons::make('pago')
                                    ->label('Pago?')
                                    ->live()
                                    ->default(true)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state == true) {
                                            return $set('data_pagamento', Carbon::now()->format('Y-m-d'));
                                        } else {
                                            return $set('data_pagamento', null);
                                        }
                                    })
                                    ->boolean()
                                    ->grouped(),
                                Forms\Components\DatePicker::make('data_vencimento')
                                    ->default(now())
                                    ->required()
                                    ->label('Data Vencimento')
                                    ->required(),
                                Forms\Components\DatePicker::make('data_pagamento')
                                    ->displayFormat('d/m/Y')
                                    ->default(now())
                                    ->label('Data Pagamento')
                                    ->required(fn(Get $get): bool => ($get('pago') == true)),
                                Forms\Components\Toggle::make('ignorado')
                                    ->columnSpan([
                                        'xl' => 2,
                                        '2xl' => 2,
                                    ])
                                    ->helperText('Não será aplicado nos totais de despesas'),

                            ]),


                        Tabs\Tab::make('Parcelamentos')
                            ->columns([
                                'xl' => 3,
                                '2xl' => 3,
                            ])
                            ->schema([
                                Forms\Components\ToggleButtons::make('parcelado')
                                    ->label('Parcelado?')
                                    ->default(false)
                                    ->boolean()
                                    ->live()
                                    ->grouped(),
                                Forms\Components\TextInput::make('qtd_parcela')
                                    ->label('Qtd Parcelas')
                                    ->hidden(fn(Get $get): bool => ($get('parcelado') == false))
                                    ->required(fn(Get $get): bool => ($get('parcelado') == true))
                                    ->numeric(),
                                Forms\Components\Select::make('forma_parcelamento')
                                    ->label('Parcelamento')
                                    ->hidden(fn(Get $get): bool => ($get('parcelado') == false))
                                    ->default(30)
                                    ->options([
                                        '7' => 'Semanal',
                                        '15' => 'Quinzenal',
                                        '30' => 'Mensal',
                                        '180' => 'Semestral',
                                        '360' => 'Mensal',
                                    ]),


                                Forms\Components\TextInput::make('anexo')
                                    ->hidden(fn(Get $get): bool => ($get('parcelado') == false))
                                    ->maxLength(255),




                                Forms\Components\TextInput::make('valor_parcela')
                                    ->numeric(),
                            ]),
                    ]),


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
