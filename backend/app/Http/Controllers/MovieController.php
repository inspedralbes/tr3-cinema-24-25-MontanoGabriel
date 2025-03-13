<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        // Obtener todas las películas
        $movies = Movie::all();

        // Retornar la respuesta en formato JSON con todas las películas
        return response()->json([
            'movies' => $movies
        ]);
    }

    public function show($id)
    {
        // Buscar la película por su ID
        $movie = Movie::find($id);

        // Verificar si la película existe
        if (!$movie) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        // Retornar la respuesta de la película en formato JSON
        return response()->json([
            'titulo' => $movie->titulo,
            'descripcion' => $movie->descripcion,
            'duracion' => $movie->duracion,
            'url_poster' => $movie->url_poster,
        ]);
    }
}
