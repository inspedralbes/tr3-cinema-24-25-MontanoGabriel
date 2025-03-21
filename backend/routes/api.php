<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SessionMovieController;
use App\Http\Controllers\CompraController;


// Rutas de autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Películas
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movie/{id}', [MovieController::class, 'show']);

// Sesiones
Route::get('/session-movies', [SessionMovieController::class, 'getSessions']); // Para las sesiones del día y semanales
Route::get('/session-movies/{id}', [SessionMovieController::class, 'show']); // Para una sesión específica

// Compra de Entradas
Route::post('/compra', [CompraController::class, 'store']);

// Obtener asientos
Route::get('/asientos/{session_id}', [SessionMovieController::class, 'getAsientos']);


