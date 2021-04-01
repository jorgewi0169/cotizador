<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeguimientoCotizacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguimiento_cotizacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcotizacion')->unsigned();
            $table->foreign('idcotizacion')->references('id')->on('cotizacion')->onDelete('cascade');
            $table->dateTime('vigencia_inicio')->nullable();
            $table->dateTime('vigencia_fin')->nullable();
            $table->string('file', 256)->nullable(); //Inducamos que no es obligatorio
            $table->text('comentario');
            $table->string('estado', 30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seguimiento_cotizacion');
    }
}
