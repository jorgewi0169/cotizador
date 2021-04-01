<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeguimientoCotizacion extends Model
{
    protected $table = 'seguimiento_cotizacion'; //Indicamos con que tabla debemos trabajar
    protected $fillable = [
        'idcotizacion',
        'vigencia_inicio',
        'vigencia_fin',
        'file',
        'comentario',
        'estado',
    ];
    //public $timestamps = false;
}
