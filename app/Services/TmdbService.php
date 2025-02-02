<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbService
{

    public function filtrarFilme($idGenero = null, $palavra = null, $pagina = null)
    {
        try {

            $url = "https://api.themoviedb.org/3/discover/movie?include_adult=false&include_video=false&language=pt-BR&page={$pagina}&sort_by=popularity.desc";

            if(!empty($idGenero)) {
                $url .= "&with_genres={$idGenero}";
            }

            if (!empty($palavra)) {
                $pagina = 1;
                $url = "https://api.themoviedb.org/3/search/movie?&language=pt-BR&page={$pagina}";

                $url .= "&query=" . urlencode($palavra);
            }

            $resposta = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TMDB_API_TOKEN'),
                'accept' => 'application/json',
            ])->timeout(30)->get($url);

            if($resposta->successful()) {
                return response()->json($resposta->json());
            } else {
                return response()->json(['error' => 'Erro na API'], 500);
            }

        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function listaGeneros()
    {
        try {
            $resposta = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TMDB_API_TOKEN'),
                'accept' => 'application/json',
            ])->timeout(30)->get("https://api.themoviedb.org/3/genre/movie/list?language=pt");
    
            if ($resposta->successful()) {
                return $resposta->json();
            }
        } catch(\Exception) {
            throw new \Exception('Erro na API: ' . $resposta->status());
        }
    }

}
