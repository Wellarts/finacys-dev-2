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
    ];

    public function Conta() {
        return $this->belongsToMany(Team::class);
    }

    public function Team() {
        return $this->belongsToMany(Team::class);
    }

}
