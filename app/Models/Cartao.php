<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cartao extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'bandeira',
        'limite',
        'saldo',
        'fatura',
        'vencimento_fatura',
        'fechamento_fatura',
        'status',
        'conta_id',
        'team_id',
    ];

    public function conta()
    {
        return $this->belongsTo(Conta::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    
}
