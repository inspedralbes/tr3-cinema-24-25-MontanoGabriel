@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h1 class="text-center text-primary mb-4">Editar Sesión de Película</h1>
            <hr>
            
            <form action="{{ route('session-movies.update', $sessionMovie->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Campo de Película -->
                <div class="mb-3">
                    <label for="movie_id" class="form-label fw-bold">Película</label>
                    <input type="text" class="form-control bg-light" value="{{ $sessionMovie->movie->titulo }}" disabled>
                </div>
                
                <!-- Campo de Fecha -->
                <div class="mb-3">
                    <label for="fecha" class="form-label fw-bold">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ $sessionMovie->fecha }}" required>
                </div>
                
                <!-- Campo de Hora -->
                <div class="mb-3">
                    <label for="time" class="form-label fw-bold">Hora</label>
                    <select name="time" id="time" class="form-select" required>
                        <option value="16:00" {{ $sessionMovie->time == '16:00' ? 'selected' : '' }}>16:00</option>
                        <option value="18:00" {{ $sessionMovie->time == '18:00' ? 'selected' : '' }}>18:00</option>
                        <option value="20:00" {{ $sessionMovie->time == '20:00' ? 'selected' : '' }}>20:00</option>
                    </select>
                </div>
                
                <!-- Checkbox de Película del Día -->
                <div class="form-check mb-3">
                    <input type="hidden" name="es_pelicula_del_dia" value="0">
                    <input type="checkbox" name="es_pelicula_del_dia" id="es_pelicula_del_dia" class="form-check-input"
                        {{ $sessionMovie->es_pelicula_del_dia ? 'checked' : '' }}>
                    <label for="es_pelicula_del_dia" class="form-check-label">Película del Día</label>
                </div>
                
                <!-- Checkbox de Película Semanal -->
                <div class="form-check mb-4">
                    <input type="hidden" name="es_pelicula_semanal" value="0">
                    <input type="checkbox" name="es_pelicula_semanal" id="es_pelicula_semanal" class="form-check-input"
                        {{ $sessionMovie->es_pelicula_semanal ? 'checked' : '' }}>
                    <label for="es_pelicula_semanal" class="form-check-label">Película Semanal</label>
                </div>
                
                <!-- Botón de Envío -->
                <button type="submit" class="btn btn-warning w-100 py-2">Actualizar Sesión</button>
            </form>
            
            <!-- Mensaje de No Cambios -->
            @if(session('no_changes'))
                <div class="alert alert-info mt-3 text-center">
                    No se realizaron cambios en la sesión.
                </div>
            @endif
        </div>
    </div>
@endsection
