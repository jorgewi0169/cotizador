<?php

namespace App\Http\Controllers;

use App\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $configuracion = Configuracion::select()->get();
        return response()->json(['config' => $configuracion]);
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $validatedData = $request->validate([

            'config_id' => 'required',
            'titulo_pagina' => 'required',
            'titulo_pdf' => 'required',
            'pie_pdf' => 'required',
            'iva' => 'required|numeric',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required',
        ], ['config_id.required' => 'Error de identificador']);

        if ($validatedData) {

            $config = Configuracion::findOrFail($request->config_id);

            $config->titulo_pagina = $request->titulo_pagina;
            $config->titulo_pdf = $request->titulo_pdf;
            $config->pie_pdf = $request->pie_pdf;
            $config->iva = $request->iva;
            $config->direccion = $request->direccion;
            $config->telefono = $request->telefono;
            $config->email = $request->email;
            $config->logo_1 = (!is_string($request->logo_1)) ? (FilesController::setProcesarFileConfig($request->file('logo_1'), $config->logo_1)) : $request->logo_1;
            $config->logo_2 = (!is_string($request->logo_2)) ? (FilesController::setProcesarFileConfig($request->file('logo_2'), $config->logo_2)) : $request->logo_2;
            $config->logo_pdf = (!is_string($request->logo_pdf)) ? (FilesController::setProcesarFileConfig($request->file('logo_pdf'), $config->logo_pdf)) : $request->logo_pdf;
            $config->favicon_img = (!is_string($request->favicon_img)) ? (FilesController::setProcesarFileConfig($request->file('favicon_img'), $config->favicon_img)) : $request->favicon_img;
            $config->save();

            return response()->json(['status' => true, "config"=>$config]);

        } else {

            return response()->json([$validatedData]);
        };
//return response()->json([$request->all()]);
    }
}
