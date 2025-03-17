<h1>Gestión de Sesiones de Películas</h1>

<a href="{{ route('session-movies.create') }}">Agregar Nueva Sesión</a>

<table>
    <thead>
        <tr>
            <th>Película</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Película del Día</th>
            <th>Película Semanal</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sessions as $session)
            <tr>
                <td>{{ $session->movie->titulo }}</td>
                <td>{{ $session->fecha }}</td>
                <td>{{ $session->time }}</td>
                <td>
                    {{ $session->es_pelicula_del_dia ? '✅ Sí' : '❌ No' }}
                </td>
                <td>
                    {{ $session->es_pelicula_semanal ? '✅ Sí' : '❌ No' }}
                </td>
                <td>
                    <a href="{{ route('session-movies.edit', $session->id) }}">Editar</a>
                    <form action="{{ route('session-movies.destroy', $session->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
