<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clasificacion extends Model
{
    protected $table = 'clasificacion';//Indicamos con que tabla debemos trabajar
    protected $fillable = ['nombre', 'state']; //asignacion de atributos en masa
}
