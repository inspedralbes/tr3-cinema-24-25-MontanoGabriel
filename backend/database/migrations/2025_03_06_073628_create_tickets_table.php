<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario
            $table->foreignId('session_movie_id')->constrained('session_movies')->onDelete('cascade'); // Sesión de la película
            $table->integer('quantity')->default(1); // Número de entradas compradas
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
