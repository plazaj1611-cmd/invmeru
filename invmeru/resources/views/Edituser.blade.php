<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 40px; color: #2c3e50; }
        .container { max-width: 500px; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin: auto; }
        h2 { text-align: center; margin-bottom: 15px; color: #34495e; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; }
        .btn { padding: 8px 16px; border-radius: 8px; font-size: 14px; cursor: pointer; border: none; }
        .btn-success { background: #27ae60; color: #fff; }
        .btn-success:hover { background: #1e8449; }
        .btn-secondary { background: #95a5a6; color: #fff; }
        .btn-secondary:hover { background: #7f8c8d; }
        .resultado { margin-top: 10px; padding: 12px; border-radius: 6px; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
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

            <div>
                <label for="pin">Nuevo PIN</label>
                <input type="password" name="pin" id="pin">
            </div>

            <div>
                <label for="pin_confirmation">Confirmar PIN</label>
                <input type="password" name="pin_confirmation" id="pin_confirmation">
            </div>

            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</body>
</html>
