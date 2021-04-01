<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('file', 256)->nullable(); //Inducamos que no es obligatorio
            $table->enum('state', ['A', 'I'])->nullable()->default('A'); //Activo - Inactivo
            $table->integer('iddocumento')->unsigned()->nullable(); //Indicamos que sera llave foranea
            $table->string('numdoc')->nullable();
            $table->string('telefono')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('created_by')->unsigned()->index()->nullable(); //quien lo creo,
            $table->integer('updated_by')->unsigned()->index()->nullable(); //quien actualizo
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('iddocumento')->references('id')->on('documento');
        });
        DB::table('users')->insert(array('firstname'        => 'Administrador',
                                         'lastname'         => 'Administrador',
                                         'username'         => 'Administrador',
                                         'email'            => 'admin@admin.com',
                                         'file'             => ' ',
                                         'state'            => 'A',
                                         'iddocumento'      => '1',
                                         'numdoc'           => '123456',
                                         'telefono'           => '123456789',
                                         'password'         => Hash::make('admin'),
                                         'created_by'       => '1'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
