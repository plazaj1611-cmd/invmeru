<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #2c3e50;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #34495e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #3498db;
            color: #fff;
        }
        tr:hover {
            background: #f1f1f1;
        }
        .btn {
            display: inline-block;
            padding: 8px 14px;
            margin-top: 15px;
            border-radius: 6px;
            text-decoration: none;
            background: #3498db;
            color: white;
        }
        .btn:hover {
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
        <h2>Inventario de Repuestos</h2>

          <a href="/admin/crear_registro" class="btn">Crear Nuevo</a>
          <a href="/admin/agregar_existencia" class="btn">Agregar</a>
          <a href="/admin/eliminar_registro" class="btn">Eliminar</a>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Marca</th>
                    <th>Existencia</th>
                    <th>Ultima Modificación</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($repuestos as $rep)
                    <tr>
                        <td>{{ $rep->nombre }}</td>
                        <td>{{ $rep->descripcion }}</td>
                        <td>{{ $rep->estado_repuesto }}</td>
                        <td>{{ $rep->nombre_fabricante }}</td>
                        <td>{{ $rep->cantidad }}</td>
                        <td>{{ $rep->updated_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="/admin" class="btn">Volver al panel</a>
    </div>
</body>
</html>
