<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fatura extends Model
{
    use HasFactory;

    protected $fillable = [
        'valor_total',
        'data_fatura_id',
        'data_vencimento',
        'data_pagamento',
        'pago',
        'cartao_id',
        'team_id',
        'categoria_id',
        'sub_categoria_id',
        'anexo',
        'descricao',
        'ignorado',
        'parcelado',
        'qtd_parcela',
        'valor_parcela',
        'ordem_parcela',
        'forma_parcelamento',
        'data_fatura_id',
    ];

    protected $casts = [
        'anexo' => 'array',
    ];



    public function dataFatura() {
        return $this->belongsTo(DataFatura::class);
    }

    public function cartao() {
        return $this->belongsTo(Cartao::class);
    }

    public function team() {
        return $this->belongsTo(Team::class);
    }

    
    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }

    public function subCategoria() {
        return $this->belongsTo(SubCategoria::class);
    }


}
