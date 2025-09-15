<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opciones de Administrador</title>
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
            max-width: 600px;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #34495e;
        }

        .botonera {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 20px;
        }

        .opcion {
            display: block;
            text-decoration: none;
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            background: #3498db;
            color: #fff;
            font-weight: 600;
            transition: 0.2s ease-in-out;
        }

        .opcion:hover {
            background: #2980b9;
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
        <h2>Opciones de Modificación</h2>

        <div class="botonera">
            <a href="{{ route('inventario.index') }}" class="opcion">Ver Inventario</a>
            <a href="{{ route('usuarios.index') }}" class="opcion">Gestión de Usuarios</a>
            <a href="{{ route('home') }}" class="opcion">Volver al Menú Principal</a>
        </div>
    </div>
</body>
</html>
