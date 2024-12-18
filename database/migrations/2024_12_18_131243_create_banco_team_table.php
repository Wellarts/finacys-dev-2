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
        Schema::create('banco_team', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banco_id');
            $table->unsignedBigInteger('team_id');

            // Adicione índices para melhorar o desempenho das consultas
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('banco_id')->references('id')->on('bancos')->onDelete('cascade');

            // Se precisar de campos adicionais na tabela pivot, adicione-os aqui
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banco_team');
    }
};
