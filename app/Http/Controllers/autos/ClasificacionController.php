<?php

namespace App\Http\Controllers\autos;

use App\Clasificacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClasificacionController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $criterio = $request->cCriterio; //Criterio de busqueda
        $buscar = $request->cBusqueda; //palabra a buscar

        $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;
        $criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'nombre') :   $criterio;


        $clasificacion = Clasificacion::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate($request->pag_mostrar);


        //$clasificacion = Categoria::paginate(2);
        return [
            'pagination' => [
                'total'        => $clasificacion->total(), //Total de registros
                'current_page' => $clasificacion->currentPage(), //Pagina actual
                'per_page'     => $clasificacion->perPage(), //Registros por pagina
                'last_page'    => $clasificacion->lastPage(), //Ultima pagina
                'from'         => $clasificacion->firstItem(), //Primera
                'to'           => $clasificacion->lastItem(), // ultima pagina
            ],
            'clasificacion' => $clasificacion
        ];
    }

    public function store(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([
            'nombre' => 'required|unique:clasificacion,nombre'
        ]);

        if ($validatedData) {

            $Clasificacion = new Clasificacion();
            $Clasificacion->nombre = $request->nombre;
            $Clasificacion->created_by    = Auth::id();
            $Clasificacion->updated_by    = 0;
            $Clasificacion->save(); //Insert

            return response()->json(['status' => true]);
        } else {

            return response()->json([$validatedData]);
        };
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([
            'nombre' => 'required|max:20'
        ]);

        if ($validatedData) {
            $Clasificacion = Clasificacion::findOrFail($request->id);
            $Clasificacion->nombre = $request->nombre;
            $Clasificacion->updated_by    = Auth::id();
            $Clasificacion->save();

            return response()->json(['status' => true]);
        } else {

            return response()->json([$validatedData]);
        };
    }

    public function cambiarEstadoCategoria(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $clasificacion = Clasificacion::findOrFail($request->id);
        $clasificacion->state = $request->estado;
        $clasificacion->save();
        return response()->json(['status' => true]);
    }
    //Select CATEGORIA
    public function selectClasificacion(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $clasificacion = Clasificacion::where('state', '=', 'A')
            ->select('id', 'nombre')->orderBy('nombre', 'asc')->get();
        return  $clasificacion;
    }
}
