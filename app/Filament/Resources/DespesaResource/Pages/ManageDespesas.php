<?php

namespace App\Filament\Resources\DespesaResource\Pages;

use App\Filament\Resources\DespesaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use App\Models\Despesa;
use Carbon\Carbon;
use App\Models\Conta;

class ManageDespesas extends ManageRecords
{
    protected static string $resource = DespesaResource::class;

    protected static ?string $title = 'Despesas';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nova')
                ->modalHeading('Despesas')
                ->after(function ($record) {
                    if ($record->parcelado == true) {
                        for ($cont = 1; $cont < $record->qtd_parcela; $cont++) {
                            $parcelas = [
                                'valor_total' => $record->valor_total,
                                'qtd_parcela' => $record->qtd_parcela,
                                'ordem_parcela' => $cont + 1,
                                'forma_parcelamento' => $record->forma_parcelamento,
                                'data_vencimento' => Carbon::create($record->data_vencimento)->addDays(30 * $cont),
                                'descricao' => $record->descricao,
                                'categoria_id' => $record->categoria_id,
                                'sub_categoria_id' => $record->sub_categoria_id,
                                'conta_id' => $record->conta_id,
                                'ignorado' => $record->ignorado,
                                'parcelado' => $record->parcelado,
                                'valor_parcela' => $record->valor_parcela,
                                'pago' => $record->pago,
                                'anexo' => $record->anexo,
                                'team_id' => $record->team_id,
                            ];
                            Despesa::create($parcelas);
                        }
                    } else {
                        $saldoConta = Conta::find($record->conta_id);
                        $saldoConta->saldo = $saldoConta->saldo - $record->valor_total;
                        $saldoConta->save();
                    }
                })
        ];
    }
}
