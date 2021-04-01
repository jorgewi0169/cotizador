<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoberturaDeducibleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cobertura_deducible', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 255);
            $table->enum('state', ['A', 'I'])->nullable()->default('A'); //Activo - Inactivo
            $table->enum('tipo', ['COBERTURA', 'DEDUCIBLE'])->nullable(); //Activo - Inactivo
            $table->enum('tipo_variable', ['MONTO', 'TEXTO', 'PORCENTAJE'])->nullable(); //Activo - Inactivo
            $table->integer('created_by')->unsigned()->index()->nullable(); //quien lo creo,
            $table->integer('updated_by')->unsigned()->index()->nullable(); //quien actualizo
            $table->timestamps();
           // $table->enum('aplica', ['SI', 'NO'])->nullable()->default('NO'); //Activo - Inactivo
           // $table->decimal('monto', 11, 2)->nullable();
           // $table->string('descripcion');
           // $table->enum('tipo_variable', ['SI', 'NO'])->nullable()->default('A'); //Activo - Inactivo

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cobertura_deducible');
    }
}
