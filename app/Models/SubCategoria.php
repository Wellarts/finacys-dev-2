<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoria_id',
        'nome',
    ];

    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }

    public function despesa() {
        return $this->belongsTo(Despesa::class);
    }


    public function team() {
        return $this->belongsTo(Team::class);
    }
}