<?php

namespace App\Http\Controllers;

use App\Models\generos;

use Illuminate\Http\Request;

class generosController extends Controller
{
    public function retornaGeneros()
    {
        try {
            $generos = generos::all(); 
            return response()->json($generos); 

        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}
