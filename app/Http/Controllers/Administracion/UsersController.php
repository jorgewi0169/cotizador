<?php

namespace App\Http\Controllers\Administracion;


use App\Http\Controllers\Controller;
use App\Http\Controllers\FilesController;
use App\Rol;
use App\User;
use App\Users_permissions;
use App\Users_roles;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function getListarUsuarios(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $criterio = $request->cCriterio; //Criterio de busqueda
        $buscar = $request->cBusqueda; //palabra a buscar
        //si la variable buscar es nula entonces le asigamos un valor vacio.
        $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;
        //si criterio es nulo entonces criterio seria igual al username
        $criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'u.username') :   $criterio;

        if ($criterio == "nombre") {

            $criterio =  DB::raw("CONCAT_WS(' ', u.firstname,  u.lastname)");
        }

        $user = DB::table('users as u')
            ->select(
                'u.id',
                'u.file',
                DB::raw("CONCAT_WS(' ', u.firstname,  u.lastname) as fullname"),
                DB::raw("CASE	IFNULL(u.state, '')	WHEN	'A'	THEN	'ACTIVO' ELSE	'INACTIVO' END	state_alias"),
                'u.username',
                'u.email',
                'u.numdoc',
                'u.telefono',
                'd.tipo_doc',
                'r.name as rol',
                'r.id as idrol'
            )
            ->join('documento as d', 'u.iddocumento', '=', 'd.id')
            ->join('users_roles as ur', 'u.id', '=', 'ur.user_id')
            ->join('roles as r', 'ur.role_id', '=', 'r.id')
            ->where($criterio, 'like', '%' . $buscar . '%')
            ->orderBy('u.id', 'desc')->paginate($request->pag_mostrar);

        return [
            'pagination' => [
                'total'        => $user->total(),
                'current_page' => $user->currentPage(),
                'per_page'     => $user->perPage(),
                'last_page'    => $user->lastPage(),
                'from'         => $user->firstItem(),
                'to'           => $user->lastItem(),
            ],
            'user' => $user
        ];
    }


    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/'); //Peticio por ajax
        //Validaciones
        $validatedData = $request->validate([
            'Nombre' => 'required',
            'Apellido' => 'required',
            'Usuario' => 'required|unique:users,username',
            'cContrasena' => 'required',
            'cNumDoc' => 'required',
            'telefono' => 'required',
            'Correo' => 'regex:/(.+)@(.+)\.(.+)/i|unique:users,email',
            'idRol'  => 'required|integer|min:1',
        ], [
            'cContrasena.required' => "El campo Contraseña es obligatorio",
            'cNumDoc.required' => "El campo Numero de documento es obligatorio",
            'idRol.min' => "Seleccione un Rol"
        ]);

        if ($validatedData) {

            try {
                DB::beginTransaction();
                $user = new User();

                $user->firstname     = $request->Nombre;
                $user->lastname      = $request->Apellido;
                $user->username      = $request->Usuario;
                $user->email         = ($request->Correo == NULL) ? ($request->Correo = '') : $request->Correo;
                $user->file          =  FilesController::setProcesarArchivo($request->file('cFotografia'));
                $user->state         = $request->cState;
                $user->iddocumento   = $request->eIdDocumento;
                $user->numdoc        = $request->cNumDoc;
                $user->telefono      = $request->telefono;
                $user->created_by    = Auth::id();
                $user->updated_by    = 0;
                $user->password      = Hash::make($request->cContrasena);
                $user->save();

                $ru = new Users_roles();
                $ru->user_id   = $user->id;
                $ru->role_id   = $request->idRol;
                $ru->save();



                DB::commit();
                return response()->json(['status' => true]);
            } catch (Exception $e) {

                return response()->json(['status' => false]);
                DB::rollback();
            }
        } else {

            return response()->json([$validatedData]);
        };
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([
            'Nombre' => 'required',
            'Apellido' => 'required',
            'Usuario' => 'required',
            'cNumDoc' => 'required',
            'telefono' => 'required',
            'Correo' => 'regex:/(.+)@(.+)\.(.+)/i',
            'idRol'  => 'required|integer|min:1',
        ], [
            'cNumDoc.required' => "El campo Numero de documento es obligatorio",
            'idRol.min' => "Seleccione un Rol"
        ]);

        if ($validatedData) {

            try {

                DB::beginTransaction();
                $user =  User::findOrFail($request->id);
                //si la la variable de contraseña no viene vacia, entonces la agregamos para guardar
                if (!empty($request->cContrasena)) {
                    $user->password      = Hash::make($request->cContrasena);
                }

                $user->firstname     = $request->Nombre;
                $user->lastname      = $request->Apellido;
                $user->username      = $request->Usuario;
                $user->email         = ($request->Correo == NULL) ? ($request->Correo = '') : $request->Correo;
                $user->file          = (!is_string($request->cFotografia)) ? (FilesController::setProcesarArchivo($request->file('cFotografia'), $user->file)) : $request->cFotografia;
                $user->state         = $request->cState;
                $user->iddocumento   = $request->eIdDocumento;
                $user->numdoc        = $request->cNumDoc;
                $user->telefono      = $request->telefono;
                $user->updated_by    = Auth::id();
                $user->save();

                $ru = Users_roles::where('user_id', $user->id)
                    ->update(['role_id' => $request->idRol]);


                DB::commit();
                return response()->json(['status' => true]);
            } catch (Exception $e) {

                return response()->json(['status' => false]);
                DB::rollback();
            }
        } else {

            return response()->json([$validatedData]);
        };
    }

    public function obtenerUser(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $user = User::where('id', $id)->take(1)->get();
        $rol = Users_roles::where('user_id', $id)->take(1)->get();

        return response()->json(['user' => $user, 'rol' => $rol]);
    }

    public function cambiarEstadoUser(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $user = User::findOrFail($request->id);
        $user->state = $request->estado;
        $user->save();
        return response()->json(['status' => true]);
    }

    public function registrarPermisosUser(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        try {
            //Iniciamos la transaccion
            DB::beginTransaction();


            Users_permissions::where('user_id', '=', $request->id)->delete();

            $listPermisos  = $request->listPermisosFilter;
            $listPermisosSize   =   sizeof($listPermisos);

            if ($listPermisosSize > 0) {

                foreach ($listPermisos as $key => $value) {
                    if ($value['checked'] == true) {

                        $user_permiso                = new Users_permissions(); //$s->id;
                        $user_permiso->user_id       = $request->id;
                        $user_permiso->permission_id = $value['id'];
                        $user_permiso->save();
                    }
                }
            } else {
                return response()->json(['status' => false]);
            }

            DB::commit();
            return response()->json(['status' => true]);
        } catch (Exception $e) {
            return response()->json(['status' => false]);
            DB::rollback();
        }
    }

    public function getListarRolPermisosByUsuario(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;

        if (!$id) {
            $id = Auth::id();
        }


        $permisos = DB::table('permissions as pe')->select('pe.id', 'pe.name', 'pe.slug')
            ->join('users_permissions as up', function ($join) use ($id) {
                $join->on('pe.id', '=', 'up.permission_id')->where('up.user_id', '=', $id);
            });

        $user_roles = DB::table('users_roles as ur')->select('p.id', 'p.name', 'p.slug')
            ->join('roles_permissions as rp', 'ur.role_id', 'rp.role_id')
            ->join('permissions as p', 'rp.permission_id', 'p.id')
            ->where('ur.user_id', '=', $id)
            ->union($permisos)
            ->get();

        /*  $respuesta = DB::select('SELECT	permiso.id,
                  permiso.name,
                  permiso.slug
            FROM		permissions	permiso
                  INNER JOIN	users_permissions	user_permiso	ON	permiso.id	=	user_permiso.permission_id
                                                                     AND	user_permiso.user_id	=	?

            UNION

            SELECT	p.id,
                  p.name,
                  p.slug
            FROM		users_roles	ur
                  INNER	JOIN	roles_permissions	rp	ON	ur.role_id			=	rp.role_id
                  INNER	JOIN	permissions		p		ON	rp.permission_id	=	p.id
            WHERE		ur.user_id	=	?;', [$id,$id]); */

        return $user_roles;
    }


    public function getListarEmpleadoPago(Request $request)
    {
        if (!$request->ajax()) return Redirect('/');

        $filtro = $request->filtro;
        $empleado = User::select(
            DB::raw("CONCAT_WS(' - ',CONCAT_WS(' ', users.firstname,  users.lastname), numdoc) as empleado"),
            'users.id',
            'users.numdoc'
        )
            ->join('users_roles as ur', 'users.id', '=', 'ur.user_id')
            ->join('roles as r', 'ur.role_id', '=', 'r.id')
            ->where(DB::raw("CONCAT_WS(' ', users.firstname,  users.lastname)"), 'like', '%' . $filtro . '%')
            ->orWhere('users.numdoc', 'like', '%' . $filtro . '%')
            ->orderBy('users.lastname', 'asc')->get();

        return $empleado;
    }
}
