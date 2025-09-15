<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Retiro</title>
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
        .field-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        input:focus, textarea:focus {
            border-color: #3498db;
            outline: none;
        }
        .btn {
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
            cursor: pointer;
            border: none;
            transition: 0.2s ease-in-out;
            margin-top: 10px;
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
        #msgSalida {
            margin-top: 15px;
            padding: 12px;
            border-radius: 8px;
            display: none;
        }
        #msgSalida.success {
            background: #2ecc71;
            color: #fff;
        }
        #msgSalida.error {
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
        <h2>Retiro de productos</h2>

        <form id="retiroForm">
            <input type="hidden" id="repuesto_id" name="repuesto_id" value="{{ $repuesto->id }}" required />
            <input type="hidden" id="stock_actual" value="{{ $repuesto->stock }}" />

            <div class="field-group">
                <label for="nombre_repuesto">Producto seleccionado:</label>
                <input type="text" id="nombre_repuesto" value="{{ $repuesto->nombre }}" readonly />
            </div>

            <div class="field-group">
                <label for="cantidadSalida">Cantidad a retirar:</label>
                <input type="number" id="cantidadSalida" name="cantidad" min="1" required />
            </div>

            <div class="field-group">
                <label for="descripcion">Nota de retiro:</label>
                <textarea id="descripcion" name="descripcion" rows="2"
                          placeholder="Ej: Entregado a (Responsable) al área de (Departamento)"></textarea>
            </div>

            <button type="submit" class="btn btn-primary"> Realizar Retiro</button>
        </form>

        <div id="msgSalida"></div>

        <button onclick="location.href='{{ route('consulta.normal') }}'" class="btn btn-secondary">
            Ir a Consulta
        </button>
    </div>

    <script>
        const form = document.getElementById('retiroForm');
        const msg = document.getElementById('msgSalida');
        const stockActual = parseInt(document.getElementById('stock_actual').value);

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const cantidad = parseInt(form.cantidad.value);
            const restante = stockActual - cantidad;

            // ⚠ Validación de stock bajo
            if (restante <= 5) {
                if (!confirm("⚠ Atención: este retiro dejará el stock en " + restante +
                             " unidades. ¿Desea continuar?")) {
                    return; // ❌ cancela si el usuario no acepta
                }
            }

            const data = {
                repuesto_id: form.repuesto_id.value,
                cantidad: form.cantidad.value,
                descripcion: form.descripcion.value
            };

            msg.style.display = 'block';
            msg.textContent = 'Procesando retiro...';
            msg.className = '';
            fetch('{{ route("retirar.normal") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(async res => {
                const json = await res.json();
                if (!res.ok) throw json;
                msg.textContent = json.message;
                msg.className = 'success';
                form.reset();
            })
            .catch(err => {
                msg.textContent = err.message || 'Error al conectar con el servidor.';
                msg.className = 'error';
            });
        });
    </script>
</body>
</html>
