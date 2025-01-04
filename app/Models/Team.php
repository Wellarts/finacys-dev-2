<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function members() {

        return $this->belongsToMany(User::class);

    }


    public function contas() {

        return $this->HasMany(Conta::class);

    }

    public function categorias() {

        return $this->HasMany(Categoria::class);

    }

    public function despesas() {

        return $this->HasMany(Despesa::class);

    }

    public function subCategorias() {

        return $this->HasMany(SubCategoria::class);

    }

    public function receitas() {

        return $this->HasMany(Receita::class);

    }

    public function configs() {

        return $this->HasMany(Config::class);

    }

    public function cartoes() {

        return $this->HasMany(Cartao::class);

    }
    
}
