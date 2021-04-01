<?php

namespace App\Http\Controllers\seguro;

use App\CoberturaDeducible;
use App\Http\Controllers\Controller;
use App\SegurocoberturaDeducible;
use App\TipoSeguro;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoberturaDeducibleController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $criterio = $request->cCriterio; //Criterio de busqueda
        $buscar = $request->cBusqueda; //palabra a buscar

        $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;
        $criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'nombre') :   $criterio;


        $codes = CoberturaDeducible::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate($request->pag_mostrar);


        //$codes = code::paginate(2);
        return [
            'pagination' => [
                'total'        => $codes->total(), //Total de registros
                'current_page' => $codes->currentPage(), //Pagina actual
                'per_page'     => $codes->perPage(), //Registros por pagina
                'last_page'    => $codes->lastPage(), //Ultima pagina
                'from'         => $codes->firstItem(), //Primera
                'to'           => $codes->lastItem(), // ultima pagina
            ],
            'codes' => $codes
        ];
    }

    public function store(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([
            'nombre' => 'required|unique:cobertura_deducible,nombre',
            'tipo' => 'required',
            'tipovariable' => 'required'
        ]);

        if ($validatedData) {

            try {
                DB::beginTransaction();
                $code = new CoberturaDeducible();
                $code->nombre = $request->nombre;
                $code->tipo = $request->tipo;
                $code->tipo_variable = $request->tipovariable;
                $code->created_by    = Auth::id();
                $code->updated_by    = 0;
                $code->save(); //Insert

                $seguros = TipoSeguro::select('id')->get();

                foreach ($seguros as $key => $value) {

                    $s = new SegurocoberturaDeducible();
                    $s->iddeco  = $code->id;
                    $s->idseguro  = $value->id;
                    $s->aplica  = 'NO';
                    $s->monto  = 0;
                    $s->descripcion  = '';
                    $s->save();
                }

                DB::commit();
                return response()->json(['status' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['status' => false]);
            }
        } else {

            return response()->json([$validatedData]);
        };
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([
            'nombre' => 'required',
            'tipo' => 'required',
            'tipovariable' => 'required'
        ]);

        if ($validatedData) {
            $code = CoberturaDeducible::findOrFail($request->id);
            $code->nombre = $request->nombre;
            $code->tipo = $request->tipo;
            $code->tipo_variable = $request->tipovariable;
            $code->updated_by    = Auth::id();
            $code->save();

            return response()->json(['status' => $code]);
        } else {

            return response()->json([$validatedData]);
        };
    }

    public function cambiarEstadoCode(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $user = CoberturaDeducible::findOrFail($request->id);
        $user->state = $request->estado;
        $user->save();
        return response()->json(['status' => true]);
    }
    //Select code
    public function selectCode(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $cobertura = CoberturaDeducible::where('state', '=', 'A')->where('tipo', '=', 'COBERTURA')
            ->select('id', 'nombre', 'tipo', 'tipo_variable')->orderBy('id', 'asc')->get();

        $deducible = CoberturaDeducible::where('state', '=', 'A')->where('tipo', '=', 'DEDUCIBLE')
            ->select('id', 'nombre', 'tipo', 'tipo_variable')->orderBy('id', 'asc')->get();

        return  response()->json(['cobertura' => $cobertura, 'deducible' => $deducible]);
    }
}
