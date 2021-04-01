<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasaSeguroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasa_seguro', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('tasa', 11, 2)->nullable();
            $table->integer('idseguro')->unsigned();
            $table->foreign('idseguro')->references('id')->on('tipo_seguro')->onDelete('cascade'); //Referenciamos para relacionar
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasa_seguro');
    }
}
