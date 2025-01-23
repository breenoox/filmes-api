<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TmdbController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tmdb', function () {
    return view('tmdb');
});

Route::get('/tmdb/{palavra}', [TmdbController::class, 'procurarFilme']);

Route::get('/tmdb/detalhes/{id}', [TmdbController::class, 'detalhesFilme']);

Route::get('/tmdb/generos/retornaGeneros', [TmdbController::class, 'retornaGeneros']);

Route::get('/api/tmdb', [TmdbController::class, 'index']);

