<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventario de Repuestos</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f3f6fa;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 400px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        h2 {
            text-align: center;
            color: #3442db;
            margin-bottom: 25px;
        }
        label {
            font-weight: 500;
            margin-top: 10px;
            display: block;
            color: #555;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        button:hover {
            background-color: #00548d;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Logo --}}
        <div class="logo">
            <img src="{{ asset('images/logo1.jpg') }}" alt="Logo de la empresa" width="120">
        </div>
        
        <h2>Iniciar Sesión</h2>

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.procesar') }}">
            @csrf
            <div>
                <label>Usuario:</label>
                <input type="text" name="usuario" required>
            </div>
            <div>
                <label>Contraseña:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Ingresar</button>
        </form>

    </div>
</body>
</html>
