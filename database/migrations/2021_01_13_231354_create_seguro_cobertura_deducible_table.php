<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSegurocoberturaDeducibleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguro_cobertura_deducible', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('iddeco')->unsigned();
            $table->foreign('iddeco')->references('id')->on('cobertura_deducible')->onDelete('cascade'); //Referenciamos para relacionar
            $table->integer('idseguro')->unsigned();
            $table->foreign('idseguro')->references('id')->on('tipo_seguro')->onDelete('cascade'); //Referenciamos para relacionar
            $table->enum('aplica', ['SI', 'NO'])->nullable()->default('NO'); //Activo - Inactivo
            $table->decimal('monto', 11, 2)->nullable();//monto, porcentaje
            $table->string('descripcion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seguro_cobertura_deducible');
    }
}
