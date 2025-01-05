<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $fillable = [

        'despesa_conta_id',
        'despesa_categoria_id',
        'despesa_sub_categoria_id',
        'receita_conta_id',
        'receita_categoria_id',
        'receita_sub_categoria_id',
        'team_id',
    ];

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function categoriaDespesa() {
        return $this->belongsTo(Categoria::class, 'despesa_categoria_id');
    }

    public function categoriaReceita() {
        return $this->belongsTo(Categoria::class, 'receita_categoria_id');
    }

    public function contaDespesa() {
        return $this->belongsTo(Conta::class, 'despesa_conta_id');
    }

    public function contaReceita() {
        return $this->belongsTo(Conta::class, 'receita_conta_id');
    }

    public function cartao() {
        return $this->belongsTo(Cartao::class);
    }


}
