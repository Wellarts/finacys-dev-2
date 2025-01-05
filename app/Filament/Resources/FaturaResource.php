<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaturaResource\Pages;
use App\Filament\Resources\FaturaResource\RelationManagers;
use App\Models\Fatura;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use App\Models\Config;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms\Get;
use Filament\Forms\Set;



class FaturaResource extends Resource
{
    protected static ?string $model = Fatura::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->columns(null)
        ->schema([

            Tabs::make('Despesa Cartão')
                ->columns(null)
                ->tabs([
                    Tabs\Tab::make('Despesa Cartão')
                        ->columns([
                            'xl' => 3,
                            '2xl' => 3,
                        ])
                        ->schema([

                            Forms\Components\TextInput::make('valor')
                                ->label('Valor')
                                ->autofocus()
                                ->numeric()
                                ->prefix('R$')
                                ->inputMode('decimal')
                                ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),
                            Forms\Components\TextInput::make('descricao')
                                ->label('Descrição')
                                //  ->extraInputAttributes(['tabindex' => 2])
                                ->columnSpan([
                                    'xl' => 2,
                                    '2xl' => 2,
                                ])

                                ->required(),
                            Forms\Components\Select::make('cartao_id')
                                ->label('Cartão')
                                ->required()
                                ->default(Config::first()->cartao_id)
                                ->searchable()
                                ->preload()
                                ->relationship(
                                    name: 'cartao',
                                    titleAttribute: 'nome',
                                    modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                                )
                                ->createOptionForm([
                                    Grid::make([
                                        'xl' => 3,
                                        '2xl' => 3,
                                    ])->schema([
                                                Forms\Components\TextInput::make('nome')
                                                    ->label('Nome')
                                                    ->required(),
                                                Forms\Components\Select::make('bandeira')
                                                    ->label('Bandeira')
                                                    ->searchable()
                                                    ->preload(false)
                                                    ->required()
                                                    ->options([
                                                        'Visa' => 'Visa',
                                                        'Mastercard' => 'Mastercard',
                                                        'Elo' => 'Elo',
                                                        'American Express' => 'American Express',
                                                        'Diners Club' => 'Diners Club',
                                                        'Discover' => 'Discover',
                                                        'JCB' => 'JCB',
                                                        'Aura' => 'Aura',
                                                        'Hipercard' => 'Hipercard',
                                                        'Hiper' => 'Hiper',
                                                        'Sorocred' => 'Sorocred',
                                                        'Cabal' => 'Cabal',
                                                        'Banescard' => 'Banescard',
                                                        'Credz' => 'Credz',
                                                        'BanriCard' => 'BanriCard',
                                                        'VeroCard' => 'VeroCard',
                                                        'Policard' => 'Policard',
                                                        'ValeCard' => 'ValeCard',
                                                        'Ticket' => 'Ticket',
                                                        'VR Benefícios' => 'VR Benefícios',
                                                        'Sodexo' => 'Sodexo',
                                                        'Alelo' => 'Alelo',
                                                        'Good Card' => 'Good Card',
                                                        'Green Card' => 'Green Card',
                                                        'Verocheque' => 'Verocheque',
                                                        'Vero Alimentação' => 'Vero Alimentação',
                                                        'Vero Refeição' => 'Vero Refeição',
                                                        'Vero Combustível' => 'Vero Combustível',
                                                        'Vero Cultura' => 'Vero Cultura',
                                                        'Vero Educação' => 'Vero Educação',
                                                        'Vero Farmácia' => 'Vero Farmácia',
                                                        'Vero Presente' => 'Vero Presente',
                                                        'Vero Saúde' => 'Vero Saúde',
                                                        'Vero Transporte' => 'Vero Transporte',
                                                        'Vero Turismo' => 'Vero Turismo',
                                                        'Vero Utilidades' => 'Vero Utilidades',
                                                        'Vero Viagem' => 'Vero Viagem',
                                                        'Vero Voucher' => 'Vero Voucher',
                                                        'Vero Voucher Alimentação' => 'Vero Voucher Alimentação',
                                                        'Vero Voucher Combustível' => 'Vero Voucher Combustível',
                                                        'Vero Voucher Cultura' => 'Vero Voucher Cultura',
                                                        'Vero Voucher Educação' => 'Vero Voucher Educação',
                                                        'Vero Voucher Farmácia' => 'Vero Voucher Farmácia',
                                                        'Vero Voucher Presente' => 'Vero Voucher Presente',
                                                        'Vero Voucher Saúde' => 'Vero Voucher Saúde',
                                                        'Vero Voucher Transporte' => 'Vero Voucher Transporte',
                                                        'Vero Voucher Turismo' => 'Vero Voucher Turismo',
                                                        'Vero Voucher Utilidades' => 'Vero Voucher Utilidades',
                                                        'Vero Voucher Viagem' => 'Vero Voucher Viagem',
                                                        'Outros' => 'Outros',
                                                    ]),
            
                                                Forms\Components\Select::make('conta_id')
                                                    ->label('Conta')
                                                    ->required()
                                                    // ->default(Config::first()->receita_conta_id)
                                                    ->searchable()
                                                    ->preload()
                                                    ->relationship(
                                                        name: 'conta',
                                                        titleAttribute: 'descricao',
                                                        modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                                                    )
                                                    ->createOptionForm([
                                                        Grid::make([
                                                            'xl' => 4,
                                                            '2xl' => 4,
                                                        ])->schema([
                                                                    Forms\Components\Select::make('banco_id')
                                                                        ->columnspan(2)
                                                                        ->label('Banco')
                                                                        ->searchable()
                                                                        ->live()
                                                                        ->afterStateUpdated(function ($state, callable $set) {
                                                                            if ($state != null) {
                                                                                $set('descricao', Banco::find($state)->nome . ' - ');
                                                                            } else {
                                                                                $set('descricao', '');
                                                                            }
                                                                        })
            
                                                                        ->relationship('banco', 'nome'),
                                                                    Forms\Components\TextInput::make('descricao')
                                                                        ->columnspan(2)
                                                                        ->hint('Dê um nome susgestivo para sua conta!')
                                                                        ->label('Descrição'),
                                                                    Forms\Components\Radio::make('tipo')
                                                                        ->options([
                                                                            'contaCorrente' => 'Conta Corrente',
                                                                            'poupanca' => 'Poupança',
                                                                            'investimento' => 'Investimento',
                                                                            'Outro' => 'Outro',
            
                                                                        ]),
                                                                    Forms\Components\TextInput::make('agencia')
                                                                        ->label('Nº da Agência'),
                                                                    Forms\Components\TextInput::make('conta')
                                                                        ->label('Nº da Conta'),
            
                                                                    Forms\Components\TextInput::make('saldo')
                                                                        ->label('Saldo')
                                                                        ->required()
                                                                        ->default(0)
                                                                        ->numeric()
                                                                        ->prefix('R$')
                                                                        ->inputMode('decimal')
                                                                        ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),
                                                                ]),
            
            
                                                    ]),
            
            
                                                Forms\Components\TextInput::make('limite')
                                                    ->label('Valor Limite')
                                                    ->numeric()
                                                    ->prefix('R$')
                                                    ->inputMode('decimal')
                                                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),
            
                                                Forms\Components\TextInput::make('saldo')
                                                    ->label('Valor Saldo')
                                                    ->numeric()
                                                    ->readonly()
                                                    ->prefix('R$')
                                                    ->inputMode('decimal')
                                                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),
                                                Forms\Components\TextInput::make('fatura')
                                                    ->label('Valor Fatura')
                                                    ->numeric()
                                                    ->prefix('R$')
                                                    ->inputMode('decimal')
                                                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),
                                                Forms\Components\TextInput::make('vencimento_fatura')
                                                    ->label('Vencimento Fatura')
                                                    ->numeric()
                                                    ->integer()
                                                    ->required(),
                                                Forms\Components\TextInput::make('fechamento_fatura')
                                                    ->label('Fechamento Fatura')
                                                    ->numeric()
                                                    ->integer()
                                                    ->required(),
                                                Forms\Components\ToggleButtons::make('status')
                                                    ->label('Ativado?')
                                                    ->default(true)
                                                    ->boolean()
                                                    ->grouped(),
                                            ]),
                                ]),
            

                            Forms\Components\Select::make('categoria_id')
                                ->label('Categoria')
                                ->required()
                                ->searchable()
                                ->default(Config::first()->despesa_categoria_id)
                                ->preload()
                                ->live()
                                ->relationship(
                                    name: 'categoria',
                                    titleAttribute: 'nome',
                                    modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                                    
                                )
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('nome')
                                        ->required(),
                                    Forms\Components\ColorPicker::make('cor'),
                                ]),
                            Forms\Components\Select::make('sub_categoria_id')
                                ->label('SubCategoria')
                                ->searchable()
                                ->preload()
                                ->label('SubCategoria')
                                ->relationship(
                                    name: 'subCategoria',
                                    titleAttribute: 'nome',
                                    modifyQueryUsing: fn(Builder $query, Get $get) => $query->where('categoria_id', $get('categoria_id'))->whereBelongsTo(Filament::getTenant()),
                                )
                                ->createOptionForm([
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
                                ]),
                            Forms\Components\ToggleButtons::make('pago')
                                ->label('Pago?')
                                //   ->extraInputAttributes(['tabindex' => 6])
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
                                ->default(Carbon::now())
                                //   ->extraInputAttributes(['tabindex' => 7])
                                ->required()
                                ->displayFormat('d/m/Y')
                                ->label('Data Vencimento')
                                ->required(),
                            Forms\Components\DatePicker::make('data_pagamento')
                                ->displayFormat('d/m/Y')
                                //   ->extraInputAttributes(['tabindex' => 8])
                                ->default(Carbon::now())
                                ->label('Data Pagamento')
                                ->required(fn(Get $get): bool => ($get('pago') == true)),
                            Forms\Components\Toggle::make('ignorado')
                                ->columnSpan([
                                    'xl' => 2,
                                    '2xl' => 2,
                                ])
                                ->helperText('Não será aplicado nos totais de despesas'),
                            Forms\Components\Select::make('data_fatura_id')
                                ->label('Fatura')
                                ->searchable()
                                ->preload()
                                ->relationship(
                                    name: 'dataFatura',
                                    titleAttribute: 'nome',
                                    modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                                ),

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
                                ->afterStateUpdated(function (callable $set, $state) {
                                    if ($state == true) {
                                        $set('pago', false);
                                        $set('data_pagamento', null);
                                    } else {
                                        $set('pago', true);
                                    }
                                })
                                ->grouped(),
                            Forms\Components\Select::make('forma_parcelamento')
                                ->label('Parcelamento')
                                //   ->extraInputAttributes(['tabindex' => 10])
                                ->hidden(fn(Get $get): bool => ($get('parcelado') == false))
                                ->default(30)
                                ->options([
                                    '7' => 'Semanal',
                                    '15' => 'Quinzenal',
                                    '30' => 'Mensal',
                                    '180' => 'Semestral',
                                    '360' => 'Anual',
                                ]),
                            Forms\Components\TextInput::make('qtd_parcela')
                                ->label('Qtd Parcelas')
                                //   ->extraInputAttributes(['tabindex' => 9])
                                ->hidden(fn(Get $get): bool => ($get('parcelado') == false))
                                ->required(fn(Get $get): bool => ($get('parcelado') == true))
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                    if ($state == true) {
                                        return $set('valor_parcela', $get('valor_total') / $state);
                                    } else {
                                        return $set('valor_parcela', null);
                                    }
                                })
                                ->numeric(),
                            Forms\Components\TextInput::make('ordem_parcela')
                                ->label('Parcela Nº')
                                ->default(1)
                                ->readonly()
                                ->hidden(fn(Get $get, $context): bool => ($get('parcelado') == false))
                                ->required(fn(Get $get): bool => ($get('parcelado') == true))
                                ->numeric(),




                            Forms\Components\TextInput::make('valor_parcela')
                                ->label('Valor Parcela')
                                //  ->extraInputAttributes(['tabindex' => 12])
                                ->hidden(fn(Get $get): bool => ($get('parcelado') == false))
                                ->required(fn(Get $get): bool => ($get('parcelado') == true))
                                ->prefix('R$')
                                ->inputMode('decimal')
                                ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),
                            Forms\Components\FileUpload::make('anexo')
                                ->hidden(fn(Get $get): bool => ($get('parcelado') == false))
                                ->columnSpanFull()
                                ->label('Anexo')
                                ->multiple()
                                ->downloadable(),
                        ]),
                ]),


        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('valor')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_fatura_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_vencimento')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_pagamento')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('pago')
                    ->boolean(),
                Tables\Columns\TextColumn::make('cartao_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('team_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('categoria_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sub_categoria_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('anexo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descricao')
                    ->searchable(),
                Tables\Columns\IconColumn::make('ignorado')
                    ->boolean(),
                Tables\Columns\IconColumn::make('parcelado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('qtd_parcela')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valor_parcela')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ordem_parcela')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('forma_parcelamento')
                    ->searchable(),
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
            'index' => Pages\ManageFaturas::route('/'),
        ];
    }
}
