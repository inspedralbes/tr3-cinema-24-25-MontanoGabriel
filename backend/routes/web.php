<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionMovieController;

Route::get('/', function () {
    return view('welcome');
});

// CRUD de sessiones
Route::resource('session-movies', SessionMovieController::class);

