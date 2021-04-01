<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracion';
    protected $fillable = [
        'titulo_pagina',
        'titulo_pdf',
        'pie_pdf',
        'iva',
        'direccion',
        'telefono',
        'email',
        'logo_1',
        'logo_2',
        'logo_pdf',
        'favicon_img'
    ];
    public $timestamps = false;
}
