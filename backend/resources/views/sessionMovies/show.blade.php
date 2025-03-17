<!-- resources/views/sessionMovies/show.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detalles de la Sesión</h1>

        <p><strong>Película:</strong> {{ $session->movie->title }}</p>
        <p><strong>Fecha:</strong> {{ $session->Fecha }}</p>
        <p><strong>Hora:</strong> {{ $session->time }}</p>
        <p><strong>Asientos:</strong> {{ json_encode($session->seats) }}</p>

        <a href="{{ route('session-movies.index') }}" class="btn btn-primary">Volver</a>
    </div>
@endsection
