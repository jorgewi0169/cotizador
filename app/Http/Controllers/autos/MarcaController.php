<?php

namespace App\Http\Controllers\autos;

use App\Http\Controllers\Controller;
use App\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarcaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $criterio = $request->cCriterio; //Criterio de busqueda
        $buscar = $request->cBusqueda; //palabra a buscar

        $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;
        $criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'nombre') :   $criterio;


        $marcas = Marca::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate($request->pag_mostrar);


        //$marcas = marca::paginate(2);
        return [
            'pagination' => [
                'total'        => $marcas->total(), //Total de registros
                'current_page' => $marcas->currentPage(), //Pagina actual
                'per_page'     => $marcas->perPage(), //Registros por pagina
                'last_page'    => $marcas->lastPage(), //Ultima pagina
                'from'         => $marcas->firstItem(), //Primera
                'to'           => $marcas->lastItem(), // ultima pagina
            ],
            'marcas' => $marcas
        ];
    }

    public function store(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([
            'nombre' => 'required|unique:marca,nombre'
        ]);

        if ($validatedData) {

            $marca = new Marca();
            $marca->nombre = $request->nombre;
            $marca->created_by    = Auth::id();
            $marca->updated_by    = 0;
            $marca->save(); //Insert

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
            $marca = Marca::findOrFail($request->id);
            $marca->nombre = $request->nombre;
            $marca->updated_by    = Auth::id();
            $marca->save();

            return response()->json(['status' => $marca]);
        } else {

            return response()->json([$validatedData]);
        };
    }

    public function cambiarEstadoMarca(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $marca = Marca::findOrFail($request->id);
        $marca->state = $request->estado;
        $marca->save();
        return response()->json(['status' => true]);
    }
    //Select marca
    public function selectMarca(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $marcas = Marca::where('state', '=', 'A')
            ->select('id', 'nombre')->orderBy('nombre', 'asc')->get();
        return  $marcas;
    }
}
