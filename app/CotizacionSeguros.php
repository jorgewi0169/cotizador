<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CotizacionSeguros extends Model
{
    protected $table = 'cotizacion_seguros'; //Indicamos con que tabla debemos trabajar

    protected $fillable = [
        'idcotizacion',
        'idtasa',
        'porcentaje_tasa',
        'idseguro',
        'auto_auto_aplica',
        'dis_rastreo_aplica',
        'interes_financiamiento_por',
        's_bancos_por',
        's_campesino_por',
        'seleccionar',
        'total_general',
        'prima_neta',
        'recibir_comision',
        'derecho_emicion',
        'total_desgravamen',
        'iva_por'
    ]; //asignacion de atributos en masa

    public $timestamps = false;

    /* relaciones */
    public function coberturas()
    {
        return $this->hasMany(SegurocoberturaDeducible::class,'idseguro');
    }
}
