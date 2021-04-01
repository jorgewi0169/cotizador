<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizacion'; //Indicamos con que tabla debemos trabajar
    protected $fillable = [
        'idcliente',
        'iduser',
        'idclasificacion',
        'idtipo_uso',
        'idmodelo',
        'suma_asegurada',
        'desgravamen',
        'alertar',
        'telefono',
        'placa',
        'created_by',
        'updated_by',

    ]; //asignacion de atributos en masa


}
