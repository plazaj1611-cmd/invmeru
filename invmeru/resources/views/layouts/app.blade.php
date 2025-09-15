<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema Inventario')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS global personalizado --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-light">
    <div class="container py-4">

        {{-- Cabecera dinámica (opcional en cada vista) --}}
        <header class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                @yield('header')
            </div>
            <div>
                <a href="{{ route('login') }}" class="btn btn-danger btn-sm">
                    Salir
                </a>
            </div>
        </header>

        {{-- Contenido principal --}}
        <main>
            <div class="card">
                <div class="card-body">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    {{-- Bootstrap JS (con Popper incluido) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
