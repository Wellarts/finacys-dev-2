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
        Schema::create('contas', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('banco_id');
            $table->foreign('banco_id')->references('id')->on('bancos')->onDelete('cascade');
            $table->string('tipo');
            $table->string('agencia');
            $table->string('conta');
            $table->string('descricao');
            $table->decimal('saldo',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas');
    }
};
