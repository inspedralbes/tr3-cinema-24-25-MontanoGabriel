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
        //  Solicitud a la API
        $response = Http::get($this->apiUrl, [
            'api_key' => $this->apiKey
        ]);

        // Decodificamos la respuesta JSON
        $movies = $response->json()['results'];

        // Insertamos las películas en la base de datos
        foreach ($movies as $movie) {
            Movie::create([
                'titulo'      => $movie['title'],
                'descripcion' => $movie['overview'] ?? null,
                'duracion'    => $movie['runtime'] ?? 90,
                'url_poster'  => isset($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : null,
                
            ]);
        }
    }
}
