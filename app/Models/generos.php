<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class generos extends Model
{
    protected $tabela = 'generos';

    protected $chavePrimaria = 'id';

    public $timestamps = false;

    protected $fillable = ['id', 'genero'];
}
