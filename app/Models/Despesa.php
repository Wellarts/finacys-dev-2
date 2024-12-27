<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    use HasFactory;

    protected $fillable = [

        'valor_total',
        'pago',
        'data_vencimento',
        'data_pagamento',
        'descricao',
        'categoria_id',
        'sub_categoria_id',
        'conta_id',
        'anexo',
        'ignorado',
        'parcelado',
        'forma_parcelamento',
        'qtd_parcela',
        'ordem_parcela',
        'valor_parcela',
        'team_id',

    ];

    protected $casts = [
        'anexo' => 'array',
    ];

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }

    public function subCategoria() {
        return $this->belongsTo(SubCategoria::class);
    }

    public function conta() {
        return $this->belongsTo(Conta::class);
    }


}
