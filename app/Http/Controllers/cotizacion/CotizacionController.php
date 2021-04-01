<?php

namespace App\Http\Controllers\cotizacion;

use App\Cotizacion;
use App\CotizacionSeguros;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FilesController;
use App\SeguimientoCotizacion;
use App\TipoSeguro;
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

        if (Auth::user()->rol[0]->name == "Administrador") {

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
                    DB::raw("(select DATE_FORMAT(vigencia_inicio, '%d/%m/%Y') from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as vigencia_inicio"),
                    DB::raw("(select DATE_FORMAT(vigencia_fin, '%d/%m/%Y') from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as vigencia_fin"),
                    DB::raw("(select estado from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as estado"),
                    DB::raw("(select TIMESTAMPDIFF(DAY, CURDATE(), vigencia_fin) from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as dias_restantes"),
                    DB::raw("(select IF(vigencia_fin > CURDATE(), 'SI', 'NO') from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as vigencia_estado"),
                    'c.placa'
                )
                ->join('clientes as cli', 'c.idcliente', '=', 'cli.id')
                ->join('users as u', 'c.iduser', '=', 'u.id')
                ->join('tipo_uso as tu', 'c.idtipo_uso', '=', 'tu.id')
                ->join('modelo as m', 'c.idmodelo', '=', 'm.id')
                ->where($v, 'like', '%' . $buscar . '%')
                ->orderBy('c.id', 'desc')->paginate($request->pag_mostrar);
        } else {

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
                    DB::raw("(select DATE_FORMAT(vigencia_inicio, '%d/%m/%Y') from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as vigencia_inicio"),
                    DB::raw("(select DATE_FORMAT(vigencia_fin, '%d/%m/%Y') from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as vigencia_fin"),
                    DB::raw("(select estado from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as estado"),
                    DB::raw("(select TIMESTAMPDIFF(DAY, CURDATE(), vigencia_fin) from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as dias_restantes"),
                    DB::raw("(select IF(vigencia_fin > CURDATE(), 'SI', 'NO') from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as vigencia_estado"),
                    'c.placa'
                )
                ->join('clientes as cli', 'c.idcliente', '=', 'cli.id')
                ->join('users as u', 'c.iduser', '=', 'u.id')
                ->join('tipo_uso as tu', 'c.idtipo_uso', '=', 'tu.id')
                ->join('modelo as m', 'c.idmodelo', '=', 'm.id')
                ->where('c.iduser', '=', Auth::user()->id)
                ->where($v, 'like', '%' . $buscar . '%')
                ->orderBy('c.id', 'desc')->paginate($request->pag_mostrar);
        }
        /*https://l-lin.github.io/font-awesome-animation/ */






        return [
            'pagination' => [
                'total'        => $cotizacion->total(),
                'current_page' => $cotizacion->currentPage(),
                'per_page'     => $cotizacion->perPage(),
                'last_page'    => $cotizacion->lastPage(),
                'from'         => $cotizacion->firstItem(),
                'to'           => $cotizacion->lastItem(),
            ],
            'cotizacion' => $cotizacion,

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
            'vaCreCoti.fecha_inicio.required' => 'Ingrese la fecha de inicio',
            'vaCreCoti.fecha_fin.required' => 'Ingrese la fecha de expiración',
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
                $c->alertar                =  'SI';
                $c->placa                  =  $cotizacion['placa'];
                $c->telefono               =  $cotizacion['telefono_s'];
                $c->created_by             =  Auth::user()->id;
                $c->updated_by             = 0;
                $c->save();

                $sc = new SeguimientoCotizacion();
                $sc->idcotizacion               = $c->id;
                $sc->vigencia_inicio            = $cotizacion['fecha_inicio'];
                $sc->vigencia_fin               = $cotizacion['fecha_fin'];
                $sc->file                       = null;
                $sc->comentario                 = ($cotizacion['comentario'] == NULL) ? '' : $cotizacion['comentario'];
                $sc->estado                     =  $cotizacion['estado'];
                $sc->save();


                $detalle = $request->arrayCotizacion;
                $num = 0;
                //arrayPrimaNeta

                foreach ($detalle as $d => $det) {

                    $d = new CotizacionSeguros();

                    $d->idcotizacion                    = $c->id;/* iva_por */
                    $d->idtasa                          = $det['id_Tasa'];
                    $d->porcentaje_tasa                 = $det['porcentaje_tasa'];
                    $d->idseguro                        = $det['seguro']['id'];
                    $d->auto_auto_aplica                = $det['auto_auto_aplica'];
                    $d->dis_rastreo_aplica              = $det['dis_rastreo_aplica'];
                    $d->interes_financiamiento_por      = $det['interes_financiamiento_por'];
                    $d->s_bancos_por                    = $det['s_bancos_por'];
                    $d->s_campesino_por                 = $det['s_campesino_por'];
                    $d->seleccionar                     = $det['seleccionar'];
                    $d->total_general                   = $det['total'];
                    $d->prima_neta                      = $request->arrayPrimaNeta[$num]['prima_neta'];
                    $d->recibir_comision                = false;
                    $d->derecho_emicion                 = $det['derecho_emicion'];
                    $d->total_desgravamen               = $det['desgravamen'];
                    $d->seleccionar                     = ($det['seleccionar'] == true) ? 'SI' : 'NO';
                    $d->iva_por                         = $det['iva_por'];

                    $d->save();
                    $num++;
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

    public function update(Request $request)
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

                $c = Cotizacion::findOrFail($request->id);

                $c->idcliente              =  $cotizacion['idcliente'];
               // $c->iduser                 =  Auth::user()->id;
                $c->idclasificacion        =  $cotizacion['idClasificacion'];
                $c->idtipo_uso             =  $cotizacion['idTipoUso'];
                $c->idmodelo               =  $cotizacion['idVehiculo'];
                $c->suma_asegurada         =  $cotizacion['suma_asegurada'];
                $c->desgravamen            =  $cotizacion['desgravamen'];
                $c->alertar                =  'SI';
                $c->placa                  =  $cotizacion['placa'];
                $c->telefono               =  $cotizacion['telefono_s'];
                //$c->created_by             = Auth::user()->id;
                $c->updated_by             = Auth::user()->id;
                $c->save();

                $sc = new SeguimientoCotizacion();
                $sc->idcotizacion               = $c->id;
                $sc->vigencia_inicio            = $cotizacion['fecha_inicio'];
                $sc->vigencia_fin               = $cotizacion['fecha_fin'];
                $sc->file                       = null;
                $sc->comentario                 = ($cotizacion['comentario'] == NULL) ? '' : $cotizacion['comentario'];
                $sc->estado                     =  $cotizacion['estado'];
                $sc->save();


                $detalle = $request->arrayCotizacion;
                $num = 0;
                //arrayPrimaNeta

                foreach ($detalle as $d => $det) {

                    $d = CotizacionSeguros::findOrFail($det['id_cotizacion_seguro']);

                    $d->idcotizacion                    = $c->id;/* iva_por */
                    $d->idtasa                          = $det['id_Tasa'];
                    $d->porcentaje_tasa                 = $det['porcentaje_tasa'];
                    $d->idseguro                        = $det['seguro']['id'];
                    $d->auto_auto_aplica                = $det['auto_auto_aplica'];
                    $d->dis_rastreo_aplica              = $det['dis_rastreo_aplica'];
                    $d->interes_financiamiento_por      = $det['interes_financiamiento_por'];
                    $d->s_bancos_por                    = $det['s_bancos_por'];
                    $d->s_campesino_por                 = $det['s_campesino_por'];
                    $d->seleccionar                     = $det['seleccionar'];
                    $d->total_general                   = $det['total'];
                    $d->prima_neta                      = $request->arrayPrimaNeta[$num]['prima_neta'];
                    //$d->recibir_comision                = false;
                    $d->derecho_emicion                 = $det['derecho_emicion'];
                    $d->total_desgravamen               = $det['desgravamen'];
                    $d->seleccionar                     = ($det['seleccionar'] == true) ? 'SI' : 'NO';
                    $d->iva_por                         = $det['iva_por'];

                    $d->save();
                    $num++;
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
        $cotizacion = DB::select('select * from DETALLE_COTIZACION where id = ?', [$id]);
        //$cotizacion_seguro = DB::select('select * from cotizacion_seguro_view  where idcotizacion = ?', [$request->id]);
        return response()->json(['cotizacion' => $cotizacion[0]]);
    }

    public function generarPDFCotizacion(Request $request)
    {
        //Datos generales
        $cotizacion = DB::select('select * from DETALLE_COTIZACION where id = ?', [$request->id]);

        //Seguros cotizados
        $cotizacion_seguro = DB::select('select * from cotizacion_seguro_view  where idcotizacion = ?', [$request->id]);

        /**COVERTURAS -------------------------------- ----------------------------------------*/
        $coverturas_mostrar = DB::select('select * from cobertura_deducible where tipo="COBERTURA" ');
        /* BUSCO LOS ID DE LOS SEGUROS */
        $covertura_array = [];
        foreach ($cotizacion_seguro as $key => $seguro) {
            //array_push($seguro_aux,$seguro->id);
            $consulta = DB::table('seguro_cobertura_deducible AS scd') //array que recorremos
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
                ->where('scd.idseguro', $seguro->idseguro)->orderBy('scd.idseguro', 'asc')->orderBy('cd.id', 'asc')->get();

            $covertura_array[$key] = $consulta;
        }
        /**DEDUCIBLES -------------------------------- ----------------------------------------*/
        $deducibles_mostrar = DB::select('select * from cobertura_deducible where tipo="DEDUCIBLE" ');
        $deducibles_array = [];
        foreach ($cotizacion_seguro as $key => $seguro) {
            //array_push($seguro_aux,$seguro->id);
            $consulta2 = DB::table('seguro_cobertura_deducible AS scd') //array que recorremos
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
                ->where('scd.idseguro', $seguro->idseguro)->orderBy('scd.idseguro', 'asc')->orderBy('cd.id', 'asc')->get();

            $deducibles_array[$key] = $consulta2;
        }

        /* calcular colspan */
        $espacios = count($cotizacion_seguro) * 2;
        $espacios2 = count($cotizacion_seguro);

        $pdf = PDF::loadView(
            'pdf.cotizacion',
            [
                'cotizacion' => $cotizacion[0],
                'deducibles_mostrar' => $deducibles_mostrar,
                'deducible' => $deducibles_array,
                'covertura' => $covertura_array,
                'coverturas_mostrar' => $coverturas_mostrar,
                'cotizacion_seguro' => $cotizacion_seguro,
                'espacios' => $espacios,
                'espacio2s' => $espacios2,

            ]
        )->setPaper('A4', 'landscape'); //landscape
        return $pdf->stream('cotizacion_' . $cotizacion[0]->cliente . '.pdf'); //11
        //return $pdf->download('cotizacion_' . $cotizacion[0]->cliente . '.pdf');

    }

    public function cargarDatos(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $cotizacion = DB::table('cotizacion')->select(
            'id',
            'idcliente',
            'iduser',
            'idclasificacion',
            'idtipo_uso',
            'idmodelo',
            'suma_asegurada',
            'desgravamen',
            'alertar',
            DB::raw("(select DATE_FORMAT(vigencia_inicio, '%Y-%m-%d') from seguimiento_cotizacion where idcotizacion = cotizacion.id order by id DESC limit 1) as vigencia_inicio"),
            DB::raw("(select DATE_FORMAT(vigencia_fin, '%Y-%m-%d') from seguimiento_cotizacion where idcotizacion = cotizacion.id order by id DESC limit 1) as vigencia_fin"),
            DB::raw("(select estado from seguimiento_cotizacion where idcotizacion = cotizacion.id order by id DESC limit 1) as estado"),
            DB::raw("(select comentario from seguimiento_cotizacion where idcotizacion = cotizacion.id order by id DESC limit 1) as comentario"),
            'telefono',
            'placa',
            'created_by',
            'updated_by',
        )->where('id', '=', $request->id)->get();

        $cotizacion_seguro = CotizacionSeguros::select()->where('idcotizacion', '=', $request->id)->get();

        /*---------------------------------------------------------------------------------------- */
        $arrayData = [];
        foreach ($cotizacion_seguro as $key => $value) {

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
                ->where('scd.idseguro', '=', $value->idseguro)->orderBy('cd.id', 'asc')->get();

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
                ->where('scd.idseguro', '=', $value->idseguro)->orderBy('cd.id', 'asc')->get();

            $tasa = DB::table('tasa_seguro')->select('tasa', 'id')->where('id', '=', $value->idtasa)->get();
            $seguro = TipoSeguro::select()->where('id', '=', $value->idseguro)->get();
            $cotizacion_seguro_detalle = CotizacionSeguros::select()->where('idseguro', '=', $value->idseguro)
                ->where('idcotizacion', '=', $request->id)->get();

            $arrayData[$key] = array(
                'covertura' => $covertura,
                'deducible' => $deducible,
                'tasa' =>  $tasa[0],
                'seguro' => $seguro,
                'porcentaje_tasa' =>  $value->porcentaje_tasa,
                'cotizacion_seguro_detalle' => $cotizacion_seguro_detalle[0],
                'id_cotizacion_seguro' => $value->id,

            );
        }
        /*---------------------------------------------------------------------------------------- */

        return response()->json(['cotizacion' => $cotizacion[0], 'detalle' => $arrayData]);
    }

    public function seguimiento_Detalle(Request $request)
    {
        $id = $request->id;

        $seguimiento = DB::select('select * from seguimiento_cotizacion_view where idcotizacion = ?', [$id]);

        return response()->json(['seguimiento' => $seguimiento], 200);
    }

    public function storeFile(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer|min:1',
            'estado' => 'required|not_in:0',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
        ], [
            'id.min' => 'Error de cotización',
            'estado.not_in' => 'Seleccione un estado',
            'fecha_inicio.required' => 'Ingrese la fecha de inicio',
            'fecha_fin.required' => 'Ingrese la fecha de expiración',
        ]);

        if ($validatedData) {

            $sc = new SeguimientoCotizacion();
            $sc->idcotizacion       = $request->id;
            $sc->vigencia_inicio    = $request->fecha_inicio;
            $sc->vigencia_fin       = $request->fecha_fin;
            $sc->file               = FilesController::setProcesarfile($request->file('file')); //$request->Nombre;
            $sc->comentario         = ($request->comentario == NULL) ? '' : $request->comentario;
            $sc->estado             = $request->estado;
            $sc->save();
            return response()->json(['status' => true]);
            /* return response()->json(['data' => $request->all]); */
        } else {
            return response()->json([$validatedData, 'data' => $request->all()]);
        }

        //return response()->json(['data' => $request->all()]);
    }

    /*
    id: 0,
                estado: 0,
                fecha_inicio: null,
                fecha_fin: null,
                comentario: '',
                file: null,
     
     */
}

/*
CREATE VIEW DETALLE_COTIZACION AS
select
c.id, cli.nombre as cliente,
date_format( now(), '%Y' ) - date_format(cli.fecha_nacimiento, '%Y' ) - ( date_format( now(), '00-%m-%d') < date_format(cli.fecha_nacimiento, '00-%m-%d' ) ) AS  edad,
cli.numdoc as cedula, cli.telefono as telefono_1, c.telefono as telefono_2, m.nombre as vehiculo, m.año as año_vehiculo, c.placa, ma.nombre as marca, ca.nombre as categoria,
cla.nombre as clasificacion,  tu.nombre as tipo_uso,
c.suma_asegurada, c.desgravamen,
(select estado from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as estado,
(select comentario from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as comentario,
(select DATE_FORMAT(vigencia_inicio, '%d/%m/%Y') from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as vigencia_inicio,
(select DATE_FORMAT(vigencia_fin, '%d/%m/%Y') from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as vigencia_fin,
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

/*
CREATE VIEW cotizacion_seguro_view AS
select c.idcotizacion, c.idseguro, c.porcentaje_tasa, c.auto_auto_aplica, ts.auto_auto, c.dis_rastreo_aplica,
			ts.dispositivo_rastreo, c.interes_financiamiento_por, c.s_bancos_por, c.s_campesino_por, c.total_general, c.prima_neta,ts.nombre, ts.logo,
ts.desgravamen as por_desgravamen, ts.cero_deducible, ts.amparo_patrimonial, c.derecho_emicion, c.iva_por, c.total_desgravamen
 from cotizacion_seguros as c
inner join tipo_seguro as ts on c.idseguro = ts.id
*/
/*
select id, idcotizacion,
			TIMESTAMPDIFF(DAY,CURDATE(), '2021-03-21')AS dias_restantes,
			IF('2021-03-21' > CURDATE(), 'SI', 'NO') ,
		comentario, estado from seguimiento_cotizacion  order by id DESC limit
*/

/*

create view seguimiento_cotizacion_view AS
select id, idcotizacion, vigencia_inicio, vigencia_fin, file, comentario,
estado, DATE_FORMAT(created_at, '%d-%m-%Y %r') as created_at, updated_at from seguimiento_cotizacion
*/


/*
create view comision_pago_empleado AS
select cs.id, u.id as iduser, c.created_at , CONCAT_WS(' ', u.firstname, u.lastname) as asesor, cs.idcotizacion, cs.total_general, cs.seleccionar, cs.recibir_comision from cotizacion_seguros as cs
inner join cotizacion as c on cs.idcotizacion = c.id
inner join users AS u ON c.iduser = u.id
where cs.seleccionar = 'SI' and cs.recibir_comision = 0
*/

/*
CREATE VIEW list_pago_empleado AS
select pe.id, CONCAT_WS(' ', u.firstname,  u.lastname) as empleado, pe.monto, pe.comision, (pe.monto + pe.comision) as general
, pe.por_comision, pe.created_at as fecha_registro, pe.descripcion from pagos_empleados as pe
inner join users as u on pe.idusuario = u.id

*/

/*
CREATE VIEW reporte_mes as
select cs.id, u.id as iduser, c.created_at , CONCAT_WS(' ', u.firstname, u.lastname) as asesor, cs.idcotizacion, cs.total_general, cs.seleccionar, cs.recibir_comision from cotizacion_seguros as cs
inner join cotizacion as c on cs.idcotizacion = c.id
inner join users AS u ON c.iduser = u.id
where cs.seleccionar = 'SI'
*/

/*

CREATE VIEW reporte_mes_año AS
 SELECT
	MONTH (created_at) AS mes,
	YEAR (created_at) AS anio,
	SUM(total_general) AS total
FROM
	reporte_mes
GROUP BY MONTH(created_at), YEAR(created_at)
 */

 /*
 CREATE VIEW asesor_venta_max AS 
SELECT
	asesor,
	SUM(total_general) AS total
FROM
	reporte_mes
GROUP BY asesor
order by total desc limit 5
 */

 /*
 
CREATE VIEW asesor_bonificacion AS
select empleado, SUM(general) AS total from list_pago_empleado
GROUP BY empleado
order by total desc limit 5
 */

 /*
 CREATE VIEW vendido_anio_mes_asesor AS
SELECT
iduser,
asesor,
	MONTH (created_at) AS mes,
	YEAR (created_at) AS anio,
	SUM(total_general) AS total
FROM
	reporte_mes
GROUP BY asesor, MONTH(created_at), YEAR(created_at)
 */

 /*
 CREATE VIEW user_by AS select u.id, r.name as cargo, CONCAT_WS(' ', u.firstname, u.lastname) as empleado from users as u
inner join users_roles as ur on u.id = ur.user_id
inner join roles as r on ur.role_id = r.id
 */

 /*
 
DELIMITER $ 
CREATE FUNCTION f_insertar_mayusculaMinuscula(cadena varchar(45)) returns varchar(45)
    BEGIN
    DECLARE len INT;
    DECLARE i INT;
    DECLARE input varchar(45);

    SET len   = CHAR_LENGTH(cadena);
    SET input = LOWER(cadena);
    SET i = 0;

    WHILE (i < len) DO
        IF (MID(input,i,1) = ' ' OR i = 0) THEN
            IF (i < len) THEN
                SET input = CONCAT(
                    LEFT(input,i),
                    UPPER(MID(input,i + 1,1)),
                    RIGHT(input,len - i - 1));
            END IF;
        END IF;
        SET i = i + 1;
    END WHILE;

    RETURN input;
    END $
 
 
 */

 /*
 
 CREATE VIEW notificacion_sistema as 
select c.id, u.id as iduser,CONCAT_WS(' ', u.firstname, u.lastname) as asesor, c.alertar,
				(select DATE_FORMAT(vigencia_inicio, '%d/%m/%Y') from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as vigencia_inicio,
				(select DATE_FORMAT(vigencia_fin, '%d/%m/%Y') from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as vigencia_fin,
				(select estado from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as estado,
				((select TIMESTAMPDIFF(DAY, CURDATE(), vigencia_fin) from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) * -1) as dias_restantes,
				(select IF(vigencia_fin > CURDATE() || estado = 'PRELIQUIDACION', 'SI', 'NO') from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as vigencia_estado,
				(select CONCAT_WS('',LEFT(comentario,60),'...') from seguimiento_cotizacion where idcotizacion = c.id order by id DESC limit 1) as comentario,
				 UPPER(TRIM( SUBSTRING( cli.nombre, 1, 1 ) )) as iniciales, f_insertar_mayusculaMinuscula(cli.nombre) as cliente
				

from cotizacion as c
inner join clientes as cli on c.idcliente = cli.id
inner join users as u on c.iduser = u.id


 */

 /*
CREATE VIEW total_comision_ganancia as 
select sum(total)as total,  (sum(total)  * 0.15) as porcentaje, ((sum(total)  * 0.15) - (select SUM(general) AS total from list_pago_empleado)) as ganancia from reporte_mes_año
 */
