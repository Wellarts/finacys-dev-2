<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CartaoResource\Pages;
use App\Filament\Resources\CartaoResource\RelationManagers;
use App\Models\Banco;
use App\Models\Cartao;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\Summarizers\Sum;

class CartaoResource extends Resource
{
    protected static ?string $model = Cartao::class;

    protected static ?string $navigationIcon = 'heroicon-s-credit-card';

    protected static ?string $navigationLabel = 'Cartões';

    protected static ?string $navigationGroup = 'Cadastros';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Cartões')
                    ->schema([
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
                                        Forms\Components\Hidden::make('team_id')
                                            ->default(Filament::getTenant()->id),
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
                            Forms\Components\TextInput::make('fechamento_fatura')
                                ->label('Fechamento Fatura')
                                ->numeric()
                                ->integer()
                                ->required(),
                            Forms\Components\TextInput::make('vencimento_fatura')
                                ->label('Vencimento Fatura')
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


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bandeira')
                    ->searchable(),
                Tables\Columns\TextColumn::make('conta.descricao')
                    ->sortable(),
                Tables\Columns\TextColumn::make('limite')
                    ->money('BRL')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('saldo')
                    ->money('BRL')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fatura')
                    ->summarize(Sum::make()->label('Total das Faturas')->money('BRL'))
                    ->money('BRL')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vencimento_fatura')
                    ->alignCenter()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fechamento_fatura')
                    ->alignCenter()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->summarize(Count::make())
                    ->Label('Ativo?')
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
            'index' => Pages\ManageCartaos::route('/'),
        ];
    }
}
