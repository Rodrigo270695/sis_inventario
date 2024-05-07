<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #ecf0f3; /* Color de fondo actualizado */
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background-color: #ecf0f3; /* Color del card actualizado */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 6px 6px 10px #cbced1, -6px -6px 10px white; /* Box-shadow actualizado */
        }
        h1 {
            color: #0056b3; /* Azul oscuro que combina con el logo */
        }
        p {
            color: #666666;
        }
        a {
            display: inline-block;
            background-color: #0056b3;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #003d82;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404 - Página no encontrada</h1>
        <p>Lo sentimos, la página que buscas no existe.</p>
        <a href="{{ url('/') }}">Volver al inicio</a>
    </div>
</body>
</html>
