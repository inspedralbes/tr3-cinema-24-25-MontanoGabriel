<?php

namespace App\Http\Controllers;

use App\Models\SessionMovie;
use Illuminate\Http\Request;

class SessionMovieController extends Controller
{
    public function index()
    {
        // Obtener la película del día
        $peliculaDelDia = SessionMovie::where('es_pelicula_del_dia', true)->first();

        // Si no existe ninguna sesión marcada como película del día, tomar la primera sesión
        if (!$peliculaDelDia) {
            $peliculaDelDia = SessionMovie::first();
        }

        // Si sigue siendo null, no hay sesiones en la base de datos, por lo que retornamos arrays vacíos
    if (!$peliculaDelDia) {
        return response()->json([
            'movieOfTheDay' => null,
            'weeklyMovies' => []
        ]);
    }

        // Obtener las sesiones semanales, limitadas a 7
        $peliculasSemanales = SessionMovie::where('es_pelicula_semanal', true)->take(7)->get();

        // Si no hay sesiones semanales, obtener 7 sesiones que no sean la del día
        if ($peliculasSemanales->isEmpty()) {
            $peliculasSemanales = SessionMovie::where('id', '!=', $peliculaDelDia->id)->take(7)->get();
        }

        // Retornar la respuesta en formato JSON con las sesiones
        return response()->json([
            'movieOfTheDay' => $peliculaDelDia,
            'weeklyMovies' => $peliculasSemanales
        ]);
        return response()->json(['message' => 'API funcionando correctamente']);

    }

    public function show($id)
    {
        // Buscar la sesión de la película por su ID
        $sessionMovie = SessionMovie::find($id);

        // Verificar si la sesión existe
        if (!$sessionMovie) {
            return response()->json(['message' => 'Sesión no encontrada'], 404);
        }

        // Retornar la respuesta de la sesión de la película en formato JSON
        return response()->json([
            'movie_id' => $sessionMovie->movie_id,
            'fecha' => $sessionMovie->Fecha,
            'hora' => $sessionMovie->time,
            'seats' => $sessionMovie->seats,
            'es_pelicula_del_dia' => $sessionMovie->es_pelicula_del_dia,
            'es_pelicula_semanal' => $sessionMovie->es_pelicula_semanal,
        ]);
    }
}
