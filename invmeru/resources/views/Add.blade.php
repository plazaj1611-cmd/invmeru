<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Stock</title>
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

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        input:focus, textarea:focus, select:focus {
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
        <h2>Agregar Stock</h2>

        <form id="agregarForm">
            <label for="nombreAgregar">Nombre del producto</label>
            <input type="text" name="nombre" id="nombreAgregar" autocomplete="off" required oninput="buscarSimilaresAgregar()">

            <div id="sugerenciasAgregar" class="sugerencias"></div>

            <div id="infoRepuesto" style="display:none; margin-bottom:15px; padding:10px; background:#edf1ec; border-radius:8px;">
                <p><strong>Nombre:</strong> <span id="repNombre"></span></p>
                <p><strong>Descripción:</strong> <span id="repDescripcion"></span></p>
                <p><strong>Marca:</strong> <span id="repMarca"></span></p>
                <p><strong>Stock actual:</strong> <span id="repStock"></span></p>
            </div>


            <label for="cantidad">Cantidad a agregar</label>
            <input type="number" name="cantidad" min="1" required>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Agregar</button>
                <a href="/inventario" class="btn btn-secondary">Volver</a>
            </div>
        </form>

        <div id="agregarMsg" class="alert"></div>
    </div>

    <script>
        const form = document.getElementById('agregarForm');
        const msg = document.getElementById('agregarMsg');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            msg.style.display = 'block';
            msg.className = 'alert';
            msg.textContent = 'Agregando stock...';

            const formData = new FormData(form);

            fetch('/repuestos/agregar', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                msg.style.display = 'block';
                msg.textContent = data.mensaje;
                msg.classList.add(data.exito ? 'success' : 'error');
                if (data.exito) form.reset();
            })
            .catch(() => {
                msg.style.display = 'block';
                msg.textContent = "Error al conectar con el servidor.";
                msg.classList.add('error');
            });
        });

        function buscarSimilaresAgregar() {
            const input = document.getElementById('nombreAgregar');
            const sugerencias = document.getElementById('sugerenciasAgregar');
            const query = input.value.trim();

            if (query.length < 2) {
                sugerencias.innerHTML = '';
                return;
            }

            fetch(`/api/repuestos/buscar?term=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    sugerencias.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.textContent = item;
                            div.style.padding = "6px";
                            div.style.cursor = "pointer";

                            div.onclick = function() {
                                input.value = this.textContent;
                                sugerencias.innerHTML = '';

                                fetch(`/api/repuestos/info?nombre=${encodeURIComponent(this.textContent)}`)
                                    .then(res => res.json())
                                    .then(rep => {
                                        if (rep) {
                                            document.getElementById('infoRepuesto').style.display = 'block';
                                            document.getElementById('repNombre').textContent = rep.nombre;
                                            document.getElementById('repDescripcion').textContent = rep.descripcion ?? 'Sin descripción';
                                            document.getElementById('repMarca').textContent = rep.marca ?? 'N/A';
                                            document.getElementById('repStock').textContent = rep.cantidad ?? 0;
                                        }
                                    })
                                    .catch(err => {
                                        console.error("Error cargando repuesto:", err);
                                        document.getElementById('infoRepuesto').style.display = 'none';
                                    });
                            };

                            sugerencias.appendChild(div);
                        });
                    }
                });
        }
    </script>
</body>
</html>
