<h1>Crear Nueva Sesión</h1>

<form action="{{ route('session-movies.store') }}" method="POST">
    @csrf
    <div>
        <label for="movie_id">Película:</label>
        <select name="movie_id" id="movie_id">
            @foreach($movies as $movie)
                <option value="{{ $movie->id }}">{{ $movie->titulo }}</option>
            @endforeach
        </select>
    </div>
    
    <div>
        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" id="fecha" required>
    </div>

    <div>
    <label for="time">Hora:</label>
    <select name="time" id="time" required>
        <option value="16:00">16:00</option>
        <option value="18:00">18:00</option>
        <option value="20:00">20:00</option>
    </select>
</div>


    <!-- Checkbox para marcar si es película del día -->
    <div>
        <input type="checkbox" name="es_pelicula_del_dia" id="es_pelicula_del_dia">
        <label for="es_pelicula_del_dia">Película del Día</label>
    </div>

    <!-- Checkbox para marcar si es película semanal -->
    <div>
        <input type="checkbox" name="es_pelicula_semanal" id="es_pelicula_semanal">
        <label for="es_pelicula_semanal">Película Semanal</label>
    </div>

    <button type="submit">Crear sesión</button>
</form>

<script>
    document.getElementById("es_pelicula_del_dia").addEventListener("change", function() {
        let semanalCheckbox = document.getElementById("es_pelicula_semanal");
        if (this.checked) {
            semanalCheckbox.checked = true;
        }
    });
</script>
