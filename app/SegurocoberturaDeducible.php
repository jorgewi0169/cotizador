<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SegurocoberturaDeducible extends Model
{
    protected $table = 'seguro_cobertura_deducible';//Indicamos con que tabla debemos trabajar
    protected $fillable = ['iddeco', 'idseguro', 'aplica', 'monto' , 'descripcion']; //asignacion de atributos en masa
    public $timestamps = false;
}
