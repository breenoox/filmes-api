<?php

use App\Http\Controllers\generosController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TmdbController;

Route::get('/', function () {
    return view('tmdb');
});

Route::get('/tmdb', [TmdbController::class, 'listagemFilmes']);
Route::get('/tmdb/{palavra}', [TmdbController::class, 'procurarFilme']);
Route::get('/tmdb/generos/retornaGeneros', [generosController::class, 'retornaGeneros']);


