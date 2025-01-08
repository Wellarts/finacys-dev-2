<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceitaResource\Pages;
use App\Filament\Resources\ReceitaResource\RelationManagers;
use App\Models\Banco;
use App\Models\Config;
use App\Models\Conta;
use App\Models\Receita;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms\Get;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;


class ReceitaResource extends Resource
{
    protected static ?string $model = Receita::class;

    protected static ?string $navigationIcon = 'heroicon-s-arrow-up-on-square-stack';

    protected static ?string $navigationLabel = 'Receitas';

    protected static ?string $navigationGroup = 'Lançamentos';


    public static function form(Form $form): Form
    {
        return $form
            ->columns(null)
            ->schema([

                Tabs::make('Receita')
                    ->columns(null)
                    ->tabs([
                        Tabs\Tab::make('Receita')
                            ->columns([
                                'xl' => 3,
                                '2xl' => 3,
                            ])
                            ->schema([

                                Forms\Components\TextInput::make('valor_total')
                                    ->label('Valor Total')
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
                                Forms\Components\Select::make('conta_id')
                                    ->label('Conta')
                                    ->required()
                                    ->default(Config::first()->receita_conta_id)
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
                                            Forms\Components\Hidden::make('team_id')
                                                ->default(Filament::getTenant()->id),
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

                                Forms\Components\Select::make('categoria_id')
                                    ->label('Categoria')
                                    ->required()
                                    ->default(Config::first()->receita_categoria_id)
                                    ->searchable()
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
                                Forms\Components\ToggleButtons::make('recebido')
                                    ->label('Recebido?')
                                    //   ->extraInputAttributes(['tabindex' => 6])
                                    ->live()
                                    ->default(true)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state == true) {
                                            return $set('data_recebimento', Carbon::now()->format('Y-m-d'));
                                        } else {
                                            return $set('data_recebimento', null);
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
                                Forms\Components\DatePicker::make('data_recebimento')
                                    ->displayFormat('d/m/Y')
                                    //   ->extraInputAttributes(['tabindex' => 8])
                                    ->default(Carbon::now())
                                    ->label('Data Recebimento')
                                    ->required(fn(Get $get): bool => ($get('pago') == true)),
                                Forms\Components\Toggle::make('ignorado')
                                    ->columnSpan([
                                        'xl' => 2,
                                        '2xl' => 2,
                                    ])
                                    ->helperText('Não será aplicado nos totais de receitas'),

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
                                            $set('recebido', false);
                                            $set('data_recebimento', null);
                                        } else {
                                            $set('recebido', true);
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
            ->defaultSort('data_vencimento', 'asc   ')
            ->columns([
                Tables\Columns\TextColumn::make('recebido')
                    ->summarize(Count::make())
                    ->Label('Recebido?')
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
                Tables\Columns\TextColumn::make('data_vencimento')
                    ->alignCenter()
                    ->label('Data Vencimento')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_recebimento')
                    ->label('Data Recebimento')
                    ->date()
                    ->alignCenter()
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição')
                    ->words(2)
                    ->searchable(),
                Tables\Columns\TextColumn::make('conta.descricao')
                    ->label('Conta')
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
                Filter::make('areceber')
                    ->label('A Receber')
                    ->toggle()
                    ->query(fn(Builder $query): Builder => $query->where('recebido', false))->default(true),
                Filter::make('recebidas')
                    ->label('Recebidas')
                    ->toggle()
                    ->query(fn(Builder $query): Builder => $query->where('recebido', true)),

                SelectFilter::make('conta')->relationship('conta', 'descricao')->searchable(),
                SelectFilter::make('categoria')->relationship('categoria', 'nome')->searchable(),
                SelectFilter::make('subCategoria')->relationship('subCategoria', 'nome')->searchable()
                    ->label('SubCategoria'),
                Tables\Filters\Filter::make('data_vencimento')
                    ->form([
                        Forms\Components\DatePicker::make('vencimento_de')
                            ->label('Vencimento de:'),
                        Forms\Components\DatePicker::make('vencimento_ate')
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

            ])
            ->filtersFormColumns(1)
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function ($record) {
                        if ($record->recebido == true) {
                            $saldoConta = Conta::find($record->conta_id);
                            $saldoConta->saldo = $saldoConta->saldo + $record->valor_parcela;
                            $saldoConta->save();
                        }
                    }),
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
            'index' => Pages\ManageReceitas::route('/'),
        ];
    }
}
