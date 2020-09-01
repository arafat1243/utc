<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class AssetsController extends Controller
{

    public function getPublicFile($file){
        $file = str_replace(':','/',$file);
            $path = storage_path('app/private/'.$file);
            try {
                $file = File::get($path);
                $type = File::mimeType($path);
                $response = Response::make($file, 200);
                $response->header("Content-Type", $type);
                return $response;
                // header('Content-Type:'.$type);
                // header('Content-Length: ' . filesize($path));
                // readfile($path);
            } catch (FileNotFoundException $exception) {
                dd($exception->getMessage());
            }
    }
    public function getPrivateFile($file){
        // $file = str_replace(':','/',$file);
            $path = storage_path('app/private/images/users/avatar-12.jpeg');
            try {
                $file = File::get($path);
                $type = File::mimeType($path);
                $response = Response::make($file, 200);
                $response->header("Content-Type", $type);
                return $response;
            } catch (FileNotFoundException $exception) {
                dd($exception->getMessage());
            }
    }
}
