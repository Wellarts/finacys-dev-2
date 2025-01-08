<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataFatura extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cartao_id',
        'valor_fatura',
        'valor_pago',
        'pago',
        'fechado',
        'vencimento_fatura',
        'team_id'
    ];

    public function faturas() {
        return $this->belongsTo(Fatura::class);
    }

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function cartao() {
        return $this->belongsTo(Cartao::class);
    }
}
