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
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('despesa_conta_id')->constrained('Contas')->restrictOnDelete();
            $table->foreignId('despesa_categoria_id')->constrained('Categorias')->restrictOnDelete();
            $table->foreignId('despesa_sub_categoria_id')->constrained('sub_categorias')->restrictOnDelete();
            
            $table->foreignId('receita_conta_id')->constrained('Contas')->restrictOnDelete();
            $table->foreignId('receita_categoria_id')->constrained('Categorias')->restrictOnDelete();
            $table->foreignId('receita_sub_categoria_id')->constrained('sub_categorias')->restrictOnDelete();

            $table->foreignId('cartao_id')->constrained()->restrictOnDelete();

            $table->foreignId('team_id')->constrained()->restrictOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configs');
    }
};
