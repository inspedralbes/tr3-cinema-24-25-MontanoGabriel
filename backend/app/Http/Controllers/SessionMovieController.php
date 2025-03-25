<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\SessionMovie;
use Illuminate\Http\Request;

class SessionMovieController extends Controller
{
    // ===================== Funciones para el Frontend (API) =====================

    // Obtener las sesiones del día y semanales
    public function getSessions(Request $request)
{
    // Obtener la película del día
    $peliculaDelDia = SessionMovie::where('es_pelicula_del_dia', true)->first(); 

    
    // Si no hay película del día, tomar la primera disponible (si existe)
    if (!$peliculaDelDia) {
        $peliculaDelDia = SessionMovie::first();  
    }

    // Si sigue sin haber sesiones, devolver JSON vacío
    if (!$peliculaDelDia) {
        return response()->json([
            'movieOfTheDay' => null,
            'weeklyMovies' => []
        ]);
    }

    // Obtener las sesiones semanales (limitar a 7)
    $peliculasSemanales = SessionMovie::where('es_pelicula_semanal', true)->take(7)->get();
    
    // Si no hay películas semanales, tomar las primeras 7 (sin incluir la película del día)
    if ($peliculasSemanales->isEmpty()) {
        $peliculasSemanales = SessionMovie::where('id', '!=', $peliculaDelDia->id)->take(7)->get();
    }

    return response()->json([
        'movieOfTheDay' => $peliculaDelDia,
        'weeklyMovies' => $peliculasSemanales
    ]);
}

    

    // ===================== Funciones para el CRUD (Backend) =====================

    // Mostrar todas las sesiones en el panel de administración
    public function index()
    {
        // Obtener todas las sesiones de películas
        $sessions = SessionMovie::all();
        return view('sessionMovies.index', compact('sessions'));
    }

    // Mostrar formulario para crear una nueva sesión
    public function create()
{
    // Obtener todas las películas disponibles para el formulario
    $movies = Movie::all();  // Si tienes un modelo de Movie y quieres obtener todas las películas.

    // Pasar las películas a la vista
    return view('sessionMovies.create', compact('movies'));
}


    // Crear una nueva sesión de película
    public function store(Request $request)
{
    // Validar los datos del formulario
    $request->validate([
        'movie_id' => 'required|exists:movies,id',
        'fecha' => 'required|date',
        'time' => 'required|date_format:H:i',
    ]);

    // Crear una nueva sesión asegurando que los checkboxes se guardan correctamente
    $session = SessionMovie::create([
        'movie_id' => $request->movie_id,
        'fecha' => $request->fecha,
        'time' => $request->time,
        'es_pelicula_del_dia' => $request->has('es_pelicula_del_dia') ? 1 : 0,  // Guardar como 1 o 0
        'es_pelicula_semanal' => $request->has('es_pelicula_semanal') ? 1 : 0,  // Guardar como 1 o 0
        
    ]);

    return redirect()->route('session-movies.index');
}


    // Mostrar detalles de una sesión específica
    public function show($id)
    {
        $session = SessionMovie::find($id);
        return view('sessionMovies.show', compact('session'));
    }

    // Mostrar formulario para editar una sesión
    public function edit($id)
{
    $sessionMovie = SessionMovie::findOrFail($id);  // Cambié a $sessionMovie
    $movies = Movie::all();

    return view('sessionMovies.edit', compact('sessionMovie', 'movies'));  // Pasando $sessionMovie en lugar de $session
}
    // Actualizar una sesión de película
    public function update(Request $request, $id)
{
    $sessionMovie = SessionMovie::findOrFail($id);

    // Depuración: Verificar qué valores llegan
    \Log::info('Datos recibidos:', $request->all());  // Guarda los datos en storage/logs/laravel.log

    // Convertir "on" a 1 y asegurarse de que siempre sean números enteros
    $sessionMovie->fecha = $request->fecha;
    $sessionMovie->time = $request->time;
    $sessionMovie->es_pelicula_del_dia = filter_var($request->input('es_pelicula_del_dia', 0), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $sessionMovie->es_pelicula_semanal = filter_var($request->input('es_pelicula_semanal', 0), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

    // Guardar y manejar errores
    try {
        $sessionMovie->save();
        return redirect()->route('session-movies.index')->with('success', 'Sesión actualizada correctamente.');
    } catch (\Exception $e) {
        \Log::error('Error al actualizar la sesión: ' . $e->getMessage());  // Guarda el error en logs
        return redirect()->back()->with('error', 'Hubo un error al actualizar la sesión.');
    }
}
    // Eliminar una sesión
    public function destroy($id)
{
    $sessionMovie = SessionMovie::findOrFail($id);
    $sessionMovie->delete();

    return redirect()->route('session-movies.index')->with('success', 'Sesión eliminada correctamente.');
}


}

