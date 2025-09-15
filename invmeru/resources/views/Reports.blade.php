<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
            text-align: center;
        }
        label {
            margin: 0 5px;
        }
        select, input {
            margin: 0 10px;
            padding: 5px;
        }
        button {
            padding: 6px 12px;
            margin: 5px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            background: #3498db;
            color: #fff;
        }
        button:hover {
            background: #2980b9;
        }
        #resultado {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #eee;
        }
        .volver {
            display: inline-block;
            margin-top: 15px;
            background: #2ecc71;
        }
        .volver:hover {
            background: #27ae60;
        }
    </style>
</head>
<body>
    <h1>Generar Reportes</h1>

    <form id="formReportes">
        <label for="mes">Mes:</label>
        <select name="mes" id="mes">
            <option value="">-- Todos --</option>
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}">{{ $m }}</option>
            @endfor
        </select>

        <label for="dia">Día:</label>
        <select name="dia" id="dia">
            <option value="">-- Todos --</option>
            @for($d = 1; $d <= 31; $d++)
                <option value="{{ $d }}">{{ $d }}</option>
            @endfor
        </select>

        <label for="anio">Año:</label>
        <input type="number" name="anio" id="anio" min="2000" max="{{ date('Y') }}" value="{{ date('Y') }}">

        <br><br>
        <button type="button" onclick="generarReporte('general')">Reporte General</button>
        <button type="button" onclick="generarReporte('detallado')">Reporte Detallado</button>
    </form>

    <div id="resultado">
        <p><em>Aquí aparecerán los resultados...</em></p>
    </div>

    <a href="{{ route('home') }}">
        <button class="volver">Volver al inicio</button>
    </a>

    <script>
        async function generarReporte(tipo) {
            const formData = new FormData(document.getElementById('formReportes'));
            const data = Object.fromEntries(formData);

            const response = await fetch(`/reports/${tipo}`, {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const resultado = await response.json();
            mostrarResultado(resultado, tipo);
        }

                function mostrarResultado(data, tipo) {
            let html = '';

                if (tipo === 'general') {
                    html += '<table><tr><th>Repuesto</th><th>Entradas</th><th>Salidas</th><th>Existencia</th></tr>';

                    data.resumen.forEach(item => {
                        html += `<tr>
                                    <td>${item.nombre_repuesto}</td>
                                    <td>${item.total_entrada || 0}</td>
                                    <td>${item.total_salida || 0}</td>
                                    <td>${item.existencia || 0}</td>
                                </tr>`;
                    });

                    html += '</table>';
            } else {
                html += '<table><tr><th>Repuesto</th><th>Tipo</th><th>Cantidad</th><th>Fecha</th><th>Responsable</th><th>Descripción</th></tr>';
                data.forEach(item => {
                    html += `<tr>
                                <td>${item.nombre_repuesto}</td>
                                <td>${item.tipo_movimiento}</td>
                                <td>${item.cantidad_movida}</td>
                                <td>${item.fecha_hora}</td>
                                <td>${item.nombre_responsable}</td>
                                <td>${item.departamento_destino}</td>
                            </tr>`;
                });
                html += '</table>';
            }

            document.getElementById('resultado').innerHTML = html || '<p>No se encontraron resultados.</p>';
        }

    </script>
</body>
</html>
