<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbService
{
    public function listaFilmes($pagina = 1)
    {
        try {
            $resposta = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TMDB_API_TOKEN'),
                'accept' => 'application/json',
            ])->timeout(30)->get("https://api.themoviedb.org/3/movie/popular?language=pt-BR&page={$pagina}");
    
            if ($resposta->successful()) {
                return $resposta->json();
            }
        } catch(\Exception) {
            throw new \Exception('Erro na API: ' . $resposta->status());
        }
    }

    public function detalhesFilme($id)
    {
        try {
            $resposta = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TMDB_API_TOKEN'),
                'accept' => 'application/json',
            ])->timeout(30)->get("https://api.themoviedb.org/3/movie/{$id}?language=pt-BR");
    
            if ($resposta->successful()) {
                return $resposta->json();
            }
        } catch(\Exception) {
            throw new \Exception('Erro na API: ' . $resposta->status());
        }
    }

    public function procurarFilme($palavra)
    {
        try {
            $resposta = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TMDB_API_TOKEN'),
                'accept' => 'application/json',
            ])->timeout(30)->get("https://api.themoviedb.org/3/search/movie?query={$palavra}&include_adult=false&language=pt-BR&page=1");
            
            if ($resposta->successful()) {
                return response()->json($resposta->json());
            } else {
                return response()->json(['error' => 'Erro na API'], 500);
            }
        } catch (\Exception $e) {
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
