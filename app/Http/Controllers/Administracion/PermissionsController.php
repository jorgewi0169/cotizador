<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionsController extends Controller
{
    public function getListarPermisos(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $criterio = $request->cCriterio; //Criterio de busqueda 
        $buscar = $request->cBusqueda; //palabra a buscar
        //si la variable buscar es nula entonces le asigamos un valor vacio.
        $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;
        //si criterio es nulo entonces criterio seria igual al username
        $criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'name') :   $criterio;

        $permiso = Permissions::where($criterio, 'like', '%' . $buscar . '%')
            ->orderBy('id', 'desc')->paginate($request->pag_mostrar);

        return [
            'pagination' => [
                'total'        => $permiso->total(),
                'current_page' => $permiso->currentPage(),
                'per_page'     => $permiso->perPage(),
                'last_page'    => $permiso->lastPage(),
                'from'         => $permiso->firstItem(),
                'to'           => $permiso->lastItem(),
            ],
            'permiso' => $permiso
        ];
    }

    public function store(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $validate = $request->validate([
            'Nombre' => 'required',
            'cSlug' => 'required'
        ], [
            'Nombre.required' => 'El campo nombre del permiso es obligatorio',
            'cSlug.required' => 'El campo Url amigable es obligatorio'
        ]);

        if ($validate) {

            $rol = new Permissions();

            $rol->name  = $request->Nombre;
            $rol->slug  = $request->cSlug;
            $rol->save();
            return response()->json(['status' => true]);
        } else {

            return response()->json([$validate]);
        };
    }

    public function update(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $validate = $request->validate([
            'Nombre' => 'required',
            'cSlug' => 'required',
            'id'    => 'required'
        ], [
            'Nombre.required' => 'El campo nombre del permiso es obligatorio',
            'cSlug.required' => 'El campo Url amigable es obligatorio',
            'id.required'    => 'Seleccione un permiso'
        ]);

        if ($validate) {

            $permiso = Permissions::findOrFail($request->id);

            $permiso->name  = $request->Nombre;
            $permiso->slug  = $request->cSlug;
            $permiso->save();
            return response()->json(['status' => true]);
        } else {

            return response()->json([$validate]);
        };
    }

   
}
