<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Registro</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: 600;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        input[type="text"]:focus {
            border-color: #3498db;
            outline: none;
        }

        .sugerencias {
            background: #f8f9fa;
            border: 1px solid #ccc;
            border-radius: 8px;
            max-height: 150px;
            overflow-y: auto;
            padding: 5px;
            margin-bottom: 15px;
        }

        .sugerencias div {
            padding: 6px;
            cursor: pointer;
        }

        .sugerencias div:hover {
            background-color: #dfe6ed;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 14px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            border: none;
            transition: 0.2s ease-in-out;
            margin-top: 10px;
        }

        .btn-primary {
            background: #e74c3c;
            color: #fff;
        }

        .btn-primary:hover {
            background: #c0392b;
        }

        .btn-secondary {
            background: #95a5a6;
            color: #fff;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-top: 15px;
            display: none;
        }

        .alert.success {
            background: #2ecc71;
            color: #fff;
        }

        .alert.error {
            background: #e74c3c;
            color: #fff;
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
        <h2>Eliminar Registro</h2>

        <form id="eliminarForm">
            <label>Nombre del producto</label>
            <input type="text" name="nombre" id="nombreEliminar" autocomplete="off" required oninput="buscarSimilaresEliminar()">

            <div id="sugerenciasEliminar" class="sugerencias"></div>

            <button type="submit" class="btn btn-primary">Eliminar</button>
        </form>

        <div id="eliminarMsg" class="alert"></div>

        <button onclick="window.location.href='/inventario'" class="btn btn-secondary">Volver</button>
    </div>

    <script>
        // Función para buscar sugerencias
        function buscarSimilaresEliminar() {
            const inputValue = document.getElementById('nombreEliminar').value.trim();
            const suggestionsDiv = document.getElementById('sugerenciasEliminar');
            suggestionsDiv.innerHTML = '';

            if (inputValue.length < 2) return;

            fetch(`/api/repuestos/buscar?term=${encodeURIComponent(inputValue)}`)
                .then(res => res.json())
                .then(similares => {
                    if (similares.length > 0) {
                        similares.forEach(nombre => {
                            const div = document.createElement('div');
                            div.textContent = nombre;
                            div.onclick = () => {
                                document.getElementById('nombreEliminar').value = nombre;
                                suggestionsDiv.innerHTML = '';
                            };
                            suggestionsDiv.appendChild(div);
                        });
                    }
                })
                .catch(() => console.error("Error al buscar sugerencias."));
        }

        // Evento de eliminar
        document.getElementById('eliminarForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const nombre = form.nombre.value.trim();
            const msg = document.getElementById('eliminarMsg');

            msg.style.display = 'block';
            msg.textContent = 'Eliminando repuesto...';
            msg.className = 'alert';

            fetch("{{ route('repuestos.eliminar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ nombre })
            })
            .then(res => res.json())
            .then(data => {
                console.log("Respuesta servidor:", data);
                msg.textContent = data.mensaje;
                msg.className = 'alert';
                if (data.exito) {
                    msg.classList.add('success');
                    form.reset();
                    document.getElementById('sugerenciasEliminar').innerHTML = '';
                } else {
                    msg.classList.add('error');
                }
            })
            .catch(() => {
                msg.textContent = "Error al conectar con el servidor para eliminar el repuesto.";
                msg.classList.add('error');
            });
        });
    </script>
</body>
</html>
