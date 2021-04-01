<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RamoSeguro extends Model
{
    protected $table = 'ramo_seguro';//Indicamos con que tabla debemos trabajar
    protected $fillable = ['nombre', 'state']; //asignacion de atributos en masa
}
