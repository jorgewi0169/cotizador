<?php

namespace App\Http\Controllers\autos;

use App\Http\Controllers\Controller;
use App\modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ModeloController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $criterio = $request->cCriterio; //Criterio de busqueda
        $buscar = $request->cBusqueda; //palabra a buscar

        $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;
        $criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'm.nombre') :   $criterio;

        switch ($criterio) {
            case 'modelo':
                $criterio = 'm.nombre';
                break;
            case 'categoria':
                $criterio = 'c.nombre';
                break;
            case 'marca':
                $criterio = 'mc.nombre';
                break;
            case 'tipo_vehiculo':
                $criterio = 'tv.nombre';
                break;
            case 'año':
                $criterio = 'm.año';
                break;
        }


        $modelos = DB::table('modelo AS m')->select(
            'c.nombre as categoria',
            'mc.nombre as marca',
            'tv.nombre as tipo_vechiculo',
            'm.nombre',
            'm.año',
            'm.idcategoria',
            'm.idmarca',
            'm.idtipovehiculo',
            'm.state',
            'm.valor_mercado',
            'm.id'
        )->join('categoria AS c',  'm.idcategoria', '=', 'c.id')
            ->join('marca AS mc', 'm.idmarca', '=', 'mc.id')
            ->join('tipo_vehiculo AS tv', 'm.idtipovehiculo', '=', 'tv.id')
            ->where($criterio, 'like', '%' . $buscar . '%')
            ->orderBy('m.id', 'desc')->paginate($request->pag_mostrar);

        return [
            'pagination' => [
                'total'        => $modelos->total(), //Total de registros
                'current_page' => $modelos->currentPage(), //Pagina actual
                'per_page'     => $modelos->perPage(), //Registros por pagina
                'last_page'    => $modelos->lastPage(), //Ultima pagina
                'from'         => $modelos->firstItem(), //Primera
                'to'           => $modelos->lastItem(), // ultima pagina
            ],
            'modelos' => $modelos
        ];
    }

    public function store(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([
            'nombre' => 'required',
            'año' => 'required|integer',
            'categoria' => 'required|integer|min:1',
            'marca' => 'required|integer|min:1',
            'tipo_vehiculo' => 'required|integer|min:1',
            'valor_mercado' => 'required',
        ], [
            'categoria.min' => 'Seleccione una categoria valida.',
            'marca.min' => 'Seleccione una narca valida.',
            'tipo_vehiculo.min' => 'Seleccione un tipo de vehiculo valido.',
        ]);

        if ($validatedData) {

            $modelo = new modelo();
            $modelo->nombre             = $request->nombre;
            $modelo->idcategoria        = $request->categoria;
            $modelo->idmarca            = $request->marca;
            $modelo->idtipovehiculo     = $request->tipo_vehiculo;
            $modelo->año                = $request->año;
            $modelo->valor_mercado      = $request->valor_mercado;
            $modelo->created_by         = Auth::id();
            $modelo->updated_by         = 0;
            $modelo->save(); //Insert

            return response()->json(['status' => true]);
        } else {

            return response()->json([$validatedData]);
        };
    }

    public function update(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([
            'nombre' => 'required',
            'año' => 'required|integer',
            'categoria' => 'required|integer|min:1',
            'marca' => 'required|integer|min:1',
            'tipo_vehiculo' => 'required|integer|min:1',
            'valor_mercado' => 'required',
        ], [
            'categoria.min' => 'Seleccione una categoria valida.',
            'marca.min' => 'Seleccione una narca valida.',
            'tipo_vehiculo.min' => 'Seleccione un tipo de vehiculo valido.',
        ]);

        if ($validatedData) {

            //$modelo = new modelo();
            $modelo = modelo::findOrFail($request->id);
            $modelo->nombre             = $request->nombre;
            $modelo->idcategoria        = $request->categoria;
            $modelo->idmarca            = $request->marca;
            $modelo->idtipovehiculo     = $request->tipo_vehiculo;
            $modelo->año                = $request->año;
            $modelo->valor_mercado      = $request->valor_mercado;
            $modelo->updated_by         = Auth::id();
            $modelo->save(); //Insert

            return response()->json(['status' => true]);
        } else {

            return response()->json([$validatedData]);
        };
    }

    public function cambiarEstadoModelo(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $modelo = modelo::findOrFail($request->id);
        $modelo->state = $request->estado;
        $modelo->save();
        return response()->json(['status' => true]);
    }

    public function selecVehiculo(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;

        $marcas = DB::table('modelo as m')->select('m.id', DB::raw("CONCAT_WS(' - ',m.nombre, m.año, mar.nombre) as nombre"))
            ->join('marca as mar', 'm.idmarca', '=', 'mar.id')
            ->where('m.state', '=', 'A')
            ->where('m.nombre', 'like', '%' . $filtro . '%')
            ->orderBy('m.nombre', 'asc')->get();
        return  $marcas;
    }
}
