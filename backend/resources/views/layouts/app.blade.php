<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Películas</title>
    <!-- Aquí puedes incluir tus archivos CSS -->
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
</head>
<body>
    <!-- Aquí puedes incluir un encabezado, menú de navegación, etc. -->
    <nav>
        <!-- Menú de navegación si lo tienes -->
    </nav>

    <div class="container">
        <!-- Contenido principal -->
        @yield('content') <!-- Aquí se insertará el contenido de cada vista -->
    </div>

    
</body>
</html>
