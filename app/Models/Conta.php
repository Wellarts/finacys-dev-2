<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    use HasFactory;

    protected $fillable = [
            'banco_id',
            'banco_id',
            'tipo',
            'agencia',
            'conta',
            'descricao',
            'saldo',
            'team_id',
    ];

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function Banco() {
        return $this->belongsTo(Banco::class);
    }

    
}
