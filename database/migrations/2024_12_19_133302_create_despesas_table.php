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
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor_total',10,2);
            $table->boolean('pago');
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->string('descricao');
            $table->foreignId('categoria_id')->constrained('categorias')->restrictOnDelete();
            $table->foreignId('sub_categoria_id')->constrained('sub_categorias')->restrictOnDelete();
            $table->foreignId('conta_id')->constrained('contas')->restrictOnDelete();
            $table->string('anexo')->nullable();
            $table->boolean('ignorado')->nullable();
            $table->boolean('parcelado');
            $table->string('forma_parcelamento')->nullable();
            $table->integer('qtd_parcela')->nullable();
            $table->decimal('valor_parcela')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despesas');
    }
};
