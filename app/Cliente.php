<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['nombre', 'numdoc', 'direccion', 'telefono', 'fecha_nacimiento', 'email', 'created_by', 'updated_bye', 'iddocumento'];
}
