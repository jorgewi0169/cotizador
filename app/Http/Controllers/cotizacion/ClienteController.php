<?php

namespace App\Http\Controllers\cotizacion;

use App\Cliente;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $criterio = $request->cCriterio; //Criterio de busqueda
        $buscar = $request->cBusqueda; //palabra a buscar

        $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;
        $criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'd.nombre') :   $criterio;

        $cliente = DB::table('clientes as p')->select('p.*', 'd.tipo_doc')
            ->join('documento as d', 'p.iddocumento', 'd.id')
            ->where($criterio, 'like', '%' . $buscar . '%')
            ->orderBy('id', 'desc')->paginate($request->pag_mostrar);
        //sleep(4);
        return [
            'pagination' => [
                'total'        => $cliente->total(), //Total de registros
                'current_page' => $cliente->currentPage(), //Pagina actual
                'per_page'     => $cliente->perPage(), //Registros por pagina
                'last_page'    => $cliente->lastPage(), //Ultima pagina
                'from'         => $cliente->firstItem(), //Primera
                'to'           => $cliente->lastItem(), // ultima pagina
            ],
            'cliente' => $cliente
        ];
    }

    public function store(Request $request)
    {

        if (!$request->ajax()) return redirect('/');



        $validatedData = $request->validate([
            'nombre' => 'required',
            'fecha_nacimiento' => 'required',
            'numdoc' => 'required|unique:clientes,numdoc',

        ], [
            'numdoc.required' => 'EL Campo N° Documento es obligatorio',
            'numdoc.unique' => 'EL N° Documento ya está registrado'
        ]);

        if ($validatedData) {

            $cliente = new Cliente();
            $cliente->nombre = $request->nombre;
            $cliente->numdoc = $request->numdoc;
            $cliente->iddocumento = $request->documento;
            $cliente->direccion = $request->direccion;
            $cliente->telefono = $request->telefono;
            $cliente->email = $request->email;
            $cliente->fecha_nacimiento = $request->fecha_nacimiento;
            $cliente->created_by = Auth::user()->id;
            $cliente->updated_by  = 0;

            $cliente->save();

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
            'numdoc' => 'required',

        ], [
            'numdoc.required' => 'EL Campo N° Documento es obligatorio'
        ]);

        if ($validatedData) {

            $cliente = Cliente::findOrFail($request->id);
            $cliente->nombre = $request->nombre;
            $cliente->numdoc = $request->numdoc;
            $cliente->iddocumento = $request->documento;
            $cliente->direccion = $request->direccion;
            $cliente->telefono = $request->telefono;
            $cliente->email = $request->email;
            $cliente->fecha_nacimiento = $request->fecha_nacimiento;
            $cliente->updated_by = Auth::user()->id;

            $cliente->save();

            return response()->json(['status' => true]);
        } else {

            return response()->json([$validatedData]);
        };
    }


    //Falta Implementar
    public function destroy(Request $request)
    {
        $existe = DB::table('clientes as c')->join('cotizacion as co', 'c.id', 'co.idcliente')->where('co.idcliente', '=', $request->id)->count();

        if ($existe > 0) {
            return response()->json(['status' => false]);
        } else {
            $documento = Cliente::find($request->id);
            $documento->delete();
            return response()->json(['status' => true]);
        }
    }

    //Select cliente servicio

    public function selectCliente(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $cliente = Cliente::select(
            'id',
            DB::raw("CONCAT_WS(' - ',nombre, numdoc) as nombre"),
            DB::raw("date_format( now(), '%Y' ) - date_format( fecha_nacimiento, '%Y' ) - ( date_format( now(), '00-%m-%d') < date_format( fecha_nacimiento, '00-%m-%d' ) ) AS  edad"),
            'numdoc')
            ->where('nombre', 'like', '%' . $filtro . '%')
            ->orWhere('numdoc', 'like', '%' . $filtro . '%')
            ->orderBy('nombre', 'asc')->get();

        return $cliente;
    }
}
/*
select CONCAT_WS(' - ',cli.nombre, cli.numdoc) as cliente, CONCAT_WS(' ', u.firstname,  u.lastname) as empleado,
tu.nombre as tipoUso, m.nombre as modelo, c.suma_asegurada, c.desgravamen, c.estado, vigencia_inicio, vigencia_fin
from cotizacion as c
inner join clientes as cli on c.idcliente = cli.id
inner join users as u on c.iduser = u.id
inner join tipo_uso as tu on c.idtipo_uso = tu.id
inner join modelo as m on c.idmodelo = m.id
*/
