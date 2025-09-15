<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Usuarios</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 40px;
            color: #2c3e50;
        }

        .container {
            max-width: 800px;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
            color: #34495e;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            border: none;
            transition: 0.2s ease-in-out;
        }

        .btn-primary {
            background: #3498db;
            color: #fff;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #95a5a6;
            color: #fff;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .btn-danger {
            background: #e74c3c;
            color: #fff;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background: #3498db;
            color: #fff;
        }

        .resultado {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            text-align: center;
        }

        .success {
            background: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
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
        <h2>Administrar Usuarios</h2>

        {{-- Mensajes de éxito o error --}}
        @if(session('success'))
            <div class="resultado success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="resultado error">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="resultado error">
                <ul style="margin: 0; padding: 0; list-style: none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Botones --}}
        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-bottom: 15px;">
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Crear Usuario</a>
            <a href="{{ route('administrador') }}" class="btn btn-secondary">Volver</a>
        </div>

        {{-- Tabla de usuarios --}}
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->usuario }}</td>
                    <td>{{ $user->rol }}</td>
                    <td>
                        <a href="{{ route('usuarios.edit', $user) }}" class="btn btn-secondary">Editar</a>
                        <form action="{{ route('usuarios.destroy', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
