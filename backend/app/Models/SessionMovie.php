<?php

// app/Models/SessionMovie.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionMovie extends Model
{
    use HasFactory;

    protected $table = 'sessionMovies';


    protected $fillable = [
        'movie_id',
        'fecha',
        'time',
        'es_pelicula_del_dia',
        'es_pelicula_semanal',
        'seats',
    ];

    // Relación con Movie
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}

