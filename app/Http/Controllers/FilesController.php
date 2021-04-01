<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FilesController extends Controller
{
    public static function setProcesarArchivo($file, $actual = false)
    {

        if ($file) {

            if ($actual) {
                Storage::disk('public')->delete("users/$actual");
            }

            $file       =   $file;
            $bandera    =   Str::random(10);
            $filename   =   $file->getClientOriginalName();
            $fileserver =   $bandera . '_' . $filename;


            Storage::putFileAs('public/users', $file, $fileserver);


            return $fileserver;
        } else {
            return '';
        }
    }

    public static function setProcesarFileSeguro($file, $actual = false)
    {

        if ($file) {

            if ($actual) {
                Storage::disk('public')->delete("seguro/$actual");
            }

            $file       =   $file;
            $bandera    =   Str::random(10);
            $filename   =   $file->getClientOriginalName();
            $fileserver =   $bandera . '_' . $filename;


            Storage::putFileAs('public/seguro', $file, $fileserver);


            return $fileserver;
        } else {
            return '';
        }
    }


    public static function setProcesarFileConfig($file, $actual = false)
    {
        if ($file) {

            if ($actual) {
                Storage::disk('public')->delete("config/$actual");
            }

            $file       =   $file;
            $bandera    =   Str::random(10);
            $filename   =   $file->getClientOriginalName();
            $fileserver =   $bandera . '_' . $filename;

            Storage::putFileAs('public/config', $file, $fileserver);

            return $fileserver;
        } else {
            return '';
        }
    }


    public static function setProcesarfile($file, $actual = false)
    {
        if ($file) {

            if ($actual) {
                Storage::disk('public')->delete("files/$actual");
            }

            $file       =   $file;
            $bandera    =   Str::random(10);
            $filename   =   $file->getClientOriginalName();
            $fileserver =   $bandera . '_' . $filename;

            Storage::putFileAs('public/files', $file, $fileserver);

            return $fileserver;
        } else {
            return '';
        }
    }
}
