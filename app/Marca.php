<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marca';//Indicamos con que tabla debemos trabajar
    protected $fillable = ['nombre', 'state']; //asignacion de atributos en masa
}
