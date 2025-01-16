<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Cartao;
use App\Models\DataFatura;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CreateFaturaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */

    public function handle(): void
    {
        // Buscar todos os cartões e criar as faturas
        $cartoes = Cartao::all();
        $faturas = DataFatura::all();


        foreach ($cartoes as $cartao) {
            for ($x = 1; $x < 19; $x++) {
                // Nome da fatura
                $faturaGerar = 'Fatura: Cartão - ' . $cartao->nome . ' - ' . Carbon::now()->addMonths($x)->format('m/Y');
                // Verificar se hoje é o dia de fechamento da fatura e se a fatura já foi gerada
                if (/*now()->day == $cartao->fechamento_fatura &&*/ !$faturas->contains('nome', $faturaGerar)) {
                    // Lógica para calcular o valor da fatura (implemente aqui)
                    //  $valorFatura = ...;

                    // Criar o registro na tabela data_faturas
                    DataFatura::create([
                        // 'nome' => 'Fatura de '.Carbon::now()->format('m/Y'), // Assumindo que o cartão pertence a um usuário
                        'nome' => $faturaGerar,
                        'cartao_id' => $cartao->id,
                        'team_id' => $cartao->team_id,
                        'pago' => false,
                        'fechado' => false,
                       // 'vencimento_fatura' => now()->addMonths($x),
                       'vencimento_fatura' => Carbon::parse(now())->addMonths($x)->startOfMonth()->addDays($cartao->vencimento_fatura -1)
                    ]);
                    Log::info('Fatura gerada para o cartão ' . $cartao->id.' Vencimento: '.Carbon::parse(now())->addMonths($x)->addDays($cartao->vencimento_fatura)->format('Y-m-d'));
                } else {
                    Log::info('Para o cartão ' . $cartao->id . ' faturas já existe');
                }
            }
        }
    }
}
