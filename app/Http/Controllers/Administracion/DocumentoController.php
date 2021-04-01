<?php

namespace App\Http\Controllers\Administracion;

use App\Documento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentoController extends Controller
{

    //Lista los documento en la vista usuario
    public function getListarDocumento(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $documento = Documento::select('id', 'tipo_doc')->get();
        return response()->json(['documento' => $documento]);
    }

    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $criterio = $request->cCriterio; //Criterio de busqueda
        $buscar = $request->cBusqueda; //palabra a buscar

        $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;
        $criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'tipo_doc') :   $criterio;

        $documento = Documento::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate($request->pag_mostrar);

        return [
            'pagination' => [
                'total'        => $documento->total(), //Total de registros
                'current_page' => $documento->currentPage(), //Pagina actual
                'per_page'     => $documento->perPage(), //Registros por pagina
                'last_page'    => $documento->lastPage(), //Ultima pagina
                'from'         => $documento->firstItem(), //Primera
                'to'           => $documento->lastItem(), // ultima pagina
            ],
            'documento' => $documento
        ];
    }

    public function store(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([
            'tipo_doc' => 'required|unique:documento,tipo_doc'
        ], [
            'tipo_doc.required' => 'El campo Documento es obligatorio'
        ]);

        if ($validatedData) {

            $documentos = new Documento();
            $documentos->tipo_doc = $request->tipo_doc;
            $documentos->save(); //Insert

            return response()->json(['status' => true]);
        } else {

            return response()->json([$validatedData]);
        };
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([
            'tipo_doc' => 'required'
        ], [
            'tipo_doc.required' => 'El campo Documento es obligatorio'
        ]);

        if ($validatedData) {
            $documento = Documento::findOrFail($request->id);
            $documento->tipo_doc = $request->tipo_doc;
            $documento->save();

            return response()->json(['status' => true]);
        } else {

            return response()->json([$validatedData]);
        };
    }
    public function destroy(Request $request)
    {
        $existe = DB::table('users as u')->join('documento as d', 'u.iddocumento', 'd.id')->where('d.id', '=', $request->id)->count();

        if ($existe > 0) {
            return response()->json(['status' => false]);
        } else {
            $documento = Documento::find($request->id);
            $documento->delete();
            return response()->json(['status' => true]);
        }
    }
}
