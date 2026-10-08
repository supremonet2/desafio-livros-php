<?php

use App\Http\Controllers\AssuntoController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\LivroController;
use Illuminate\Support\Facades\Route;

Route::get('relatorio/livros', [LivroController::class, 'buscarRelatorio']);
Route::apiResource('livros', LivroController::class)->except(['create', 'edit']);
Route::apiResource('assuntos', AssuntoController::class)->except(['create', 'edit']);
Route::apiResource('autors', AutorController::class)->except(['create', 'edit']);
