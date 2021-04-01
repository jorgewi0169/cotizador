<?php

namespace App\Http\Controllers\seguro;

use App\TipoSeguro;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FilesController;
use App\SegurocoberturaDeducible;
use App\TasaSeguro;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TipoSeguroController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $criterio = $request->cCriterio; //Criterio de busqueda
        $buscar = $request->cBusqueda; //palabra a buscar

        $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;
        $criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'nombre') :   $criterio;

        $seguro = TipoSeguro::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate($request->pag_mostrar);

        return [
            'pagination' => [
                'total'        => $seguro->total(), //Total de registros
                'current_page' => $seguro->currentPage(), //Pagina actual
                'per_page'     => $seguro->perPage(), //Registros por pagina
                'last_page'    => $seguro->lastPage(), //Ultima pagina
                'from'         => $seguro->firstItem(), //Primera
                'to'           => $seguro->lastItem(), // ultima pagina
            ],
            'seguro' => $seguro
        ];
    }

    public function store(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        // return response()->json(['data'=>$request->all()]);

        $validatedData  = $request->validate(
            [
                'nombre' => 'required',
            ],
            [
                'nombre.required' => 'El campo Nombre del Seguro es obligatorio',

            ]
        );

        if ($validatedData) {
            // return response()->json(['data'=>$request->all()]);
            try {

                DB::beginTransaction();

                $seguro = new TipoSeguro();
                $seguro->nombre                             = $request->nombre;
                $seguro->logo                               = FilesController::setProcesarFileSeguro($request->file('logo'));
                $seguro->desgravamen                        = $request->concesionario;
                $seguro->cero_deducible                     = $request->deducible; //
                $seguro->amparo_patrimonial                 = $request->patrimonial; //
                $seguro->auto_auto                          = $request->adicional;
                $seguro->dispositivo_rastreo                = $request->dispositivo_rastreo;
                $seguro->created_by                         = Auth::user()->id;
                $seguro->updated_by                         = 0;
                $seguro->save();

                $tasa = json_decode($request->tasas, true); // mi array de tasas

                foreach ($tasa as $key => $value) {
                    $t = new TasaSeguro();
                    $t->tasa        = $value['tasa'];
                    $t->idseguro    = $seguro->id;
                    $t->save();
                }

                /*Cobertura deduible */

                $cobertura_deducible = json_decode($request->cobertura_deducible, true); // mi array de tasas

                foreach ($cobertura_deducible as $key => $value) {
                    $t = new SegurocoberturaDeducible();
                    $t->idseguro        = $seguro->id;
                    $t->iddeco          = $value['id'];
                    $t->aplica          = ($value['aplica'] == true) ? 'SI' : 'NO';
                    $t->monto           = $value['monto'];
                    $t->descripcion     = $value['descripcion'];
                    $t->save();
                    //$criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'nombre') :   $criterio;
                }

                DB::commit();
                return response()->json(['status' => true]);
            } catch (Exception $e) {

                DB::rollback();
                return response()->json(['status' => false, 'mensaje' => $e->getMessage()]);
            }
        } else {
            return response()->json([$validatedData]);
        }
    }

    public function update(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $validatedData  = $request->validate(
            [
                'nombre' => 'required',
            ],
            [
                'nombre.required' => 'El campo Nombre del Seguro es obligatorio',

            ]
        );

        if ($validatedData) {
            try {

                DB::beginTransaction();

                //$seguro = new TipoSeguro();
                $seguro = TipoSeguro::findOrFail($request->id);
                $seguro->nombre                             = $request->nombre;
                $seguro->logo                               = (!is_string($request->logo)) ? (FilesController::setProcesarFileSeguro($request->file('logo'), $seguro->logo)) : $request->logo; //FilesController::setProcesarFileSeguro($request->file('logo'));
                $seguro->desgravamen                        = $request->concesionario;
                $seguro->cero_deducible                     = $request->deducible;
                $seguro->amparo_patrimonial                 = $request->patrimonial;
                $seguro->auto_auto                          = $request->adicional;
                $seguro->dispositivo_rastreo                = $request->dispositivo_rastreo;
                $seguro->created_by                         = Auth::user()->id;
                $seguro->updated_by                         = 0;
                $seguro->save();

                $tasa = json_decode($request->tasas, true); // mi array de tasas
                TasaSeguro::where('idseguro', '=', $request->id)->delete();

                foreach ($tasa as $key => $value) {
                    $t = new TasaSeguro();
                    $t->tasa        = $value['tasa'];
                    $t->idseguro    = $seguro->id;
                    $t->save();
                }

                /*Cobertura deduible */
                SegurocoberturaDeducible::where('idseguro', '=', $request->id)->delete(); //ServicioDetalleCompra::where('idservicio', '=', $request->id)->delete();
                $cobertura_deducible = json_decode($request->cobertura_deducible, true); // mi array de tasas

                foreach ($cobertura_deducible as $key => $value) {
                    $t = new SegurocoberturaDeducible();
                    $t->idseguro        = $seguro->id;
                    $t->iddeco          = $value['id'];
                    $t->aplica          = ($value['aplica'] == true) ? 'SI' : 'NO';
                    $t->monto           = $value['monto'];
                    $t->descripcion     = $value['descripcion'];
                    $t->save();
                    //$criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'nombre') :   $criterio;
                }

                DB::commit();
                return response()->json(['status' => true]);
            } catch (Exception $e) {

                DB::rollback();
                return response()->json(['status' => false]);
            }
        } else {
            return response()->json([$validatedData]);
        }
    }

    public function state(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $seguro = TipoSeguro::findOrFail($request->id);
        $seguro->state = $request->estado;
        $seguro->save();
        return response()->json(['status' => true]);
    }

    public function destroy(Request $request)
    {

        if (!$request->ajax()) return redirect('/');
        // $existe = DB::table('articulos as a')->join('categorias as c', 'a.idcategoria', 'c.id')->where('c.id', '=', $request->id)->count();

        // if ($existe > 0) {
        //  return response()->json(['status' => false]);
        //  } else {

        $imagen = $request->img;
        $valor = Storage::disk('public')->delete("seguro/$imagen");

        if ($valor) {

            $documento = TipoSeguro::find($request->id);
            $documento->delete();
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
        //}
    }

    public function obtenerseguro(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $scd = DB::table('seguro_cobertura_deducible AS scd') //array que recorremos
            ->select(
                'cd.id',
                'cd.nombre',
                'cd.tipo',
                'cd.tipo_variable',
                'scd.aplica',
                'scd.monto',
                'scd.descripcion'
            )->join('cobertura_deducible as cd', 'scd.iddeco', '=', 'cd.id')->where('scd.idseguro', '=', $request->id)->orderBy('cd.id', 'asc')->get();



        $seguro = TipoSeguro::select()->where('id', '=',  $request->id)->get();
        $tasa_seguro = TasaSeguro::select()->where('idseguro', '=',  $request->id)->get();

        return response()->json([

            'scd' => $scd,
            'seguro' => $seguro,
            'tasa_seguro' => $tasa_seguro,
        ]);
    }

    public function selectSeguro(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $tiposeguro = Tiposeguro::select()
            ->where('state', '=', 'A')
            ->where('nombre', 'like', '%' . $filtro . '%')
            ->orderBy('nombre', 'asc')->get();

        return $tiposeguro;
    }


    public function selectTasas(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $tasas = TasaSeguro::select()
            ->where('idseguro', '=', $request->id)
            ->orderBy('id', 'asc')->get();

        return $tasas;
    }

    public function obtenerseguroById(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $covertura = DB::table('seguro_cobertura_deducible AS scd') //array que recorremos
            ->select(
                'cd.id',
                'cd.nombre',
                'cd.tipo',
                'cd.tipo_variable',
                'scd.aplica',
                'scd.monto',
                'scd.descripcion'
            )->join('cobertura_deducible as cd', 'scd.iddeco', '=', 'cd.id')
            ->where('cd.tipo', '=', 'COBERTURA')
            ->where('scd.idseguro', '=', $request->id)->orderBy('cd.id', 'asc')->get();

        $deducible = DB::table('seguro_cobertura_deducible AS scd') //array que recorremos
            ->select(
                'cd.id',
                'cd.nombre',
                'cd.tipo',
                'cd.tipo_variable',
                'scd.aplica',
                'scd.monto',
                'scd.descripcion'
            )->join('cobertura_deducible as cd', 'scd.iddeco', '=', 'cd.id')
            ->where('cd.tipo', '=', 'DEDUCIBLE')
            ->where('scd.idseguro', '=', $request->id)->orderBy('cd.id', 'asc')->get();

        $tasa = DB::table('tasa_seguro')->select('tasa')->where('id', '=', $request->idTasaSeguro)->get();
        $seguro = TipoSeguro::select()->where('id', '=',  $request->id)->get();
        //$tasa_seguro = TasaSeguro::select()->where('idseguro', '=',  $request->id)->get();

        return response()->json([

            'deducible' => $deducible,
            'covertura' => $covertura,
            'seguro' => $seguro[0],
            'tasa' => $tasa,

        ]);
    }
}
