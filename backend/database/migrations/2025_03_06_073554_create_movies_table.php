<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->integer('duracion'); 
            $table->string('url_poster')->nullable();
            $table->string('trailer_url')->nullable(); // Para la URL del trailer
            $table->float('rating')->nullable();      // Para la calificación (float puede ser adecuado)
         
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }
};
