<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Registro</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .formulario .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        .formulario label {
            font-weight: bold;
            margin-bottom: 6px;
            color: #333;
        }

        .formulario input,
        .formulario textarea,
        .formulario select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .formulario input:focus,
        .formulario textarea:focus,
        .formulario select:focus {
            border-color: #007bff;
            outline: none;
        }

        .botones {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            margin-top: 15px;
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
            color: #fff;
        }

        .btn-primary {
            background: #007bff;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #565e64;
        }

        .alert {
            margin-top: 15px;
            padding: 12px;
            border-radius: 6px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        .hidden {
            display: none;
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

    <div class="form-container">
        <h2>Nuevo Registro</h2>

        <form id="crearForm" class="formulario">
            @csrf

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="form-group">
                <label>Cantidad</label>
                <input type="number" name="cantidad" value="1" min="1" required>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Nombre del Fabricante</label>
                <input type="text" name="nombre_fabricante">
            </div>

            <div class="form-group">
                <label>Estado del Repuesto</label>
                <select name="estado_repuesto">
                    <option value="nuevo">Nuevo</option>
                    <option value="reacondicionado">Reacondicionado</option>
                </select>
            </div>

            <div class="botones">
                <button type="submit" class="btn btn-primary">Guardar Registro</button>
                <a href="/inventario" class="btn btn-secondary">Volver</a>
            </div>
        </form>

        <div id="crearMsg" class="alert hidden"></div>
    </div>

    <script>
        document.getElementById('crearForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            const msg = document.getElementById('crearMsg');
            msg.textContent = 'Guardando repuesto...';
            msg.classList.remove('hidden', 'success', 'error');

            fetch("{{ route('repuestos.store') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('[name=_token]').value },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                msg.textContent = data.mensaje;
                msg.classList.add(data.exito ? 'success' : 'error');
                if (data.exito) form.reset();
            })
            .catch(() => {
                msg.textContent = "Error al conectar con el servidor para crear el repuesto.";
                msg.classList.add('error');
            });
        });
    </script>
</body>
</html>
