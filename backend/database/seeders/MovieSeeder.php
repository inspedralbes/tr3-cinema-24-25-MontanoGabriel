<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Movie;

class MovieSeeder extends Seeder
{
    // API de TMDB
    private $apiKey = 'fdf961e48b3b6bc9aa35095abb5a8d86';
    private $apiUrl = 'https://api.themoviedb.org/3/movie/popular?api_key=fdf961e48b3b6bc9aa35095abb5a8d86&language=es-ES&page=1';

    public function run()
    {
        // Solicitud a la API de películas populares
        $response = Http::get($this->apiUrl);

        // Decodificamos la respuesta JSON
        $movies = $response->json()['results'];

        // Insertamos las películas en la base de datos
        foreach ($movies as $movie) {
            // Obtener el ID de la película para hacer una solicitud adicional de detalles del tráiler
            $movieDetails = Http::get("https://api.themoviedb.org/3/movie/{$movie['id']}/videos", [
                'api_key' => $this->apiKey,
                'language' => 'es-ES',
            ]);

            // Obtener el primer tráiler (si existe)
            $trailerUrl = null;
            $videos = $movieDetails->json()['results'];
            foreach ($videos as $video) {
                if ($video['type'] === 'Trailer') {
                    $trailerUrl = 'https://www.youtube.com/watch?v=' . $video['key'];
                    break;
                }
            }

            // Obtener la calificación de la película
            $rating = $movie['vote_average'] ?? null;

            // Insertamos la película con los detalles completos
            Movie::create([
                'titulo'      => $movie['title'],
                'descripcion' => $movie['overview'] ?? null,
                'duracion'    => $movie['runtime'] ?? 90,
                'url_poster'  => isset($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : null,
                'trailer_url' => $trailerUrl, // Asignamos el URL del tráiler
                'rating'      => $rating,     // Asignamos la calificación
            ]);
        }
    }
}
