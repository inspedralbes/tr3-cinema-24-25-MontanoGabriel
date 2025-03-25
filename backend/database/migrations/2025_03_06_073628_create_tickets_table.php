<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
 

        // Tabla de Tickets
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Usuario registrado (puede ser NULL)
            $table->string('name')->nullable();   // Nombre del comprador (si no está registrado)
            $table->string('surname')->nullable(); // Apellido del comprador
            $table->string('email')->nullable();   // Email del comprador
            $table->json('seats'); // Asiento (por ejemplo, 'K7')
            $table->foreignId('session_movie_id')->constrained('session_movies')->onDelete('cascade'); // Sesión de la película
            $table->decimal('total_price', 8, 2); // Precio total de la compra
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
