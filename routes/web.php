<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\AssuntoController;
use App\Http\Controllers\AutorController;

// -- Extras --
Route::get('/', [LivroController::class, 'home']);
Route::get('/documentacao', [LivroController::class, 'documentacao']);

// -- Painel --
Route::resource('livros', LivroController::class)->except(['create', 'edit']);
