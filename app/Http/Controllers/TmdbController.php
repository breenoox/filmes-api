<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;

class TmdbController extends Controller
{

    public function listagemFilmes(TmdbService $tmdbService,Request $request)
    {
        try {
            $pagina = $request->input('pagina', 1);
            $idGenero = $request->input('genero');
            $palavraFilme = $request->input('palavra');

            $data = $tmdbService->filtrarFilme($idGenero, $palavraFilme, $pagina);
            return response()->json($data);
            
        } catch(\Exception $e) {
            return view('erro', ['error' => $e->getMessage()]);
        }
    }
    
    public function retornaGeneros(TmdbService $tmdbService)
    {
        try {
            $data = $tmdbService->listaGeneros();
            return response()->json($data);

        } catch(\Exception $e) {
            return view('erro', ['error' => $e->getMessage()]);
        }
    }


}
