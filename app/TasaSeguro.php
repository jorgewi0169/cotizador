<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TasaSeguro extends Model
{
    protected $table = "tasa_seguro";
    protected $fillable = [
        'tasa',
        'idseguro'
    ];

    public $timestamps = false;
}
