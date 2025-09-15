<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Repuestos</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 40px;
            color: #2c3e50;
        }

        .container {
            max-width: 700px;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin: auto;
            text-align: center;
        }

        .logo img {
            margin-bottom: 20px;
        }

        h2 {
            color: #34495e;
            margin-bottom: 10px;
        }

        h3 {
            color: #5a6d7c;
            margin-bottom: 20px;
        }

        .menu-horizontal {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .menu-horizontal a {
            padding: 12px 20px;
            border-radius: 8px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s ease-in-out;
        }

        .menu-horizontal a:hover {
            background-color: #2980b9;
        }

        .rol-sesion {
            margin-top: 15px;
            font-weight: 600;
        }

        .logout-btn {
            position: absolute;
            top: 15px;
            right: 20px;
        }

        .logout-btn button {
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s ease-in-out;
        }

        .logout-btn button:hover {
            background: #c0392b;
        }

    </style>
</head>
<body>
    <!-- Botón de salida -->
    <div class="logout-btn">
        <button onclick="location.href='{{ route('login') }}'">Salir</button>
    </div>

    <div class="container">
        <div class="logo">
            <img src="{{ asset('images/logo1.jpg') }}" alt="Logo de la empresa" width="150">
        </div>

        <h2>Bienvenido al sistema de gestión de inventario</h2>
        <h3>Seleccione una opción para continuar:</h3>

        <div class="menu-horizontal">
            <a href="{{ route('consultar.producto') }}">Consultar Producto</a>
            <a href="{{ route('reports.index') }}">Reportes</a>
            <a href="{{ route('administrador') }}">Modificación</a>
        </div>
    </div>
</body>
</html>
