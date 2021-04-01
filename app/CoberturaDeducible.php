<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoberturaDeducible extends Model
{
    protected $table = 'cobertura_deducible';//Indicamos con que tabla debemos trabajar
    protected $fillable = ['nombre', 'state', 'tipo', 'tipo_variable']; //asignacion de atributos en masa
}
