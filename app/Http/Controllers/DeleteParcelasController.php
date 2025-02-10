<?php

namespace App\Http\Controllers;

use App\Models\Cartao;
use App\Models\Conta;
use App\Models\Despesa;
use App\Models\Fatura;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class DeleteParcelasController extends Controller
{
    function deleteParcelas($idCompra)
    {

        $faturas = Fatura::where('id_compra', $idCompra)->get();
        foreach ($faturas as $fatura) {
            
            // ajusta limite do cartão
            $cartao = Cartao::find($fatura->cartao_id);
            $cartao->saldo += $fatura->valor_parcela;
            $cartao->save();
            
            // delete as parcelas
            $fatura->delete();

        }
        Notification::make()
            ->title('Parcelas excluídas com sucesso!')
            ->success()
            ->send();

        return back();
    }

    function deleteDespesas($idDespesa)
    {

        $despesas = Despesa::where('id_despesa', $idDespesa)->get();
        foreach ($despesas as $despesa) {
            
            // ajusta limite do cartão
            $conta = Conta::find($despesa->conta_id);
            $conta->saldo += $despesa->valor_parcela;
            $conta->save();
            
            // delete as parcelas
            $despesa->delete();

        }
        Notification::make()
            ->title('Parcelas excluídas com sucesso!')
            ->success()
            ->send();

        return back();
    }
}
