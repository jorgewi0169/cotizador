<?php

namespace App\Http\Controllers\autos;

use App\Http\Controllers\Controller;
use App\TipoVehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipoVehiculoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $criterio = $request->cCriterio; //Criterio de busqueda
        $buscar = $request->cBusqueda; //palabra a buscar

        $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;
        $criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'nombre') :   $criterio;


        $tipovehiculos = TipoVehiculo::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate($request->pag_mostrar);


        //$tipovehiculos = marca::paginate(2);
        return [
            'pagination' => [
                'total'        => $tipovehiculos->total(), //Total de registros
                'current_page' => $tipovehiculos->currentPage(), //Pagina actual
                'per_page'     => $tipovehiculos->perPage(), //Registros por pagina
                'last_page'    => $tipovehiculos->lastPage(), //Ultima pagina
                'from'         => $tipovehiculos->firstItem(), //Primera
                'to'           => $tipovehiculos->lastItem(), // ultima pagina
            ],
            'tipovehiculos' => $tipovehiculos
        ];
    }

    public function store(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([
            'nombre' => 'required|unique:tipo_vehiculo,nombre'
        ]);

        if ($validatedData) {

            $tipovehiculo = new TipoVehiculo();
            $tipovehiculo->nombre = $request->nombre;
            $tipovehiculo->created_by    = Auth::id();
            $tipovehiculo->updated_by    = 0;
            $tipovehiculo->save(); //Insert

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
            $tipovehiculo = TipoVehiculo::findOrFail($request->id);
            $tipovehiculo->nombre = $request->nombre;
            $tipovehiculo->updated_by    = Auth::id();
            $tipovehiculo->save();

            return response()->json(['status' => $tipovehiculo]);
        } else {

            return response()->json([$validatedData]);
        };
    }

    public function cambiarEstadoTipoVehiculo(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $tipovehiculo = TipoVehiculo::findOrFail($request->id);
        $tipovehiculo->state = $request->estado;
        $tipovehiculo->save();
        return response()->json(['status' => true]);
    }
    //Select marca
    public function selectTipoVehiculo(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $marcas = TipoVehiculo::where('state', '=', 'A')
            ->select('id', 'nombre')->orderBy('nombre', 'asc')->get();
        return  $marcas;
    }
}
