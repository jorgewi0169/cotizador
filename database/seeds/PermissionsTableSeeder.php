<?php

use App\Permissions;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Permissions::create([
            'name'  =>  'Navegar Inicio',
            'slug'  =>  'inicio.index'
        ]);
        //Usuarios
        Permissions::create([
            'name'  =>  'Navegar Usuarios',
            'slug'  =>  'usuario.index'
        ]);
        Permissions::create([
            'name'  =>  'Crear Usuarios',
            'slug'  =>  'usuario.crear'
        ]);
        Permissions::create([
            'name'  =>  'Editar Usuarios',
            'slug'  =>  'usuario.editar'
        ]);
        Permissions::create([
            'name'  =>  'Ver Usuarios',
            'slug'  =>  'usuario.ver'
        ]);
        Permissions::create([
            'name'  =>  'Activar Usuarios',
            'slug'  =>  'usuario.activar'
        ]);
        Permissions::create([
            'name'  =>  'Desactivar Usuarios',
            'slug'  =>  'usuario.desactivar'
        ]);

        Permissions::create([
            'name'  =>  'Eliminar Usuarios',
            'slug'  =>  'usuario.eliminar'
        ]);

       
        //Fin Usuarios

        //Roles
        Permissions::create([
            'name'  =>  'Navegar Roles',
            'slug'  =>  'rol.index'
        ]);
        Permissions::create([
            'name'  =>  'Crear Roles',
            'slug'  =>  'rol.crear'
        ]);
        Permissions::create([
            'name'  =>  'Editar Roles',
            'slug'  =>  'rol.editar'
        ]);
        Permissions::create([
            'name'  =>  'Eliminar Roles',
            'slug'  =>  'rol.eliminar'
        ]);

       
        //Fin Roles

        //Permisos
        Permissions::create([
            'name'  =>  'Navegar Permisos',
            'slug'  =>  'permisos.index'
        ]);

        Permissions::create([
            'name'  =>  'Crear Permiso',
            'slug'  =>  'permisos.crear'
        ]);
        Permissions::create([
            'name'  =>  'Editar Permiso',
            'slug'  =>  'permisos.editar'
        ]);
       
        //Permisos fin

        Permissions::create([
            'name'  =>  'Navegar seguro',
            'slug'  =>  'seguro.index'
        ]);

          Permissions::create([
            'name'  =>  'Crear seguro',
            'slug'  =>  'seguro.crear'
        ]);
        Permissions::create([
            'name'  =>  'Editar Seguro',
            'slug'  =>  'seguro.editar'
        ]);
        Permissions::create([
            'name'  =>  'Desactivar seguro',
            'slug'  =>  'seguro.desactivar'
        ]);

        Permissions::create([
            'name'  =>  'Activar seguro',
            'slug'  =>  'seguro.activar'
        ]);

        Permissions::create([
            'name'  =>  'Ver seguro',
            'slug'  =>  'seguro.ver'
        ]);

        Permissions::create([
            'name'  =>  'Eliminar seguro',
            'slug'  =>  'seguro.eliminar'
        ]);
          Permissions::create([
            'name'  =>  'Navegar categoria',
            'slug'  =>  'categoria.index'
        ]);

        Permissions::create([
            'name'  =>  'Crear categoria',
            'slug'  =>  'categoria.crear'
        ]);
        Permissions::create([
            'name'  =>  'Editar categoria',
            'slug'  =>  'categoria.editar'
        ]);
        Permissions::create([
            'name'  =>  'Desactivar categoria',
            'slug'  =>  'categoria.desactivar'
        ]);

        Permissions::create([
            'name'  =>  'Activar categoria',
            'slug'  =>  'categoria.activar'
        ]);

        Permissions::create([
            'name'  =>  'Ver categoria',
            'slug'  =>  'categoria.ver'
        ]);

        Permissions::create([
            'name'  =>  'Eliminar categoria',
            'slug'  =>  'categoria.eliminar'
        ]);

        Permissions::create([
            'name'  =>  'Navegar marca',
            'slug'  =>  'marca.index'
        ]);

        Permissions::create([
            'name'  =>  'Crear marca',
            'slug'  =>  'marca.crear'
        ]);
        Permissions::create([
            'name'  =>  'Editar marca',
            'slug'  =>  'marca.editar'
        ]);
        Permissions::create([
            'name'  =>  'Desactivar marca',
            'slug'  =>  'marca.desactivar'
        ]);

        Permissions::create([
            'name'  =>  'Activar marca',
            'slug'  =>  'marca.activar'
        ]);

        Permissions::create([
            'name'  =>  'Eliminar marca',
            'slug'  =>  'marca.eliminar'
        ]);

          Permissions::create([
            'name'  =>  'Navegar Tipo de Vehiculo',
            'slug'  =>  'tipovehiculo.index'
        ]);

        Permissions::create([
            'name'  =>  'Crear Tipo de Vehiculo',
            'slug'  =>  'tipovehiculo.crear'
        ]);
        Permissions::create([
            'name'  =>  'Editar Tipo de Vehiculo',
            'slug'  =>  'tipovehiculo.editar'
        ]);
        Permissions::create([
            'name'  =>  'Desactivar Tipo de Vehiculo',
            'slug'  =>  'tipovehiculo.desactivar'
        ]);

        Permissions::create([
            'name'  =>  'Activar Tipo de Vehiculo',
            'slug'  =>  'tipovehiculo.activar'
        ]);


        Permissions::create([
            'name'  =>  'Eliminar Tipo de Vehiculo',
            'slug'  =>  'tipovehiculo.eliminar'
        ]);

        Permissions::create([
            'name'  =>  'Navegar Tipo de Uso',
            'slug'  =>  'tipouso.index'
        ]);

        Permissions::create([
            'name'  =>  'Crear Tipo de Uso',
            'slug'  =>  'tipouso.crear'
        ]);
        Permissions::create([
            'name'  =>  'Editar Tipo de Uso',
            'slug'  =>  'tipouso.editar'
        ]);
        Permissions::create([
            'name'  =>  'Desactivar Tipo de Uso',
            'slug'  =>  'tipouso.desactivar'
        ]);

        Permissions::create([
            'name'  =>  'Activar Tipo de Uso',
            'slug'  =>  'tipouso.activar'
        ]);


        Permissions::create([
            'name'  =>  'Eliminar Tipo de Uso',
            'slug'  =>  'tipouso.eliminar'
        ]);

        Permissions::create([
            'name'  =>  'Navegar Modelo',
            'slug'  =>  'modelo.index'
        ]);

        Permissions::create([
            'name'  =>  'Crear Modelo',
            'slug'  =>  'modelo.crear'
        ]);
        Permissions::create([
            'name'  =>  'Editar Modelo',
            'slug'  =>  'modelo.editar'
        ]);
        Permissions::create([
            'name'  =>  'Desactivar Modelo',
            'slug'  =>  'modelo.desactivar'
        ]);

        Permissions::create([
            'name'  =>  'Activar Modelo',
            'slug'  =>  'modelo.activar'
        ]);


        Permissions::create([
            'name'  =>  'Eliminar Modelo',
            'slug' =>  'modelo.eliminar'
        ]);

         Permissions::create([
            'name'  =>  'Navegar Cliente',
            'slug'  =>  'cliente.index'
        ]);

         Permissions::create([
            'name'  =>  'Crear Cliente',
            'slug'  =>  'cliente.crear'
        ]);
        Permissions::create([
            'name'  =>  'Editar Cliente',
            'slug'  =>  'cliente.editar'
        ]);
        Permissions::create([
            'name'  =>  'Eliminar Cliente',
            'slug'  =>  'cliente.eliminar'
        ]);


         Permissions::create([
            'name'  =>  'Navegar Coberturadeducible',
            'slug'  =>  'coberturadeducible.index'
        ]);

         Permissions::create([
            'name'  =>  'Crear Coberturadeducible',
            'slug'  =>  'coberturadeducible.crear'
        ]);
        Permissions::create([
            'name'  =>  'Editar Coberturadeducible',
            'slug'  =>  'coberturadeducible.editar'
        ]);
        Permissions::create([
            'name'  =>  'Eliminar Coberturadeducible',
            'slug'  =>  'coberturadeducible.eliminar'
        ]);


        Permissions::create([
            'name'  =>  'Navegar Clasificacion',
            'slug'  =>  'clasificacion.index'
        ]);

        Permissions::create([
            'name'  =>  'Crear Clasificacion',
            'slug'  =>  'clasificacion.crear'
        ]);
        Permissions::create([
            'name'  =>  'Editar Clasificacion',
            'slug'  =>  'clasificacion.editar'
        ]);
        Permissions::create([
            'name'  =>  'Desactivar Clasificacion',
            'slug'  =>  'clasificacion.desactivar'
        ]);

        Permissions::create([
            'name'  =>  'Activar Clasificacion',
            'slug'  =>  'clasificacion.activar'
        ]);

        Permissions::create([
            'name'  =>  'Eliminar Clasificacion',
            'slug'  =>  'clasificacion.eliminar'
        ]);


         Permissions::create([
            'name'  =>  'Navegar Cotizacion',
            'slug'  =>  'cotizacion.index'
        ]);

        Permissions::create([
            'name'  =>  'Crear Cotizacion',
            'slug'  =>  'cotizacion.crear'
        ]);
        Permissions::create([
            'name'  =>  'Editar Cotizacion',
            'slug'  =>  'cotizacion.editar'
        ]);
        Permissions::create([
            'name'  =>  'Cotizacion PDF',
            'slug'  =>  'cotizacion.pdf'
        ]);

        Permissions::create([
            'name'  =>  'Cotizacion Detalle',
            'slug'  =>  'cotizacion.detalle'
        ]);

        Permissions::create([
            'name'  =>  'Cotizacion Seguimiento',
            'slug'  =>  'cotizacion.seguimiento'
        ]);
         

          
        Permissions::create([
            'name'  =>  'Navegar Pagos',
            'slug'  =>  'pagos.index'
        ]);

        Permissions::create([
            'name'  =>  'Crear Pagos',
            'slug'  =>  'pagos.crear'
        ]); 
    }
}
//php artisan db:seed --class=PermissionsTableSeeder
