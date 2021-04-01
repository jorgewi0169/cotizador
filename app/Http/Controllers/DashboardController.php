<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        if (!$request->ajax())  return redirect('/');
        $anio = date('Y');
        $venta_mes_año = DB::table('reporte_mes_año')->select()->where('anio', $anio)->get();

        $total_comision_ganancia = DB::table('total_comision_ganancia')->select()->get();

        return response()->json(['venta_mes_año' => $venta_mes_año, 'total_comision_ganancia' => $total_comision_ganancia[0]]);
    }


    public function asesor_venta(Request $request)
    {
        if (!$request->ajax())  return redirect('/');
        $asesor_venta_max = DB::table('asesor_venta_max')->select()->get();

        return response()->json(['asesor_venta_max' => $asesor_venta_max]);
    }

    public function asesor_comision(Request $request)
    {
        if (!$request->ajax())  return redirect('/');
        $asesor_bonificacion = DB::table('asesor_bonificacion')->select()->get();

        return response()->json(['asesor_bonificacion' => $asesor_bonificacion]);
    }
    

    public function asesor_vendido_by(Request $request)
    {
        if (!$request->ajax())  return redirect('/');
        $anio = date('Y');
        $vendido_anio_mes_asesor = DB::table('vendido_anio_mes_asesor')
            ->select()
            ->where('anio', $anio)
            ->where('iduser',  Auth::user()->id)
            ->get();

        return response()->json(['vendido_anio_mes_asesor' => $vendido_anio_mes_asesor]);
    }

    public function asesor_by(Request $request)
    {
        if (!$request->ajax())  return redirect('/');
        $anio = date('Y');
        $user_by = DB::table('user_by')
            ->select()
            ->where('id',  Auth::user()->id)
            ->get();

        return response()->json(['user_by' => $user_by[0]]);
    }
}
