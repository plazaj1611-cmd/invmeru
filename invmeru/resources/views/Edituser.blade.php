<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #f9fbfd;
            margin: 0;
            padding: 40px;
            color: #2c3e50;
        }
        .container {
            max-width: 480px;
            background: #fff;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin: auto;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1a4f8b;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #34495e;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d0d7de;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input:focus {
            border-color: #1a73e8;
            outline: none;
        }
        .btn {
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-success {
            background: #1a73e8;
            color: #fff;
        }
        .btn-success:hover {
            background: #155ab6;
        }
        .btn-secondary {
            background: #95a5a6;
            color: #fff;
        }
        .btn-secondary:hover {
            background: #7f8c8d;
        }
        .resultado {
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 8px;
        }
        .error {
            background: #fdecea;
            color: #c0392b;
            border: 1px solid #f5c6cb;
        }
        .acciones {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Usuario</h2>

        @if ($errors->any())
            <div class="resultado error">
                <ul style="margin:0; padding:0; list-style:none;">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('usuarios.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="usuario" value="{{ old('usuario', $user->usuario) }}" required>
            </div>

            <div class="form-group">
                <label for="pin">Nuevo PIN</label>
                <input type="password" name="pin" id="pin">
            </div>

            <div class="form-group">
                <label for="pin_confirmation">Confirmar PIN</label>
                <input type="password" name="pin_confirmation" id="pin_confirmation">
            </div>

            <div class="acciones">
                <button type="submit" class="btn btn-success">Actualizar</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
</body>
</html>
