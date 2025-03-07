<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TicketController;

// Películas
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movie/{id}', [MovieController::class, 'show']);

// Sesiones
Route::get('/SessionMovies/{movieId}', [SessionController::class, 'getSessionsForMovie']);

// Compra de Entradas
Route::post('/purchase', [TicketController::class, 'purchase']);

