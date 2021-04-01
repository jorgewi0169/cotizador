<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    protected $table = 'tipo_vehiculo';//Indicamos con que tabla debemos trabajar
    protected $fillable = ['nombre', 'state']; //asignacion de atributos en masa
}
