<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;

class TmdbController extends Controller
{
    public function index(Request $request, TmdbService $tmdbService)
    {
        try {
            $pagina = $request->query('page', 1);

            $data = $tmdbService->listaFilmes($pagina);
            return response()->json($data);
            
        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function detalhesFilme($id, TmdbService $tmdbService)
    {
        try {
            $data = $tmdbService->detalhesFilme($id);
            return view('detalhes', compact('data'));

        } catch (\Exception $e) {
            return view('erro', ['error' => $e->getMessage()]);
        }
    }

    public function procurarFilme($palavra, TmdbService $tmdbService)
    {
        try {
            $data = $tmdbService->procurarFilme($palavra);
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
