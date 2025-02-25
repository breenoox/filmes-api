<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;

class TmdbController extends Controller
{

    public function listagemFilmes(TmdbService $tmdbService,Request $request)
    {
        try {
            $dados = [
                'pagina' => $request->input('pagina', 1),
                'idGenero' => $request->input('genero'),
                'palavraFilme' => $request->input('palavra')
            ];
            
            $data = $tmdbService->filtrarFilme($dados);
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

    public function retornaFilmesFavoritos(TmdbService $tmdbService)
    {
        try {
            $data = $tmdbService->retornaFilmesFavoritos();
            return response()->json($data);

        } catch (\Exception $e) {
            return view('erro', ['error' => $e->getMessage()]);
        }
    }

    public function favoritarFilme(TmdbService $tmdbService, Request $request)
    {
        try {
            $dados = [
                'idFilme' => $request->input('idFilme')
            ];

            $data = $tmdbService->salvarFilmeFavoritado($dados);
            return response()->json($data);

        } catch (\Exception $e) {
            return view('erro', ['error' => $e->getMessage()]);
        }
    }
    
    public function desfavoritarFilme(TmdbService $tmdbService, Request $request)
    {
        try {
            $dados = [
                'idFilme' => $request->input('idFilme')
            ];

            $data = $tmdbService->removerFilmeFavoritado($dados);
            return response()->json($data);

        } catch (\Exception $e) {
            return view('erro', ['error' => $e->getMessage()]);
        }
    }


}
