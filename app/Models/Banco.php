<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome'
    ];

    
    public function conta() {
        return $this->HasMany(Conta::class);
    }
}
