<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcliente')->unsigned();
            $table->foreign('idcliente')->references('id')->on('clientes')->onDelete('cascade');
            $table->integer('iduser')->unsigned();
            $table->foreign('iduser')->references('id')->on('users')->onDelete('cascade');
            $table->integer('idclasificacion')->unsigned();
            $table->foreign('idclasificacion')->references('id')->on('clasificacion')->onDelete('cascade');
            $table->integer('idtipo_uso')->unsigned();
            $table->foreign('idtipo_uso')->references('id')->on('tipo_uso')->onDelete('cascade');
            $table->integer('idmodelo')->unsigned();
            $table->foreign('idmodelo')->references('id')->on('modelo')->onDelete('cascade');
            $table->decimal('suma_asegurada', 11, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cotizacion');
        //Cambiar el llenado de las coverturas y dedicubles por un procedimiento almacenado que al momento de
        //registrar una nuea, este extraiga todos los id de los seguros_cobertura_dedible, e inserte masivo-

    }
}
