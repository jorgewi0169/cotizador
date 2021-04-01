<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pagos_Empleados extends Model
{
    protected $table = 'pagos_empleados';
    protected $fillable = ['idempleado', 'monto', 'comision',  'por_comision', 'descripcion', 'created_by'];
}
