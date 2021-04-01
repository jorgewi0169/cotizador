<?php

namespace App\Http\Controllers\cotizacion;

use App\Cotizacion;
use App\CotizacionSeguros;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class CotizacionController extends Controller
{

    public function index(Request $request)
    {
        if (!$request->ajax())  return redirect('/');

        $criterio = $request->cCriterio; //Criterio de busqueda
        $buscar = $request->cBusqueda; //palabra a buscar

        $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;
        $criterio    =   ($criterio   ==  NULL) ? ($criterio   =   'cli.nombre') :   $criterio;

        $v = "";
        switch ($criterio) {

            case 'cliente':
                $v = 'cli.nombre';
                break;
            case 'asesor':
                $v = DB::raw("CONCAT_WS(' ', u.firstname,  u.lastname)");
                break;
            case 'documento':
                $v = 'cli.numdoc';
                break;
            case 'placa':
                $v = 'c.placa';
                break;
        }


        $cotizacion = DB::table('cotizacion as c')
            ->select(
                'c.id',
                'cli.nombre as cliente',
                'cli.numdoc as documento',
                DB::raw("CONCAT_WS(' ', u.firstname, u.lastname) as empleado"),
                'tu.nombre as tipoUso',
                'm.nombre as modelo',
                'c.suma_asegurada',
                'c.desgravamen',
                'c.estado',
                DB::raw("DATE_FORMAT(c.vigencia_inicio, '%d/%m/%Y') as vigencia_inicio"),
                DB::raw("DATE_FORMAT(c.vigencia_fin, '%d/%m/%Y') as vigencia_fin"),
                'c.placa'
            )
            ->join('clientes as cli', 'c.idcliente', '=', 'cli.id')
            ->join('users as u', 'c.iduser', '=', 'u.id')
            ->join('tipo_uso as tu', 'c.idtipo_uso', '=', 'tu.id')
            ->join('modelo as m', 'c.idmodelo', '=', 'm.id')
            ->where($v, 'like', '%' . $buscar . '%')
            ->orderBy('c.id', 'desc')->paginate($request->pag_mostrar);


        return [
            'pagination' => [
                'total'        => $cotizacion->total(),
                'current_page' => $cotizacion->currentPage(),
                'per_page'     => $cotizacion->perPage(),
                'last_page'    => $cotizacion->lastPage(),
                'from'         => $cotizacion->firstItem(),
                'to'           => $cotizacion->lastItem(),
            ],
            'cotizacion' => $cotizacion
        ];
    }
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'vaCreCoti.estado' => 'required|not_in:0',
            'vaCreCoti.fecha_inicio' => 'required',
            'vaCreCoti.placa' => 'required',
            'vaCreCoti.fecha_fin' => 'required',
            'vaCreCoti.idcliente' => 'required|integer|min:1',
            'vaCreCoti.idClasificacion' => 'required|integer|min:1',
            'vaCreCoti.idTipoUso' => 'required|integer|min:1',
            'vaCreCoti.idVehiculo' => 'required|integer|min:1',
            'vaCreCoti.suma_asegurada' => 'required|numeric|min:0|not_in:0',
        ], [
            'vaCreCoti.idcliente.min' => 'Seleccione un cliente',
            'vaCreCoti.idClasificacion.min' => 'Seleccione una clasificación del vehículo',
            'vaCreCoti.idTipoUso.min' => 'Seleccione el tipo de uso',
            'vaCreCoti.idVehiculo.min' => 'Seleccione un vehículo',
            'vaCreCoti.estado.not_in' => 'Seleccione un estado',
            'vaCreCoti.suma_asegurada.min' => 'Ingrese una suma asegurada mayor a 0',
            'vaCreCoti.suma_asegurada.not_in' => 'Ingrese una suma asegurada mayor a 0',
            'vaCreCoti.fecha_inicio.required' => 'Ingrese la fecha de inicio de la cotización',
            'vaCreCoti.fecha_fin.required' => 'Ingrese la fecha de expiración de la cotización',
            'vaCreCoti.placa.required' => 'Ingrese el número de placa del vehículo'
        ]);

        if ($validatedData) {
            try {

                DB::beginTransaction();
                $cotizacion = $request->vaCreCoti;
                $c = new Cotizacion();
                $c->idcliente              =  $cotizacion['idcliente'];
                $c->iduser                 =  Auth::user()->id;
                $c->idclasificacion        =  $cotizacion['idClasificacion'];
                $c->idtipo_uso             =  $cotizacion['idTipoUso'];
                $c->idmodelo               =  $cotizacion['idVehiculo'];
                $c->suma_asegurada         =  $cotizacion['suma_asegurada'];
                $c->desgravamen            =  $cotizacion['desgravamen'];
                $c->estado                 =  $cotizacion['estado'];
                $c->alertar                =  'SI';
                $c->vigencia_inicio        =  $cotizacion['fecha_inicio'];
                $c->vigencia_fin           =  $cotizacion['fecha_fin'];
                $c->placa                  =  $cotizacion['placa'];
                $c->telefono               =  $cotizacion['telefono_s'];
                $c->comentario             =  ($cotizacion['comentario'] == NULL) ? '' : $cotizacion['comentario'];
                $c->created_by             = Auth::user()->id;
                $c->updated_by             = 0;
                $c->save();


                $detalle = $request->arrayCotizacion;

                foreach ($detalle as $d => $det) {

                    $d = new CotizacionSeguros();

                    $d->idcotizacion                    = $c->id;
                    $d->idtasa                          = $det['id_Tasa'];
                    $d->porcentaje_tasa                 = $det['porcentaje_tasa'];
                    $d->idseguro                        = $det['seguro']['id'];
                    $d->auto_auto_aplica                = $det['auto_auto_aplica'];
                    $d->dis_rastreo_aplica              = $det['dis_rastreo_aplica'];
                    $d->interes_financiamiento_por      = $det['interes_financiamiento_por'];
                    $d->s_bancos_por                    = $det['s_bancos_por'];
                    $d->s_campesino_por                 = $det['s_campesino_por'];
                    $d->seleccionar                     = $det['seleccionar'];
                    $d->total                           = $det['total'];
                    $d->recibir_comision                = false;
                    $d->seleccionar                     = ($det['seleccionar'] == true) ? 'SI' : 'NO';

                    $d->save();
                }
                DB::commit();

                return response()->json(['status' => true]);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['status' => false, 'mensaje' => $e->getMessage()]);
            }
        } else {
            return response()->json([$validatedData]);
        }
    }


    public function obtenerCotizacion(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
    }

    public function listarPdf(Request $request)
    {
        //Datos generales
        $cotizacion = DB::select('select * from DETALLE_COTIZACION where id = ?', [4]);
        //Seguros cotizados
        $cotizacion_seguro = DB::select('select * from cotizacion_seguros as c
        inner join tipo_seguro as ts on c.idseguro = ts.id where idcotizacion = ?', [4]);

        //Llamos las coverturas dque perteneces a cada seguro cotizado

            $covertura = DB::table('seguro_cobertura_deducible AS scd') //array que recorremos
            ->select(
                'scd.idseguro as seguro',
                'cd.id',
                'cd.nombre',
                'cd.tipo',
                'cd.tipo_variable',
                'scd.aplica',
                'scd.monto',
                'scd.descripcion'
            )->join('cobertura_deducible as cd', 'scd.iddeco', '=', 'cd.id')
            ->where('cd.tipo', '=', 'COBERTURA')
            ->whereIn('scd.idseguro', [1,2,3])->orderBy('scd.idseguro', 'asc')->orderBy('cd.id', 'asc')->get();


            $deducible = DB::table('seguro_cobertura_deducible AS scd') //array que recorremos
            ->select(
                'scd.idseguro as seguro',
                'cd.id',
                'cd.nombre',
                'cd.tipo',
                'cd.tipo_variable',
                'scd.aplica',
                'scd.monto',
                'scd.descripcion'
            )->join('cobertura_deducible as cd', 'scd.iddeco', '=', 'cd.id')
            ->where('cd.tipo', '=', 'DEDUCIBLE')
            ->whereIn('scd.idseguro', [1,2,3])->orderBy('scd.idseguro', 'asc')->orderBy('cd.id', 'asc')->get();



        /* calcular colspan */
        $colspan = count($cotizacion_seguro) * 2;

        $pdf = PDF::loadView(
            'pdf.cotizacion',
            [
                'cotizacion' => $cotizacion[0],
                'deducible' => $deducible,
                'covertura' => $covertura,
                'cotizacion_seguro' => $cotizacion_seguro,
                'colspan' => $colspan
            ]
        )->setPaper('A4', 'landscape'); //landscape
        //return $pdf->download('articulos.pdf');
        //$cotizacion = $cotizacion[0];
        return $pdf->stream('cotizacion.pdf');
    }
}

/*
CREATE VIEW DETALLE_COTIZACION AS
select
c.id, cli.nombre as cliente,
date_format( now(), '%Y' ) - date_format(cli.fecha_nacimiento, '%Y' ) - ( date_format( now(), '00-%m-%d') < date_format(cli.fecha_nacimiento, '00-%m-%d' ) ) AS  edad,
cli.numdoc as cedula, cli.telefono as telefono_1, c.telefono as telefono_2, c.comentario, m.nombre as vehiculo, m.año as año_vehiculo, c.placa, ma.nombre as marca, ca.nombre as categoria,
cla.nombre as clasificacion,  tu.nombre as tipo_uso,
c.suma_asegurada, c.desgravamen, c.estado, c.vigencia_inicio, c.vigencia_fin,
CONCAT_WS(' ', u.firstname, u.lastname) as asesor, u.telefono as asesor_telefono,
CONCAT_WS(' ', us.firstname, us.lastname) as creado, CONCAT_WS(' ', usr.firstname, usr.lastname) as actualizado
from cotizacion 	AS c
inner join users AS u ON c.iduser = u.id
inner join users AS us ON c.created_by = us.id
left join users AS usr ON c.updated_by = usr.id
inner join clientes AS cli ON c.idcliente = cli.id
inner join modelo AS m ON c.idmodelo = m.id
inner join marca AS ma ON m.idmarca = ma.id
inner join categoria AS ca ON m.idcategoria = ca.id
inner join clasificacion AS cla ON c.idclasificacion = cla.id
inner join tipo_uso AS tu ON c.idtipo_uso = tu.id

*/
