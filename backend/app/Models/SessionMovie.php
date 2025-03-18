<?php

// app/Models/SessionMovie.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionMovie extends Model
{
    use HasFactory;

    protected $table = 'session_movies';


    protected $fillable = [
        'movie_id',
        'fecha',
        'time',
        'es_pelicula_del_dia',
        'es_pelicula_semanal',
    ];

    // Relación con Movie
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}

