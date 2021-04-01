<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo_pagina', 50);
            $table->string('titulo_pdf', 50);
            $table->string('pie_pdf', 50);
            $table->decimal('iva', 11, 2);
            $table->string('direccion', 50);
            $table->string('telefono', 50);
            $table->string('email', 50);
            $table->string('logo_1', 50)->nullable();
            $table->string('logo_2', 50)->nullable();
            $table->string('logo_pdf', 50)->nullable();
            $table->string('favicon_img', 50);
        });

        DB::table('configuracion')->insert(
            array(
                'titulo_pagina' => 'Sistema de de Cotizacion',
                'titulo_pdf' => 'Mi Tienda',
                'pie_pdf' => 'Gracias por su preferencia!',
                'iva' => 0.18,
                'direccion' => 'Puente Piedra - Lima Peru',
                'telefono' => '+51 123456789',
                'email' => 'mitienda@gmail.com',
                'logo_1' => 'logo1.png',
                'logo_2' =>      'logo2.png',
                'logo_pdf' => 'logo_pdf.png',
                'favicon_img' => 'favicon.png'
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracion');
    }
}
