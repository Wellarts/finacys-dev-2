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
            // Nome da fatura
            $faturaGerar = 'Fatura: Cartão - '.$cartao->nome.' - '.Carbon::now()->addMonth()->format('m/Y');
            // Verificar se hoje é o dia de fechamento da fatura e se a fatura já foi gerada
            if (now()->day == $cartao->fechamento_fatura && !$faturas->contains('nome', $faturaGerar)) {
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
                    'vencimento_fatura' => now()->addMonth(),
                ]);
                Log::info('Fatura gerada para o cartão '.$cartao->id);

               
            }else{
                Log::info('Para o cartão '.$cartao->id.' fatura já existe ou não é o dia de fechamento da fatura');
            }
        }
    }
}
