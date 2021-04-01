<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class modelo extends Model
{
    protected $table = 'modelo'; //Indicamos con que tabla debemos trabajar
    protected $fillable = ['idcategoria', 'idmarca', 'idtipovehiculo', 'nombre', 'año', 'valor_mercado', 'state']; //asignacion de atributos en masa
}
