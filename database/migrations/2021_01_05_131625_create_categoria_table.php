<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categoria', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 50);
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
        Schema::dropIfExists('categoria');
    }
}
