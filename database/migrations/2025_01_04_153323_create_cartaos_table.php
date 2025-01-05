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
        Schema::create('cartaos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('bandeira');
            $table->decimal('limite', 10, 2);
            $table->decimal('saldo', 10, 2);
            $table->decimal('fatura', 10, 2);
            $table->integer('vencimento_fatura');
            $table->integer('fechamento_fatura');
            $table->boolean('status');
            $table->foreignId('conta_id')->constrained();
            $table->foreignId('team_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartaos');
    }
};
