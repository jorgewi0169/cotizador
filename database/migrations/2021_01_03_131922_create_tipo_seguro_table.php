<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoSeguroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_seguro', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('logo');
            $table->decimal('desgravamen', 11, 2)->nullable();
            $table->decimal('cero_deducible', 11, 2)->nullable();
            $table->decimal('amparo_patrimonial', 11, 2)->nullable();
            $table->decimal('auto_auto', 11, 2)->nullable();
           // $table->enum('aplica', ['SI', 'NO'])->nullable()->default('NO'); //Activo - Inactivo //solo en suadeb aplica, si aplica suma
            $table->decimal('dispositivo_rastreo', 11, 2)->nullable();
            $table->enum('state', ['A', 'I'])->nullable()->default('A'); //Activo - Inactivo
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
        Schema::dropIfExists('tipo_seguro');
    }
}

/* $table->decimal('adicional_sustituto', 11, 2)->nullable();
            $table->decimal('sweaden_cero_deducible', 11, 2)->nullable();
            $table->decimal('sweaden_amparo_patrimonial', 11, 2)->nullable();
            $table->decimal('taller_concesionario', 11, 2)->nullable();
            $table->decimal('dispositivo_rastreo', 11, 2)->nullable(); */
