<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Rol;
use App\roles_permissions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
   public function getListarRol(Request $request)
   {

      if (!$request->ajax()) return redirect('/');

      $criterio = $request->cCriterio; //Criterio de busqueda 
      $buscar = $request->cBusqueda; //palabra a buscar
      //si la variable buscar es nula entonces le asigamos un valor vacio.
      $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;
      //si criterio es nulo entonces criterio seria igual al username
      $criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'name') :   $criterio;

      $rol = Rol::where($criterio, 'like', '%' . $buscar . '%')
         ->orderBy('id', 'desc')->paginate($request->pag_mostrar);

      return [
         'pagination' => [
            'total'        => $rol->total(),
            'current_page' => $rol->currentPage(),
            'per_page'     => $rol->perPage(),
            'last_page'    => $rol->lastPage(),
            'from'         => $rol->firstItem(),
            'to'           => $rol->lastItem(),
         ],
         'rol' => $rol
      ];
   }

   public function getListarPermisosByRol(Request $request)
   {
      if (!$request->ajax()) return redirect('/');

      $roles = DB::table('permissions as permisos')
         ->select('permisos.id', 'permisos.slug', 'permisos.name')->get();

      return $roles;
   }

   public function store(Request $request)
   {

      if (!$request->ajax()) return redirect('/');

      $validate = $request->validate([
         'Nombre' => 'required',
         'cSlug' => 'required'
      ], [
         'Nombre.required' => 'El campo nombre del rol es obligatorio',
         'cUrl.required' => 'El campo Url amigable es obligatorio'
      ]);

      if ($validate) {

         try {
            //Iniciamos la transaccion
            DB::beginTransaction();

            $rol = new Rol();

            $rol->name  = $request->Nombre;
            $rol->slug  = $request->cSlug;
            $rol->save();

            $listPermisos  = $request->listPermisosFilter;
            $listPermisosSize   =   sizeof($listPermisos);

            if ($listPermisosSize > 0) {

               foreach ($listPermisos as $key => $value) {
                  if ($value['checked'] == true) {

                     $roles_permiso                = new roles_permissions(); //$s->id;
                     $roles_permiso->role_id       = $rol->id;
                     $roles_permiso->permission_id = $value['id'];
                     $roles_permiso->save();
                  }
               }
            }

            DB::commit();
            return response()->json(['status' => true]);
         } catch (Exception $e) {
            return response()->json(['status' => false]);
            DB::rollback();
         }
      }else {

         return response()->json([$validate]);
      };
   }

   public function obtenerRol(Request $request)
   {
      if (!$request->ajax()) return redirect('/');

      $id = $request->id;

      $rol = DB::table("roles as rol")
         ->select('rol.id', 'rol.slug', 'rol.name')
         ->where('id', $id)->take(1)->get();

     
      $permiso = DB::select("SELECT	permiso.id,
                              permiso.slug,
                              permiso.name,
                              CASE	IFNULL(rol_permiso.role_id, '')	WHEN	''	THEN	0
                                                                              ELSE	1
                                                                              END	checked
                        FROM		permissions	permiso
                        LEFT OUTER JOIN	roles_permissions	rol_permiso	ON	permiso.id	=	rol_permiso.permission_id
                                                         AND	rol_permiso.role_id	=	?", [$id]);
                
      return response()->json([
         'rol'     => $rol,
         'permiso' => $permiso,
      ]);
   }

   public function update(Request $request)
   {

      if (!$request->ajax()) return redirect('/');

      $validate = $request->validate([
         'Nombre' => 'required',
         'cSlug' => 'required',
         'id'     => 'required'
      ], [
         'Nombre.required' => 'El campo nombre del rol es obligatorio',
         'cUrl.required' => 'El campo Url amigable es obligatorio',
         'id.required'  => 'Seleccione un Rol'
      ]);

      if ($validate) {

         try {
            //Iniciamos la transaccion
            DB::beginTransaction();

            $rol = Rol::findOrFail($request->id);

            $rol->name  = $request->Nombre;
            $rol->slug  = $request->cSlug;
            $rol->save();

            roles_permissions::where('role_id', '=', $request->id )->delete();

            $listPermisos  = $request->listPermisosFilter;
            $listPermisosSize   =   sizeof($listPermisos);

            if ($listPermisosSize > 0) {

               foreach ($listPermisos as $key => $value) {
                  if ($value['checked'] == true) {

                     $roles_permiso                = new roles_permissions(); //$s->id;
                     $roles_permiso->role_id       = $request->id;
                     $roles_permiso->permission_id = $value['id'];
                     $roles_permiso->save();
                  }
               }
            }

            DB::commit();
            return response()->json(['status' => true]);
         } catch (Exception $e) {
            return response()->json(['status' => false]);
            DB::rollback();
         }
      }else {

         return response()->json([$validate]);
      };
   }

   public function verRol(Request $request)
   {
      if (!$request->ajax()) return redirect('/');

      $id = $request->id;

      $rol = DB::table("roles as rol")
         ->select('rol.id', 'rol.slug', 'rol.name')
         ->where('id', $id)->take(1)->get();
      
      $permiso = DB::table("permissions as p")->select('p.id','p.slug', 'p.name')
                                               ->join('roles_permissions as rp',	'p.id','=',	'rp.permission_id')
                                               ->where('rp.role_id','=',$id)->get();

      return response()->json([
         'rol'     => $rol,
         'permiso' => $permiso,
      ]);
   }

   public function listarPermisosRoles(Request $request){

      if (!$request->ajax()) return redirect('/');

      $id = $request->id;
      $iduser = $request->iduser;

      $permisorol = DB::table("permissions as p")->select('p.id','p.slug', 'p.name')
                                               ->join('roles_permissions as rp',	'p.id','=',	'rp.permission_id')
                                               ->where('rp.role_id','=',$id)->get();
      
      $permisouser = DB::select("SELECT	permiso.id,
                                permiso.slug,
                                permiso.name,
                                CASE	IFNULL(user_permiso.user_id, '')	WHEN	''	THEN	0
                                                                                 ELSE	1
                                                                                 END	checked
                        FROM		permissions	permiso
                        LEFT OUTER JOIN	users_permissions	user_permiso	ON	permiso.id	=	user_permiso.permission_id
                                                                            AND	user_permiso.user_id	= ?", [$iduser]);
      return response()->json([
         'permisorol'     => $permisorol,
         'permisouser' => $permisouser,
      ]);
   }

   public function listarRol(Request $request){

      if(!$request->ajax()) return redirect('/');

      $rol = Rol::select()->get();

      return $rol;
   }
}
