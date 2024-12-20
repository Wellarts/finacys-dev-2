<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cor',
        'team_id',
    ];

    

    public function team() {
        return $this->belongsTo(Team::class);
    }
        
    public function conta() {
        return $this->belongsTo(Conta::class);
    }

    public function despesa() {
        return $this->belongsTo(Despesa::class);
    }

    public function subCategoria() {
        return $this->belongsTo(SubCategoria::class);
    }

}
