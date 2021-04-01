<?php

namespace App\Http\Controllers\autos;

use App\Categoria;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $criterio = $request->cCriterio; //Criterio de busqueda
        $buscar = $request->cBusqueda; //palabra a buscar

        $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;
        $criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'nombre') :   $criterio;


        $categorias = Categoria::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate($request->pag_mostrar);


        //$categorias = Categoria::paginate(2);
        return [
            'pagination' => [
                'total'        => $categorias->total(), //Total de registros
                'current_page' => $categorias->currentPage(), //Pagina actual
                'per_page'     => $categorias->perPage(), //Registros por pagina
                'last_page'    => $categorias->lastPage(), //Ultima pagina
                'from'         => $categorias->firstItem(), //Primera
                'to'           => $categorias->lastItem(), // ultima pagina
            ],
            'categorias' => $categorias
        ];
    }

    public function store(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([
            'nombre' => 'required|unique:categoria,nombre'
        ]);

        if ($validatedData) {

            $categoria = new Categoria();
            $categoria->nombre = $request->nombre;
            $categoria->created_by    = Auth::id();
            $categoria->updated_by    = 0;
            $categoria->save(); //Insert

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
            $categoria = Categoria::findOrFail($request->id);
            $categoria->nombre = $request->nombre;
            $categoria->updated_by    = Auth::id();
            $categoria->save();

            return response()->json(['status' => $categoria]);
        } else {

            return response()->json([$validatedData]);
        };
    }

    public function cambiarEstadoCategoria(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $user = Categoria::findOrFail($request->id);
        $user->state = $request->estado;
        $user->save();
        return response()->json(['status' => true]);
    }
    //Select CATEGORIA
    public function selectCategoria(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categorias = Categoria::where('state', '=', 'A')
            ->select('id', 'nombre')->orderBy('nombre', 'asc')->get();
        return  $categorias;
    }
}
