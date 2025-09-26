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
            font-family: 'Poppins', Arial, sans-serif;
            background: #f9fbfd;
            margin: 30px;
            color: #2c3e50;
        }
        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #1a4f8b;
            font-weight: 600;
        }
        form {
            margin-bottom: 25px;
            text-align: center;
            background: #fff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            display: inline-block;
        }
        label {
            margin: 0 6px;
            font-weight: 500;
            color: #34495e;
        }
        select, input {
            margin: 0 8px;
            padding: 8px 10px;
            border: 1px solid #d0d7de;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        select:focus, input:focus {
            border-color: #1a73e8;
            outline: none;
        }
        button {
            padding: 8px 16px;
            margin: 8px 5px;
            cursor: pointer;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            background: #1a73e8;
            color: #fff;
            transition: background 0.3s;
        }
        button:hover {
            background: #155ab6;
        }
        #resultado {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        th, td {
            border: 1px solid #e1e4e8;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background: #f1f5f9;
            font-weight: 600;
            color: #1a4f8b;
        }
        .volver {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
            border-radius: 10px;
            background: #27ae60;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }
        .volver:hover {
            background: #1e8449;
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

    <a href="{{ route('home') }}" class="volver">Volver al inicio</a>

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
