<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoUso extends Model
{
    protected $table = 'tipo_uso';//Indicamos con que tabla debemos trabajar
    protected $fillable = ['nombre', 'state']; //asignacion de atributos en masa
}
