<?php

namespace App\Http\Controllers\seguro;

use App\Http\Controllers\Controller;
use App\RamoSeguro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RamoSeguroController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $criterio = $request->cCriterio; //Criterio de busqueda
        $buscar = $request->cBusqueda; //palabra a buscar

        $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;
        $criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'nombre') :   $criterio;


        $ramoSeguros = RamoSeguro::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate($request->pag_mostrar);


        //$ramoSeguros = Categoria::paginate(2);
        return [
            'pagination' => [
                'total'        => $ramoSeguros->total(), //Total de registros
                'current_page' => $ramoSeguros->currentPage(), //Pagina actual
                'per_page'     => $ramoSeguros->perPage(), //Registros por pagina
                'last_page'    => $ramoSeguros->lastPage(), //Ultima pagina
                'from'         => $ramoSeguros->firstItem(), //Primera
                'to'           => $ramoSeguros->lastItem(), // ultima pagina
            ],
            'ramoSeguros' => $ramoSeguros
        ];
    }

    public function store(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([
            'nombre' => 'required|unique:ramo_seguro,nombre'
        ]);

        if ($validatedData) {

            $ramoSeguro = new RamoSeguro();
            $ramoSeguro->nombre = $request->nombre;
            $ramoSeguro->created_by    = Auth::id();
            $ramoSeguro->updated_by    = 0;
            $ramoSeguro->save(); //Insert

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
            $ramoSeguro = RamoSeguro::findOrFail($request->id);
            $ramoSeguro->nombre = $request->nombre;
            $ramoSeguro->updated_by    = Auth::id();
            $ramoSeguro->save();

            return response()->json(['status' => $ramoSeguro]);
        } else {

            return response()->json([$validatedData]);
        };
    }

    public function cambiarEstadoRamoSeguro(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $user = RamoSeguro::findOrFail($request->id);
        $user->state = $request->estado;
        $user->save();
        return response()->json(['status' => true]);
    }
    //Select CATEGORIA
    public function selectRamoSeguro(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $ramoSeguros = RamoSeguro::where('state', '=', 'A')
            ->select('id', 'nombre')->orderBy('nombre', 'asc')->get();
        return  $ramoSeguros;
    }
}
