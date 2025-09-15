<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Repuestos</title>
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
            position: relative;
        }

        .logo img {
            display: block;
            margin: 0 auto 15px auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
            color: #34495e;
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #7f8c8d;
            font-size: 16px;
            font-weight: normal;
        }

        label {
            font-weight: 600;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        input:focus {
            border-color: #3498db;
            outline: none;
        }

        .autocomplete-list {
            background: #f8f9fa;
            border: 1px solid #ccc;
            border-radius: 8px;
            max-height: 150px;
            overflow-y: auto;
            margin-bottom: 15px;
            padding: 5px;
            display: none;
        }
        .autocomplete-list.show {
            display: block;
        }
        .autocomplete-list div {
            padding: 6px;
            cursor: pointer;
        }
        .autocomplete-list div:hover {
            background: #ecf0f1;
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

        .resultado {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            background: #f8f9fa;
            border: 1px solid #ddd;
        }

        .resultado p {
            margin: 8px 0;
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
        {{-- Logo --}}
        <div class="logo">
            <img src="{{ asset('images/logo1.jpg') }}" alt="Logo de la empresa" width="150">
        </div>

        {{-- Títulos --}}
        <h2>Consulta de repuestos</h2>
        <h3>Escriba el nombre del repuesto que desea consultar</h3>

        {{-- Campo de búsqueda --}}
        <input type="text" id="nombreConsulta" placeholder="Buscar repuesto..." autocomplete="off">
        <div class="autocomplete-list" id="autocomplete-list"></div>

        {{-- Botón de búsqueda --}}
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button id="buscarBtn" class="btn btn-primary">Buscar</button>
        </div>

        {{-- Resultado --}}
        <div id="resultado" class="resultado" style="display: none;"></div>
    </div>

    <script>
    const input = document.getElementById('nombreConsulta');
    const lista = document.getElementById('autocomplete-list');
    const buscarBtn = document.getElementById('buscarBtn');
    const resultadoDiv = document.getElementById('resultado');

    // Autocompletar 
    input.addEventListener('input', function() {
        const query = this.value.trim();
        lista.innerHTML = '';
        lista.classList.remove('show');
        if (query.length < 2) return;

        fetch(`/api/repuestos/buscar?term=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                lista.innerHTML = '';
                if (data.length > 0) {
                    data.forEach((item) => {
                        const nombre = item.nombre ?? item;
                        const div = document.createElement('div');
                        div.textContent = nombre;
                        div.addEventListener('click', function() {
                            input.value = nombre;
                            lista.innerHTML = '';
                            lista.classList.remove('show');
                        });
                        lista.appendChild(div);
                    });
                    lista.classList.add('show');
                }
            });
    });

    document.addEventListener('click', function(e) {
        if (!lista.contains(e.target) && e.target !== input) {
            lista.innerHTML = '';
            lista.classList.remove('show');
        }
    });

    // Buscar repuesto 
    buscarBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const nombre = input.value.trim();
        if (!nombre) return;

        fetch(`/consulta/normal`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ nombre: nombre })
        })
        .then(res => res.json())
        .then(data => {
            resultadoDiv.style.display = 'block';
            if (data) {
                resultadoDiv.innerHTML = `
                    <p><strong>Nombre:</strong> ${data.nombre}</p>
                    <p><strong>Marca:</strong> ${data.nombre_fabricante}</p>
                    <p><strong>Descripción:</strong> ${data.descripcion}</p>
                    <p><strong>Cantidad:</strong> ${data.cantidad}</p>
                    
                    <div style="margin-top: 15px;">
                        <a href="/consulta/normal/${data.id}/retirar?nombre=${encodeURIComponent(data.nombre)}" 
                           class="btn btn-primary">Salida</a>
                    </div>`;
            } else {
                resultadoDiv.innerHTML = `<p>No se encontró el repuesto "${nombre}"</p>`;
            }
        })
        .catch(err => {
            resultadoDiv.style.display = 'block';
            resultadoDiv.innerHTML = `<p style="color:red;">Error al consultar el repuesto.</p>`;
        });
    });
    </script>
</body>
</html>
