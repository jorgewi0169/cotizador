<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionSegurosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizacion_seguros', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcotizacion')->unsigned();
            $table->foreign('idcotizacion')->references('id')->on('cotizacion')->onDelete('cascade');
            $table->integer('idtasa')->unsigned();
            $table->foreign('idtasa')->references('id')->on('tasa_seguro');
            $table->decimal('porcentaje_tasa', 11, 2)->nullable();
            $table->integer('idseguro')->unsigned();
            $table->foreign('idseguro')->references('id')->on('tipo_seguro');
            $table->boolean('auto_auto_aplica');
            $table->boolean('dis_rastreo_aplica');
            $table->decimal('interes_financiamiento_por', 11, 2)->nullable();
            $table->decimal('s_bancos_por', 11, 2)->nullable();
            $table->decimal('derecho_emicion', 11, 2)->nullable();
            $table->decimal('s_campesino_por', 11, 2)->nullable();
            $table->enum('seleccionar', ['SI', 'NO'])->nullable(); //Activo - Inactivo
            $table->decimal('total_general', 11, 2)->nullable();
            $table->decimal('prima_neta', 11, 2)->nullable();
            $table->decimal('total_desgravamen', 11, 2)->nullable();
            $table->decimal('iva_por', 11, 2)->nullable();
            $table->boolean('recibir_comision')->nullable();//0->no // 1->si
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cotizacion_seguros');
    }
}
