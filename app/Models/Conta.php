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
    ];

    public function Team() {
        return $this->belongsToMany(Team::class);
    }

    public function Banco() {
        return $this->belongsTo(Banco::class);
    }
}
