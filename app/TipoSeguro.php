<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoSeguro extends Model
{
    protected $table = "tipo_Seguro";
    protected $fillable = [
        'nombre',
        'logo',
        'desgravamen',
        'cero_deducible',
        'amparo_patrimonial',
        'auto_auto',
        'dispositivo_rastreo',
        'created_by',
        'updated_by',
    ];
}
