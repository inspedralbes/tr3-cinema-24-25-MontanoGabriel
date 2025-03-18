<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('session_movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->onDelete('cascade'); // Película
            $table->date('fecha'); // Fecha de la proyección
            $table->time('time'); // Hora de la sesión
            $table->boolean('es_pelicula_del_dia')->default(false); 
            $table->boolean('es_pelicula_semanal')->default(false);
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('SessionMovies');
    }
};

