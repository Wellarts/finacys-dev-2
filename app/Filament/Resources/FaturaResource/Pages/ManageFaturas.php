<?php

namespace App\Filament\Resources\FaturaResource\Pages;

use App\Filament\Resources\FaturaResource;
use App\Models\Cartao;
use App\Models\DataFatura;
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
                        //    $diaFatura =  DataFatura::where('cartao_id', $record->cartao_id)->first()->fechamento_fatura;
                        //    dd($diaFatura);
                        // dd($record->dataFatura->fechamento_fatura);
                        //  dd($record->cartao->fechamento_fatura);
                        for ($cont = 1; $cont < $record->qtd_parcela; $cont++) {

                            $parcelas = [
                                'valor_total' => $record->valor_total,
                                'qtd_parcela' => $record->qtd_parcela,
                                'ordem_parcela' => $cont + 1,
                                'forma_parcelamento' => $record->forma_parcelamento,
                                'data_vencimento' => Carbon::create($record->data_vencimento)
                                    ->startOfMonth()
                                    ->addDays($record->cartao->vencimento_fatura - 1)
                                    ->addMonths($cont)
                                    ->format('Y-m-d'),
                                'descricao' => $record->descricao,
                                'categoria_id' => $record->categoria_id,
                                'sub_categoria_id' => $record->sub_categoria_id,
                                'cartao_id' => $record->cartao_id,
                                'ignorado' => $record->ignorado,
                                'parcelado' => $record->parcelado,
                                'data_fatura' => 'Fatura ' . Cartao::where('id', $record->cartao_id)->first()->vencimento_fatura . '/' . Carbon::now()->addMonths($cont)->format('m/Y') . ' - ' . Cartao::where('id', $record->cartao_id)->first()->nome,
                                'valor_parcela' => $record->valor_parcela,
                                'pago' => $record->pago,
                                'anexo' => $record->anexo,
                                'team_id' => $record->team_id,


                            ];
                            //dd($parcelas);

                            Fatura::create($parcelas);

                            // $dataFatura = DataFatura::find($record->data_fatura_id - 1);
                            // $dataFatura->valor_fatura += $record->valor_parcela;
                            // $dataFatura->save();
                        }
                    } else {

                        // Ajusta o valor da parcela para o valor total
                        $record->valor_parcela = $record->valor_total;
                        $record->save();

                        // $dataFatura = DataFatura::find($record->data_fatura_id);
                        // $dataFatura->valor_fatura += $record->valor_parcela;
                        // $dataFatura->save();

                        // // Ajusta o valor do saldo da conta
                        // $saldoConta = Conta::find($record->conta_id);
                        // $saldoConta->saldo = $saldoConta->saldo - $record->valor_total;
                        // $saldoConta->save();
                    }
                })
        ];
    }
}
