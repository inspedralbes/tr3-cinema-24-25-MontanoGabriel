<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('asientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_movie_id')->constrained('session_movies')->onDelete('cascade'); // Relación con la sesión
            $table->string('row'); // Letra de la fila (A, B, C...)
            $table->integer('asiento_number'); // Número de asiento (1, 2, 3...)
            $table->enum('status', ['available', 'occupied'])->default('available'); // Estado del asiento
            $table->timestamps();
            $table->unique(['session_movie_id', 'row', 'asiento_number']); // Evita duplicados
        });
        

        Schema::create('ticket_asientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade'); // Ticket
            $table->foreignId('asiento_id')->constrained('asientos')->onDelete('cascade'); // Asiento comprado
            $table->timestamps();
        
            $table->unique(['ticket_id', 'asiento_id']); // Evita duplicados
        });
        
        
        
    }

    
    public function down(): void
    {
        Schema::dropIfExists('asientos');
    }
};
