<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con Usuarios
            // Cambia la referencia a 'session_movies' en lugar de 'sessions'
            $table->foreignId('SessionMovies_id')->constrained('SessionMovies')->onDelete('cascade'); // Relación con Sesiones
            $table->integer('quantity')->default(1); // Número de entradas compradas
            $table->date('date'); // Fecha de la sesión
            $table->timestamps();

            // Restricción única: Un usuario solo puede comprar entradas para UNA película en un día.
            $table->unique(['user_id', 'date'], 'unique_user_movie_per_day');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
