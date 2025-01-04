<?php

namespace App\Filament\Resources\ReceitaResource\Pages;

use App\Filament\Resources\ReceitaResource;
use App\Models\Conta;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Carbon\Carbon;
use App\Models\Receita;

class ManageReceitas extends ManageRecords
{
    protected static string $resource = ReceitaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Nova')
                ->modalHeading('Receitas')
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
                                'recebido' => $record->recebido,
                                'anexo' => $record->anexo,
                                'team_id' => $record->team_id,
                            ];
                            Receita::create($parcelas);
                        }
                    } else {

                        // Ajusta o valor da parcela para o valor total
                        $record->valor_parcela = $record->valor_total;
                        $record->save();                       

                        // Ajusta o valor do saldo da conta
                        $saldoConta = Conta::find($record->conta_id);
                        $saldoConta->saldo = $saldoConta->saldo + $record->valor_total;
                        $saldoConta->save();
                    }
                })
        ];
    }
}
