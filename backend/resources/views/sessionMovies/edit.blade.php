@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Sesión de Película</h1>

        <!-- Formulario para editar la sesión -->
        <form action="{{ route('session-movies.update', $sessionMovie->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Mostrar el nombre de la película que ya está seleccionada -->
            <div class="form-group">
                <label for="movie_id">Película</label>
                <input type="text" class="form-control" value="{{ $sessionMovie->movie->titulo }}" disabled>
            </div>

            <!-- Campo para cambiar la fecha -->
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="form-control" value="{{ $sessionMovie->fecha }}" required>
            </div>

            <!-- Campo para cambiar la hora -->
            <div class="form-group">
    <label for="time">Hora</label>
    <select name="time" id="time" class="form-control" required>
        <option value="16:00" {{ $sessionMovie->time == '16:00' ? 'selected' : '' }}>16:00</option>
        <option value="18:00" {{ $sessionMovie->time == '18:00' ? 'selected' : '' }}>18:00</option>
        <option value="20:00" {{ $sessionMovie->time == '20:00' ? 'selected' : '' }}>20:00</option>
    </select>
</div>


            <!-- Checkbox para marcar si es película del día -->
<input type="hidden" name="es_pelicula_del_dia" value="0"> 
<input type="checkbox" name="es_pelicula_del_dia" id="es_pelicula_del_dia" class="form-check-input"
    {{ $sessionMovie->es_pelicula_del_dia ? 'checked' : '' }}>
<label for="es_pelicula_del_dia" class="form-check-label">Película del Día</label>

<!-- Checkbox para marcar si es película semanal -->
<input type="hidden" name="es_pelicula_semanal" value="0"> 
<input type="checkbox" name="es_pelicula_semanal" id="es_pelicula_semanal" class="form-check-input"
    {{ $sessionMovie->es_pelicula_semanal ? 'checked' : '' }}>
<label for="es_pelicula_semanal" class="form-check-label">Película Semanal</label>


            <button type="submit" class="btn btn-warning mt-3">Actualizar Sesión</button>
        </form>

        <!-- Mensaje indicando que no se ha realizado ningún cambio (opcional) -->
        @if(session('no_changes'))
            <div class="alert alert-info mt-3">
                No se realizaron cambios en la sesión.
            </div>
        @endif
    </div>
@endsection
