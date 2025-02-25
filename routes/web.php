<?php

use App\Http\Controllers\generosController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TmdbController;

Route::get('/', function () {
    return view('tmdb');
});

Route::get('/filmes-favoritados', function () {
    return view('filmesFavoritados');
});

Route::get('/tmdb', [TmdbController::class, 'listagemFilmes']);
Route::get('/tmdb/{palavra}', [TmdbController::class, 'procurarFilme']);
Route::get('/tmdb/generos/retornaGeneros', [generosController::class, 'retornaGeneros']);

Route::post('/favoritar-filme', [TmdbController::class, 'favoritarFilme']);
Route::post('/desfavoritar-filme', [TmdbController::class, 'desfavoritarFilme']);
Route::get('/retorna-filmes-favoritos', [TmdbController::class, 'retornaFilmesFavoritos']);