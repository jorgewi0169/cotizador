<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificacionController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax())  return redirect('/');

        if (Auth::user()->rol[0]->name == "Administrador") {

            $notificacion_sistema = DB::table('notificacion_sistema')->select()->where('vigencia_estado', '=', 'NO')->get();
        } else {
            $notificacion_sistema = DB::table('notificacion_sistema')->select()
                ->where('vigencia_estado', '=', 'NO')
                ->where('iduser', '=', Auth::user()->id)
                ->get();
        }


        return response()->json(['notificacion_sistema' => $notificacion_sistema, 'count' => count($notificacion_sistema)]);
    }
}
