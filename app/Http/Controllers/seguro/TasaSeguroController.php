<?php

namespace App\Http\Controllers\seguro;

use App\Http\Controllers\Controller;
use App\TasaSeguro;
use Illuminate\Http\Request;

class TasaSeguroController extends Controller
{
    public function obtenerTasa(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $tasas = TasaSeguro::where('idseguro','=',$id)->get();
        return response()->json(['tasas'=>$tasas]);
    }
}
