<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaturaResource\Pages;
use App\Models\Banco;
use App\Models\Cartao;
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
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\TernaryFilter;


class FaturaResource extends Resource
{
    protected static ?string $model = Fatura::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Despesa de Cartão';

    protected static ?string $navigationGroup = 'Lançamentos';


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

                                Forms\Components\Hidden::make('id_compra')
                                    ->default(rand(1000000000, 9999999999)),

                                Forms\Components\TextInput::make('valor_total')
                                    ->label('Valor Total')
                                    ->required()
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
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state != null) {
                                            $cartao = Cartao::where('id', $state)->first();
                                            $vencimentoFatura = $cartao->vencimento_fatura;
                                            $fechamentoFatura = $cartao->fechamento_fatura;
                                            $dataVencimento = Carbon::createFromFormat('d/m/Y', $vencimentoFatura . '/' . Carbon::now()->format('m/Y'));
                                            $dataFechamento = Carbon::createFromFormat('d/m/Y', $fechamentoFatura . '/' . Carbon::now()->format('m/Y'));

                                            if ($dataFechamento <= Carbon::now()) {
                                                if ($vencimentoFatura < $fechamentoFatura) {
                                                    $dataVencimento->addMonths(2);
                                                } else {
                                                    $dataVencimento->addMonths(1);
                                                }
                                            }

                                            $dataVencimento->format('Y-m-d');

                                            $set('data_fatura', 'Fatura: ' . $dataVencimento->format('d/m/Y') . ' - ' . $cartao->nome);
                                            $set('data_vencimento', $dataVencimento->format('Y-m-d'));
                                            ############################################
                                            // $set('data_vencimento', function ($state) {
                                            //     $cartao = Cartao::where('id', $state)->first();
                                            //     $vencimentoFatura = $cartao->vencimento_fatura;
                                            //     $fechamentoFatura = $cartao->fechamento_fatura;
                                            //     $dataVencimento = Carbon::createFromFormat('d/m/Y', $vencimentoFatura . '/' . Carbon::now()->format('m/Y'));
                                            //     $dataFechamento = Carbon::createFromFormat('d/m/Y', $fechamentoFatura . '/' . Carbon::now()->format('m/Y'));


                                            //     if ($dataFechamento <= Carbon::now()) {
                                            //         if ($vencimentoFatura < $fechamentoFatura) {
                                            //             return  $dataVencimento->addMonths(2);
                                            //         } else {
                                            //             $dataVencimento->addMonths(1);
                                            //         }
                                            //     }

                                            //     return $dataVencimento->format('Y-m-d');
                                            // });
                                        }
                                    })
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
                                            Forms\Components\Hidden::make('team_id')
                                                ->default(Filament::getTenant()->id),
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
                                                        Forms\Components\Hidden::make('team_id')
                                                            ->default(Filament::getTenant()->id),
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
                                        Forms\Components\Hidden::make('team_id')
                                            ->default(Filament::getTenant()->id),
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
                                        Forms\Components\Hidden::make('team_id')
                                            ->default(Filament::getTenant()->id),
                                    ]),
                                Forms\Components\ToggleButtons::make('pago')
                                    ->label('Pago?')
                                    //   ->extraInputAttributes(['tabindex' => 6])
                                    ->live()
                                    ->default(false)
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
                                    ->default(function (Get $get) {
                                        $cartao = Cartao::where('id', $get('cartao_id'))->first();
                                        $vencimentoFatura = $cartao->vencimento_fatura;
                                        $fechamentoFatura = $cartao->fechamento_fatura;
                                        $dataVencimento = Carbon::createFromFormat('d/m/Y', $vencimentoFatura . '/' . Carbon::now()->format('m/Y'));
                                        $dataFechamento = Carbon::createFromFormat('d/m/Y', $fechamentoFatura . '/' . Carbon::now()->format('m/Y'));


                                        if ($dataFechamento <= Carbon::now()) {
                                            if ($vencimentoFatura < $fechamentoFatura) {
                                                return  $dataVencimento->addMonth(2);
                                            } else {
                                                $dataVencimento->addMonth(1);
                                            }
                                        }

                                        return $dataVencimento->format('Y-m-d');
                                    })
                                    ->required()
                                    ->displayFormat('d/m/Y')
                                    ->label('Data Vencimento')
                                    ->required(),
                                Forms\Components\DatePicker::make('data_pagamento')
                                    ->displayFormat('d/m/Y')
                                    //   ->extraInputAttributes(['tabindex' => 8])
                                    ->live()
                                    ->default(function (Get $get) {
                                        return $get('pago') == true ? Carbon::now()->format('Y-m-d') : null;
                                    })
                                    ->label('Data Pagamento')
                                    ->required(fn(Get $get): bool => ($get('pago') == true)),
                                Forms\Components\Toggle::make('ignorado')
                                    ->columnSpan([
                                        'xl' => 1,
                                        '2xl' => 1,
                                    ])
                                    ->helperText('Não será aplicado nos totais de despesas'),
                                // Forms\Components\Select::make('data_fatura_id')
                                //     ->label('Fatura')
                                //     ->columnSpan([
                                //         'xl' => 2,
                                //         '2xl' => 2,
                                //     ])
                                //     ->searchable()
                                //     ->default(function (Get $get) {
                                //         return  DataFatura::where('cartao_id', $get('cartao_id'))->orderBy('id', 'asc')->first()->id;
                                //     })
                                //     ->preload()
                                //     ->relationship(
                                //         name: 'dataFatura',
                                //         titleAttribute: 'nome',
                                //         modifyQueryUsing: fn(Builder $query, Get $get) => $query->where('cartao_id', $get('cartao_id'))->whereBelongsTo(Filament::getTenant()),
                                //     ),
                                Forms\Components\TextInput::make('data_fatura')
                                    ->columnSpan([
                                        'xl' => 2,
                                        '2xl' => 2,
                                    ])
                                    // ->default(Cartao::where('id', Config::first()->cartao_id)->orderBy('id', 'asc')->first()->vencimento_fatura)
                                    ->default(function () {
                                        $cartao = Cartao::where('id', Config::first()->cartao_id)->first();
                                        $vencimentoFatura = $cartao->vencimento_fatura;
                                        $fechamentoFatura = $cartao->fechamento_fatura;
                                        $dataVencimento = Carbon::createFromFormat('d/m/Y', $vencimentoFatura . '/' . Carbon::now()->format('m/Y'));
                                        $dataFechamento = Carbon::createFromFormat('d/m/Y', $fechamentoFatura . '/' . Carbon::now()->format('m/Y'));

                                        if ($dataFechamento <= Carbon::now()) {
                                            if ($vencimentoFatura < $fechamentoFatura) {
                                                $dataVencimento->addMonths(2);
                                            } else {
                                                $dataVencimento->addMonths(1);
                                            }
                                        }

                                        $dataVencimento->format('Y-m-d');

                                        return 'Fatura: ' . $dataVencimento->format('d/m/Y') . ' - ' . $cartao->nome;
                                    })
                                    ->label('Data Fatura'),

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
            ->defaultSort('data_vencimento', 'asc')
            ->defaultGroup('data_vencimento', 'Fatura')           
            

            ->groups([
                Group::make('data_vencimento')
                    ->date('d/m/Y')
                    ->label('Vencimento')

            ])
            ->columns([
                Tables\Columns\TextColumn::make('pago')
                    ->summarize(Count::make())
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
                // Tables\Columns\TextColumn::make('valor_total')
                //     ->label('Valor Total')
                //     ->money('BRL'),
                // Tables\Columns\TextColumn::make('qtd_parcela')
                //     ->alignCenter()
                //     ->label('Qtd Parcelas')
                //     ->numeric(),
                Tables\Columns\TextColumn::make('ordem_parcela')
                    ->summarize(Count::make()->label('Qtd Parcelas'))
                    ->alignCenter()
                    ->label('Parcela Nº')
                    ->numeric(),
                Tables\Columns\TextColumn::make('valor_parcela')
                    ->summarize(Sum::make()->label('Total Parcelas')->money('BRL'))
                    ->money('BRL')
                    ->alignCenter()
                    ->label('Valor Parcela'),
                Tables\Columns\TextColumn::make('data_fatura')
                    ->badge()
                    ->alignCenter()
                    ->label('Fatura')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_vencimento')
                    ->alignCenter()
                    ->label('Data Vencimento')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_pagamento')
                    ->label('Data Pagamento')
                    ->date()
                    ->alignCenter()
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição')
                    ->words(2)
                    ->searchable(),
                Tables\Columns\TextColumn::make('cartao.nome')
                    ->label('Cartão')
                    ->sortable(),
                Tables\Columns\TextColumn::make('categoria.nome')
                    ->label('Categoria')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subCategoria.nome')
                    ->label('SubCategoria')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ignorado')
                    ->summarize(Count::make()->label('Qtd Ignoradas'))
                    ->Label('Ignorado?')
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
                Tables\Columns\TextColumn::make('parcelado')
                    ->summarize(Count::make()->label('Qtd Parceladas'))
                    ->Label('Parcelado?')
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
                // Filter::make('apagar')
                //     ->label('A Pagar')
                    
                //     ->toggle()
                //     ->query(fn(Builder $query): Builder => $query->where('pago', false))->default(true),
                // Filter::make('pagas')
                //     ->label('Pagas')
                //     ->toggle()
                //     ->query(fn(Builder $query): Builder => $query->where('pago', true)),
                TernaryFilter::make('pago')
                    ->label('Pago')
                    ->default(false),                  
                    

                SelectFilter::make('cartao')->relationship('cartao', 'nome')
                    ->searchable()
                    ->default(Config::first()->cartao_id)
                    ->label('Cartão'),
                SelectFilter::make('categoria')->relationship('categoria', 'nome')->searchable(),
                SelectFilter::make('subCategoria')->relationship('subCategoria', 'nome')->searchable()
                    ->label('SubCategoria'),
                Tables\Filters\Filter::make('data_vencimento')
                    ->form([
                        Forms\Components\DatePicker::make('vencimento_de')
                            ->label('Vencimento de:')
                            ->default(Carbon::now()->startOfMonth()),
                        Forms\Components\DatePicker::make('vencimento_ate')
                            ->default(Carbon::now()->endOfMonth()->addMonths(1))
                            ->label('Vencimento até:'),
                    ])
                    

                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['vencimento_de'],
                                fn($query) => $query->whereDate('data_vencimento', '>=', $data['vencimento_de'])
                            )
                            ->when(
                                $data['vencimento_ate'],
                                fn($query) => $query->whereDate('data_vencimento', '<=', $data['vencimento_ate'])
                            );
                    })
                ], layout: FiltersLayout::AboveContent)->filtersFormColumns(5)

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Confirmar exclusão')
                    ->after(function ($record) {
                        $cartao = Cartao::find($record->cartao_id);
                        $cartao->saldo += $record->valor_parcela;
                        $cartao->save();
                        Notification::make()
                            ->title('Demais parcelas')
                            ->body('Deseja também excluir todas as parcelas não paga desta compra?')
                            ->actions([
                                Action::make('Sim')
                                    ->button()
                                    ->color('danger')
                                    ->url(route('deleteParcelas', $record->id_compra)),
                                Action::make('Não')
                                    ->color('gray')
                                    ->close(),


                            ])
                            ->persistent()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
