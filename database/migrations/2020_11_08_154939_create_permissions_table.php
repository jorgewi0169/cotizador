<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('name');
            $table->timestamps();
        });

        DB::table('permissions')->insert([
            [
                'name'  =>  'Navegar Inicio',
                'slug'  =>  'inicio.index'
            ],
            //Usuarios
            [
                'name'  =>  'Navegar Usuarios',
                'slug'  =>  'usuario.index'
            ],
            [
                'name'  =>  'Crear Usuarios',
                'slug'  =>  'usuario.crear'
            ],
            [
                'name'  =>  'Editar Usuarios',
                'slug'  =>  'usuario.editar'
            ],
            [
                'name'  =>  'Ver Usuarios',
                'slug'  =>  'usuario.ver'
            ],
            [
                'name'  =>  'Activar Usuarios',
                'slug'  =>  'usuario.activar'
            ],
            [
                'name'  =>  'Desactivar Usuarios',
                'slug'  =>  'usuario.desactivar'
            ],

            [
                'name'  =>  'Eliminar Usuarios',
                'slug'  =>  'usuario.eliminar'
            ],

            [
                'name'  =>  'Reporte Usuarios',
                'slug'  =>  'usuario.reporte'
            ],
            //Fin Usuarios

            //Roles
            [
                'name'  =>  'Navegar Roles',
                'slug'  =>  'rol.index'
            ],
            [
                'name'  =>  'Crear Roles',
                'slug'  =>  'rol.crear'
            ],
            [
                'name'  =>  'Editar Roles',
                'slug'  =>  'rol.editar'
            ],
            [
                'name'  =>  'Eliminar Roles',
                'slug'  =>  'rol.eliminar'
            ],

            [
                'name'  =>  'Reporte Roles',
                'slug'  =>  'rol.reporte'
            ],
            //Fin Roles

            //Permisos
            [
                'name'  =>  'Navegar Permisos',
                'slug'  =>  'permisos.index'
            ],

            [
                'name'  =>  'Crear Permiso',
                'slug'  =>  'permisos.crear'
            ],
            [
                'name'  =>  'Editar Permiso',
                'slug'  =>  'permisos.editar'
            ],
            [
                'name'  =>  'Reporte Permiso',
                'slug'  =>  'permisos.reporte'
            ],

            [
                'name'  =>  'Navegar Configuracion',
                'slug'  =>  'configuracion.index'
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
