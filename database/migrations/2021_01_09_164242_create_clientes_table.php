<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 100);
            $table->integer('iddocumento')->unsigned()->nullable(); //Indicamos que sera llave foranea
            $table->foreign('iddocumento')->references('id')->on('documento');
            $table->string('numdoc')->nullable();
            $table->string('direccion', 70)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->Date('fecha_nacimiento')->nullable();
            $table->string('email', 50)->nullable();
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
        Schema::dropIfExists('clientes');
    }
}
