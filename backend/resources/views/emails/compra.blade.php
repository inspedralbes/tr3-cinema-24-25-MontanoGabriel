<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        h1 {
            color: #ff6347;
            text-align: center;
            margin-top: 50px;
        }
        p {
            font-size: 18px;
            text-align: center;
            margin: 10px 0;
        }
        strong {
            color: #008080;
        }
        .poster {
            display: block;
            margin: 20px auto;
            max-width: 100%;
            height: auto;
        }
        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
        }
        .footer a {
            color: #ff6347;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>¡Gracias por tu compra, {{ $nombre }} {{ $apellido }}!</h1>
    <p>¡Qué emoción que hayas decidido disfrutar de <strong>{{ $pelicula }}</strong> con nosotros!</p>
    <p>Tu sesión será el <strong>{{ $horario }}</strong>. ¡Asegúrate de llegar a tiempo!</p>
    <p>Los asientos que has elegido son: <strong>{{ $asientos }}</strong></p>
    <p>El total de tu compra es: <strong>{{ $total_price }} EUR</strong>. ¡Una experiencia que no olvidarás!</p>
    
    @if($url_poster)
        <img src="{{ $url_poster }}" alt="Poster de la película" class="poster">
    @endif

    <div class="footer">
        <p>Gracias por elegir <strong>Cinemix</strong> 🎬</p>
        <p><a href="https://www.cinemix.com">Visítanos en Cinemix.com</a></p>
    </div>
</body>
</html>
