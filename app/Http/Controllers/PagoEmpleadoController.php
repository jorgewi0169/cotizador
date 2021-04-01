<?php

namespace App\Http\Controllers;

use App\CotizacionSeguros;
use App\Pagos_Empleados;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PagoEmpleadoController extends Controller
{

    public function index(Request $request)
    {

        if (!$request->ajax())  return redirect('/');

        $buscar = $request->cBusqueda; //palabra a buscar

        $buscar    =   ($buscar   ==  NULL) ? ($buscar   =   '') :   $buscar;


        $pago = DB::table('list_pago_empleado')
            ->select()
            ->where('empleado', 'like', '%' . $buscar . '%')
            ->orderBy('id', 'desc')->paginate(10);


        return [
            'pagination' => [
                'total'        => $pago->total(),
                'current_page' => $pago->currentPage(),
                'per_page'     => $pago->perPage(),
                'last_page'    => $pago->lastPage(),
                'from'         => $pago->firstItem(),
                'to'           => $pago->lastItem(),
            ],
            'pago' => $pago
        ];
    }
    public function comision(Request $request)
    {


        if (!$request->ajax()) return redirect('/');

        $comision = DB::table('comision_pago_empleado')
            ->select()
            ->where('iduser', '=', $request->id)->get();

        return response()->json(['comision' => $comision]);
    } //


    public function store(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([
            'empleado_id' => 'required|integer|min:1',

        ], [

            'empleado_id.min' => 'Seleccione un Empleado valido'
        ]);

        if ($validatedData) {

            try {
                DB::beginTransaction();
                $pago = new Pagos_Empleados();
                $pago->idusuario        = $request->empleado_id;
                $pago->monto            = $request->monto;
                $pago->comision         = $request->comision;
                $pago->por_comision     = $request->por_comision;
                $pago->descripcion      = ($request->descripcion == NULL) ? '' : $request->descripcion;
                $pago->created_by       = Auth::id();
                $pago->save();

                $sc = CotizacionSeguros::join('cotizacion as c', 'cotizacion_seguros.idcotizacion', '=', 'c.id')
                    ->join('users as u', 'c.iduser', '=', 'u.id')
                    ->where('u.id', '=', $request->empleado_id)
                    ->where('cotizacion_seguros.seleccionar', '=', 'SI')
                    ->update(['recibir_comision' => 1]);

                /*
                Flight::where('active', 1)
      ->where('destination', 'San Diego')
      ->update(['delayed' => 1]);

      UPDATE articulos AS a
                    INNER JOIN servicios_detalle_ventas AS sd ON a.id = sd.idarticulo
                    INNER JOIN servicios AS s ON sd.idservicio = s.id
                    SET a.stock = a.stock - sd.cantidad
                    WHERE s.id = NEW.id;
                 */

                DB::commit();
                return response()->json(['status' => true]);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['status' => false, 'mensaje' => $e->getMessage()]);
            }


            //}

        } else {

            return response()->json([$validatedData]);
        };
    }
}
