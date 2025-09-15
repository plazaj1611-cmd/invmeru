<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laravel 12 | Home</title>

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
  <div class="max-w-7xl mx-auto px-4"> 

      <x-alert type="danger">
        <x-slot name="title">
          Bienvenido
        </x-slot>
        Esto es un mensaje de alerta personalizado.
      </x-alert>
  </div>
</body>
</html>