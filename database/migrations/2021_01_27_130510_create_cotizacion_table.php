<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionTable extends Migration
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
            $table->foreign('idcliente')->references('id')->on('clientes');
            $table->integer('iduser')->unsigned();
            $table->foreign('iduser')->references('id')->on('users');
            $table->integer('idclasificacion')->unsigned();
            $table->foreign('idclasificacion')->references('id')->on('clasificacion');
            $table->integer('idtipo_uso')->unsigned();
            $table->foreign('idtipo_uso')->references('id')->on('tipo_uso');
            $table->integer('idmodelo')->unsigned();
            $table->foreign('idmodelo')->references('id')->on('modelo');
            $table->decimal('suma_asegurada', 11, 2)->nullable();
            $table->decimal('desgravamen', 11, 2)->nullable();
            $table->enum('alertar', ['SI', 'NO'])->nullable()->default('SI'); //Activo - Inactivo
            $table->enum('estado_registro', ['A', 'I'])->nullable()->default('A'); //Activo - Inactivo
            $table->string('telefono', 20)->nullable();
            $table->string('placa', 255)->nullable();
            $table->integer('created_by')->unsigned()->index()->nullable(); //quien lo creo,
            $table->integer('updated_by')->unsigned()->index()->nullable(); //quien actualizo
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
        Schema::dropIfExists('cotizacion');
        //Cambiar el llenado de las coverturas y dedicubles por un procedimiento almacenado que al momento de
        //registrar una nuea, este extraiga todos los id de los seguros_cobertura_dedible, e inserte masivo-

    }
}
