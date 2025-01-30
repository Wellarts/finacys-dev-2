<?php

namespace App\Http\Controllers;

use App\Models\Fatura;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class DeleteParcelasController extends Controller
{
    function deleteParcelas($idCompra)
    {

        $faturas = Fatura::where('id_compra', $idCompra)->get();
        foreach ($faturas as $fatura) {
            $fatura->delete();
        }
        Notification::make()
            ->title('Parcelas excluídas com sucesso!')
            ->success()
            ->send();

        return back();
    }
}
