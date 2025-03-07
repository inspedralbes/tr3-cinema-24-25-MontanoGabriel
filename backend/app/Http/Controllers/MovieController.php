<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        // Consulta en la base de datos la pelicula que tenga la coluna de es_pelicula_del_dia en true y selecciona solo la primera
        $peliculaDelDia = Movie::where('es_pelicula_del_dia', true)->first();

        // Si no hay ninguna pelicula del dia marcada conje la primera pelicula de la base de datos
        if (!$peliculaDelDia) {
            $peliculaDelDia = Movie::first();
        }

        // consulta en la base de datos la pelicula que tenga es_pelicula_semanal en true y selecciona 7
        $peliculasSemanales = Movie::where('es_pelicula_semanal', true)->take(7)->get();

        // Si no hay películas marcadas como semanales, tomamos 7 películas diferentes a la del día
        if ($peliculasSemanales->isEmpty()) {
            $peliculasSemanales = Movie::where('id', '!=', $peliculaDelDia->id)->take(7)->get();
        }

        // Retornamos un JSON
        return response()->json([
        'movieOfTheDay' => $peliculaDelDia,
        'weeklyMovies' => $peliculasSemanales
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

    // Verifica si los valores existen y muestra la película
    return response()->json([
        'titulo' => $movie->titulo,
        'descripcion' => $movie->descripcion,
        'duracion' => $movie->duracion,
        'url_poster' => $movie->url_poster,
    ]);
}

}
