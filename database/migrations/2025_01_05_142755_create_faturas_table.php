<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_faturas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('cartao_id')->constrained();
            $table->decimal('valor_fatura', 10, 2);
            $table->decimal('valor_pago', 10, 2);
            $table->boolean('pago');
            $table->boolean('fechado');
            $table->date('vencimento_fatura');
            $table->foreignId('team_id')->constrained();
            $table->timestamps();
        });

        Schema::create('faturas', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor_total', 10, 2);
            $table->foreignId('data_fatura_id')->constrained();
            $table->date('data_vencimento');
            $table->date('data_pagamento');
            $table->boolean('pago');
            $table->foreignId('cartao_id')->constrained();
            $table->foreignId('team_id')->constrained();
            $table->foreignId('categoria_id')->constrained();
            $table->foreignId('sub_categoria_id')->constrained();
            $table->string('anexo')->nullable();
            $table->string('descricao');
            $table->boolean('ignorado');
            $table->boolean('parcelado');
            $table->integer('qtd_parcela');
            $table->decimal('valor_parcela', 10, 2);
            $table->integer('ordem_parcela');
            $table->string('forma_parcelamento');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturas');
        Schema::dropIfExists('data_faturas');
    }
};
