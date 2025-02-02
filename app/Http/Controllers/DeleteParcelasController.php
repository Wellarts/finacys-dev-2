<?php

namespace App\Http\Controllers;

use App\Models\Cartao;
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
}
