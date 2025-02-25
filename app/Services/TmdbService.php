<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbService
{

    public function filtrarFilme($dados)
    {
        try {

            $url = "https://api.themoviedb.org/3/discover/movie?include_adult=false&include_video=false&language=pt-BR&page={$dados['pagina']}&sort_by=popularity.desc";

            if(!empty($dados['idGenero'])) {
                $url .= "&with_genres={$dados['idGenero']}";
            }

            if (!empty($dados['palavraFilme'])) {
                $pagina = 1;
                $url = "https://api.themoviedb.org/3/search/movie?&language=pt-BR&page={$pagina}";

                $url .= "&query=" . urlencode($dados['palavraFilme']);
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

    public function salvarFilmeFavoritado($dados)
    {
        try {

            $resposta = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TMDB_API_TOKEN'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://api.themoviedb.org/3/account/21670324/favorite', [
                'media_type' => 'movie',
                'media_id' => $dados['idFilme'], 
                'favorite' => true,
            ]);

            if ($resposta->successful()) {
                return $resposta->json();
            }

        } catch (\Exception $e) {
            throw new \Exception('Erro na API: ' . $resposta->status());
        }

    }

    public function removerFilmeFavoritado($dados)
    {
        try {

            $resposta = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TMDB_API_TOKEN'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://api.themoviedb.org/3/account/21670324/favorite', [
                'media_type' => 'movie',
                'media_id' => $dados['idFilme'], 
                'favorite' => false,
            ]);

            if ($resposta->successful()) {
                return $resposta->json();
            }

        } catch (\Exception $e) {
            throw new \Exception('Erro na API: ' . $resposta->status());
        }

    }

    public function retornaFilmesFavoritos()
    {
        try {
            $resposta = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TMDB_API_TOKEN'),
                'accept' => 'application/json',
            ])->timeout(30)->get("https://api.themoviedb.org/3/account/21670324/favorite/movies?language=pt-BR&page=1&sort_by=created_at.asc");
    
            if ($resposta->successful()) {
                return $resposta->json();
            }
            
        } catch (\Exception $e) {
            throw new \Exception('Erro na API: ' . $resposta->status());
        }
    }

}
