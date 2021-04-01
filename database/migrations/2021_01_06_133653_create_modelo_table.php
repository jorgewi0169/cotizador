<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModeloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modelo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcategoria')->unsigned(); //Indicamos que sera llave foranea
            $table->foreign('idcategoria')->references('id')->on('categoria')->onDelete('cascade'); //Referenciamos para relacionar
            $table->integer('idmarca')->unsigned(); //Indicamos que sera llave foranea
            $table->foreign('idmarca')->references('id')->on('marca')->onDelete('cascade'); //Referenciamos para relacionar
            $table->integer('idtipovehiculo')->unsigned(); //Indicamos que sera llave foranea
            $table->foreign('idtipovehiculo')->references('id')->on('tipo_vehiculo')->onDelete('cascade'); //Referenciamos para relacionar
            $table->string('nombre', 255)->unique();
            $table->integer('año');
            $table->enum('state', ['A', 'I'])->nullable()->default('A'); //Activo - Inactivo
            $table->decimal('valor_mercado', 11, 2); //11 digitos 2 decimales
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
        Schema::dropIfExists('modelo');
    }
}
