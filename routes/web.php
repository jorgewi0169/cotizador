<?php

use App\Configuracion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/authenticate/login', 'Auth\LoginController@login');



Route::group(['middleware' => ['auth']], function () {


    Route::post('/authenticate/logout', 'Auth\LoginController@logout');

    Route::get('/authenticate/getRefrescarUsuarioAutenticado', function () {
        return Auth::user();
    });

    Route::get('/administracion/usuario/getListarRolPermisosByUsuario', 'Administracion\UsersController@getListarRolPermisosByUsuario');

    Route::get('/administracion/usuario/getListarUsuarios', 'Administracion\UsersController@getListarUsuarios'); //
    Route::get('/administracion/usuario/getListarDocumentos', 'Administracion\DocumentoController@getListarDocumento'); //va en documeto
    Route::post('/administracion/usuario/registrar', 'Administracion\UsersController@store'); //
    Route::get('/administracion/usuario/obteneruser', 'Administracion\UsersController@obtenerUser'); //
    Route::post('/administracion/usuario/actualizar', 'Administracion\UsersController@update'); //
    Route::post('/administracion/usuario/estado', 'Administracion\UsersController@cambiarEstadoUser'); //
    Route::get('/administracion/rol/listarRol', 'Administracion\RolesController@listarRol'); //
    Route::get('/administracion/rol/lispermisorol', 'Administracion\RolesController@listarPermisosRoles'); //
    Route::post('/administracion/usuario/registrarpermisosuser', 'Administracion\UsersController@registrarPermisosUser'); //



    Route::get('/administracion/rol/getListar', 'Administracion\RolesController@getListarRol'); //
    Route::post('/administracion/rol/registrar', 'Administracion\RolesController@store'); //
    Route::get('/administracion/rol/getListarPermisosByRol', 'Administracion\RolesController@getListarPermisosByRol');
    Route::get('/administracion/rol/obtenerrol', 'Administracion\RolesController@obtenerRol'); //
    Route::post('/administracion/rol/actualizar', 'Administracion\RolesController@update'); //
    Route::get('/administracion/rol/verrol', 'Administracion\RolesController@verRol'); //

    Route::get('/administracion/permiso/getListarPermisos', 'Administracion\PermissionsController@getListarPermisos'); //
    Route::post('/administracion/permiso/registrar', 'Administracion\PermissionsController@store'); //
    Route::post('/administracion/permiso/actualizar', 'Administracion\PermissionsController@update'); //

    Route::post('/configuracion/actualizar', 'ConfiguracionController@update');

    Route::get('/documento', 'Administracion\DocumentoController@index');
    Route::post('/documento/registrar', 'Administracion\DocumentoController@store');
    Route::put('/documento/actualizar', 'Administracion\DocumentoController@update');
    Route::post('/documento/delete', 'Administracion\DocumentoController@destroy');

    Route::post('/seguro', 'seguro\TipoSeguroController@index');
    Route::post('/seguro/registrar', 'seguro\TipoSeguroController@store');
    Route::post('/seguro/actualizar', 'seguro\TipoSeguroController@update');
    Route::post('/seguro/estado', 'seguro\TipoSeguroController@state');
    Route::post('/seguro/delete', 'seguro\TipoSeguroController@destroy');
    Route::post('/seguro/tasas', 'seguro\TasaSeguroController@obtenerTasa');
    Route::get('/seguro/list', 'seguro\CoberturaDeducibleController@selectCode');
    Route::post('/seguro/obtener', 'seguro\TipoSeguroController@obtenerseguro');

    Route::get('/categoria', 'autos\CategoriaController@index');
    Route::post('/categoria/registrar', 'autos\CategoriaController@store');
    Route::put('/categoria/actualizar', 'autos\CategoriaController@update');
    Route::post('/categoria/estado', 'autos\CategoriaController@cambiarEstadoCategoria'); //
    //Route::post('/categoria/delete', 'Almacen\CategoriaController@destroy');
    //Route::get('/categoria/listarPdf', 'Almacen\CategoriaController@listarPdf')->name('articulos_pdf');

    Route::get('/marca', 'autos\MarcaController@index');
    Route::post('/marca/registrar', 'autos\MarcaController@store');
    Route::put('/marca/actualizar', 'autos\MarcaController@update');
    Route::post('/marca/estado', 'autos\MarcaController@cambiarEstadoMarca'); //
    //Route::post('/categoria/delete', 'Almacen\MarcaController@destroy');
    //Route::get('/categoria/listarPdf', 'Almacen\MarcaController@listarPdf')->name('articulos_pdf');

    Route::get('/tipovehiculo', 'autos\TipoVehiculoController@index');
    Route::post('/tipovehiculo/registrar', 'autos\TipoVehiculoController@store');
    Route::put('/tipovehiculo/actualizar', 'autos\TipoVehiculoController@update');
    Route::post('/tipovehiculo/estado', 'autos\TipoVehiculoController@cambiarEstadoTipoVehiculo'); //
    //Route::post('/categoria/delete', 'Almacen\TipoVehiculoController@destroy');
    //Route::get('/categoria/listarPdf', 'Almacen\TipoVehiculoController@listarPdf')->name('articulos_pdf');

    Route::get('/tipouso', 'autos\TipoUsoController@index');
    Route::post('/tipouso/registrar', 'autos\TipoUsoController@store');
    Route::put('/tipouso/actualizar', 'autos\TipoUsoController@update');
    Route::post('/tipouso/estado', 'autos\TipoUsoController@cambiarEstadotipouso'); //
    //Route::post('/categoria/delete', 'Almacen\TipoUsoController@destroy');
    //Route::get('/categoria/listarPdf', 'Almacen\TipoUsoController@listarPdf')->name('articulos_pdf');

    Route::get('/modelo', 'autos\ModeloController@index');
    Route::get('/categoria/getListarCategorias', 'autos\CategoriaController@selectCategoria');
    Route::get('/marca/getListarMarca', 'autos\MarcaController@selectMarca');
    Route::get('/marca/getListTipoVehiculo', 'autos\TipoVehiculoController@selectTipoVehiculo');
    Route::post('/modelo/registrar', 'autos\ModeloController@store');
    Route::put('/modelo/actualizar', 'autos\ModeloController@update');
    Route::post('/modelo/estado', 'autos\ModeloController@cambiarEstadoModelo'); //


    Route::get('/cliente', 'cotizacion\ClienteController@index');
    Route::post('/cliente/registrar', 'cotizacion\ClienteController@store');
    Route::post('/cliente/actualizar', 'cotizacion\ClienteController@update');
    Route::post('/cliente/delete', 'cotizacion\ClienteController@destroy');

    Route::get('/code', 'seguro\CoberturaDeducibleController@index');
    Route::post('/code/registrar', 'seguro\CoberturaDeducibleController@store');
    Route::put('/code/actualizar', 'seguro\CoberturaDeducibleController@update');
    Route::post('/code/estado', 'seguro\CoberturaDeducibleController@cambiarEstadoCode'); //

    Route::get('/clasificacion', 'autos\ClasificacionController@index');
    Route::post('/clasificacion/registrar', 'autos\ClasificacionController@store');
    Route::put('/clasificacion/actualizar', 'autos\ClasificacionController@update');
    Route::post('/clasificacion/estado', 'autos\ClasificacionController@cambiarEstadoCategoria'); //

    Route::get('/cotizacion/getlistcliente', 'cotizacion\ClienteController@selectCliente');
    Route::get('/cotizacion/getlistvehiculo', 'autos\ModeloController@selecVehiculo');
    Route::get('/cotizacion/getlistclasificacion', 'autos\ClasificacionController@selectClasificacion');
    Route::get('/cotizacion/getlisttipouso', 'autos\TipoUsoController@selecttipouso');
    Route::get('/cotizacion/getlisttiposeguro', 'seguro\TipoSeguroController@selectSeguro');
    Route::post('/cotizacion/getlisttasas', 'seguro\TipoSeguroController@selectTasas');
    Route::post('/cotizacion/getlistSeguroTotal', 'seguro\TipoSeguroController@obtenerseguroById');
    Route::post('/cotizacion/registrar', 'cotizacion\CotizacionController@store');
    Route::get('/cotizacion', 'cotizacion\CotizacionController@index');
    Route::get('/cotizacion/generarPDFCotizacion/{id}', 'cotizacion\CotizacionController@generarPDFCotizacion')->name('cotizacion_pdf');
    Route::post('/cotizacion/viewdetalle', 'cotizacion\CotizacionController@obtenerCotizacion');
    Route::post('/cotizacion/editardetalle', 'cotizacion\CotizacionController@cargarDatos');
    Route::post('/cotizacion/actualizar', 'cotizacion\CotizacionController@update');
    Route::post('/cotizacion/seguimiento', 'cotizacion\CotizacionController@seguimiento_Detalle');
    Route::post('/seguimiento/registrar', 'cotizacion\CotizacionController@storeFile');

    Route::post('/pagos/getListarUsuariosPagos', 'Administracion\UsersController@getListarEmpleadoPago');
    Route::post('/pagos/obtenercomision', 'PagoEmpleadoController@comision');
    Route::post('/pagos/registrar', 'PagoEmpleadoController@store');
    Route::get('/pagos', 'PagoEmpleadoController@index');

    Route::get('/dashboard/mes/anio', 'DashboardController@dashboard');
    Route::get('/dashboard/aasesor/venta', 'DashboardController@asesor_venta');
    Route::get('/dashboard/aasesor/comision', 'DashboardController@asesor_comision');
    Route::get('/dashboard/aasesor/by', 'DashboardController@asesor_vendido_by');
    Route::post('/dashboard/user', 'DashboardController@asesor_by');

    Route::post('/notificar/user', 'NotificacionController@index');


});



Route::get('/{optional?}', function () {
   // $configuracion = Configuracion::select()->get();
    //return view('principal')->with('config', $configuracion);
    return view('principal');
})->name('basepath')
    ->where('optional', '.*');


///Cotizacio, si hay una cotizacion seleccionada, entonces bloquear.