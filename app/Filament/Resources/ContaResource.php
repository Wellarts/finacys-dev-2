<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContaResource\Pages;
use App\Filament\Resources\ContaResource\RelationManagers;
use App\Models\Conta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Table;
use App\Models\Banco;
use Filament\Set;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContaResource extends Resource
{
    protected static ?string $model = Conta::class;

    protected static ?string $navigationIcon = 'heroicon-s-banknotes';

    protected static ?string $navigationLabel = 'Contas';

    protected static ?string $navigationGroup = 'Cadastros';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Contas')
                    ->schema([
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('banco.nome')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('saldo')
                    ->badge()
                    ->color(function ($state) {
                        return $state > 0 ? 'success' : 'danger';
                    })
                    ->summarize(Sum::make()->label('Total Saldo')->money('BRL'))
                    ->label('Saldo')
                    ->money('BRL'),
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição')
                    ->searchable(),

                Tables\Columns\SelectColumn::make('tipo')
                    ->options([
                        'contaCorrente' => 'Conta Corrente',
                        'poupanca' => 'Poupança',
                        'investimento' => 'Investimento',
                        'Outro' => 'Outro',
                    ])
                    ->searchable(),
                Tables\Columns\TextColumn::make('agencia')
                    ->label('Nº da Agência')
                    ->searchable(),
                Tables\Columns\TextColumn::make('conta')
                    ->label('Nº da Conta')
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
            'index' => Pages\ManageContas::route('/'),
        ];
    }
}
