<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_empleados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idusuario')->unsigned();
            $table->foreign('idusuario')->references('id')->on('users');
            $table->decimal('monto', 11, 2); //11 digitos 2 decimales
            $table->decimal('comision', 11, 2);
            $table->decimal('por_comision', 11, 2);
            //$table->enum('state', ['A', 'I'])->nullable()->default('A'); //Activo - Inactivo//fala implementar anulacion
            $table->string('descripcion', 20)->nullable(); //comision/pago_mes
            $table->integer('created_by')->unsigned()->index()->nullable(); //quien lo creo,
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
        Schema::dropIfExists('pagos_empleados');
    }
}
