<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    // Especifica el nombre de la tabla
    protected $table = 'movies';

    // Actualiza los campos rellenables según la migración
    protected $fillable = [
        'titulo', 'descripcion', 'duracion', 'url_poster', 'es_pelicula_del_dia', 'es_pelicula_semanal'
    ];
}
