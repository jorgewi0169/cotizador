<?php

namespace App\Http\Controllers\autos;

use App\Http\Controllers\Controller;
use App\TipoUso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipoUsoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $criterio = $request->cCriterio; //Criterio de busqueda
        $buscar = $request->cBusqueda; //palabra a buscar

        $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;
        $criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'nombre') :   $criterio;


        $tipousos = TipoUso::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate($request->pag_mostrar);


        //$tipousos = marca::paginate(2);
        return [
            'pagination' => [
                'total'        => $tipousos->total(), //Total de registros
                'current_page' => $tipousos->currentPage(), //Pagina actual
                'per_page'     => $tipousos->perPage(), //Registros por pagina
                'last_page'    => $tipousos->lastPage(), //Ultima pagina
                'from'         => $tipousos->firstItem(), //Primera
                'to'           => $tipousos->lastItem(), // ultima pagina
            ],
            'tipousos' => $tipousos
        ];
    }

    public function store(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([
            'nombre' => 'required|unique:tipo_vehiculo,nombre'
        ]);

        if ($validatedData) {

            $tipouso = new TipoUso();
            $tipouso->nombre = $request->nombre;
            $tipouso->created_by    = Auth::id();
            $tipouso->updated_by    = 0;
            $tipouso->save(); //Insert

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
            $tipouso = TipoUso::findOrFail($request->id);
            $tipouso->nombre = $request->nombre;
            $tipouso->updated_by    = Auth::id();
            $tipouso->save();

            return response()->json(['status' => $tipouso]);
        } else {

            return response()->json([$validatedData]);
        };
    }

    public function cambiarEstadotipouso(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $tipouso = TipoUso::findOrFail($request->id);
        $tipouso->state = $request->estado;
        $tipouso->save();
        return response()->json(['status' => true]);
    }
    //Select marca
    public function selecttipouso(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $uso = TipoUso::where('state', '=', 'A')
            ->select('id', 'nombre')->orderBy('nombre', 'asc')->get();
        return  $uso;
    }
}
