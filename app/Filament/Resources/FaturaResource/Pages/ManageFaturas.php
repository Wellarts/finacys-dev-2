<?php

namespace App\Filament\Resources\FaturaResource\Pages;

use App\Filament\Resources\FaturaResource;
use App\Models\Fatura;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFaturas extends ManageRecords
{
    protected static string $resource = FaturaResource::class;

    protected static ?string $title = 'Despesas de Cartão';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                
                ->label('Nova Despesa')
                ->modalHeading('Despesas de Cartão')
                ->after(function ($record) {
                    if ($record->parcelado == true) {
                        for ($cont = 1; $cont < $record->qtd_parcela; $cont++) {
                            $parcelas = [
                                'valor_total' => $record->valor_total,
                                'qtd_parcela' => $record->qtd_parcela,
                                'ordem_parcela' => $cont + 1,
                                'forma_parcelamento' => $record->forma_parcelamento,
                                'data_vencimento' => Carbon::create($record->data_vencimento)
                                    ->startOfMonth()
                                    ->addDays(7)
                                    ->addMonths($cont)
                                    ->format('Y-m-d'),
                                'descricao' => $record->descricao,
                                'categoria_id' => $record->categoria_id,
                                'sub_categoria_id' => $record->sub_categoria_id,
                                'cartao_id' => $record->cartao_id,
                                'ignorado' => $record->ignorado,
                                'parcelado' => $record->parcelado,
                                'valor_parcela' => $record->valor_parcela,
                                'pago' => $record->pago,
                                'anexo' => $record->anexo,
                                'team_id' => $record->team_id,
                            ];
                            //dd($parcelas);
                            Fatura::create($parcelas);
                        }
                    } else {

                        // Ajusta o valor da parcela para o valor total
                        $record->valor_parcela = $record->valor_total;
                        $record->save();

                        // // Ajusta o valor do saldo da conta
                        // $saldoConta = Conta::find($record->conta_id);
                        // $saldoConta->saldo = $saldoConta->saldo - $record->valor_total;
                        // $saldoConta->save();
                    }
                })
        ];
    }
}
