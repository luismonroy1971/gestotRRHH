<?php
// Verificar si la sesión no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener valores de sesión
$nombreUsuario = $_SESSION['username'] ?? 'Usuario';
$rolUsuario = $_SESSION['role'] ?? 'INVITADO';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Legajo</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #343a40;
            color: #fff;
            padding: 10px 20px;
        }
        .top-bar a { color: #f8d7da; text-decoration: none; font-weight: 600; }
        .top-bar a:hover { color: #fff; }
        .container { max-width: 800px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; margin-bottom: 20px; color: #007bff; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 15px; border: none; color: #fff; border-radius: 4px; cursor: pointer; }
      
        .button.cancel { background-color: #6c757d; }
        .add-button {
            display: inline-block;
            background-color: #059669;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            text-decoration: none;
            margin-bottom: 2rem;
            transition: background-color 0.2s;
        }
        .cancel-button {
            display: inline-block;
            background-color:rgb(236, 48, 23);
            color: white;
            padding: 0.6rem 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
            margin-bottom: 2rem;
            transition: background-color 0.2s;
        }
        .button.save { display: inline-block;
            background-color: #059669;
            color: white;
            padding: 0.6rem 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
            margin-bottom: 2rem;
            transition: background-color 0.2s; 
        }
    </style>
</head>
<body>
    <!-- Barra Superior -->
    <div class="top-bar">
        <a href="/legajo">← Retornar Gestión de Legajos</a>
        <div><?= htmlspecialchars($nombreUsuario) ?> | Perfil: <?= htmlspecialchars($rolUsuario) ?></div>
    </div>

    <div class="container">
        <h1>Agregar Legajo</h1>
        <form action="/legajo/create" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="tipo_documento">Tipo Documento</label>
                <select name="tipo_documento" id="tipo_documento" required>
                    <option value="1">Documento Nacional de Identidad</option>
                    <option value="4">Carné de Extranjería</option>
                </select>
            </div>

            <div class="form-group">
                <label for="n_documento">Número Documento</label>
                <input type="text" name="n_documento" id="n_documento" required>
            </div>

            <button class="add-button type="button" id="buscar_nombre" onclick="buscarNombre()">Buscar Nombre Completo</button>

            <div class="form-group">
                <label for="apellidos_nombres">Apellidos y Nombres</label>
                <input type="text" name="apellidos_nombres" id="apellidos_nombres" disabled>
            </div>

            <!-- Campos comunes -->
            <div class="form-group">
                <label for="documento_id">Documento</label>
                <select name="documento_id" id="documento_id" required></select>
            </div>
            <div class="form-group">
                <label for="ejercicio">Ejercicio</label>
                <select name="ejercicio" id="ejercicio" required>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>
            </div>
            <div class="form-group">
                <label for="periodo">Periodo</label>
                <select name="periodo" id="periodo" required>
                    <?php
                        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                        foreach ($meses as $index => $mes) {
                            echo "<option value='" . ($index + 1) . "'>$mes</option>";
                        }
                    ?>
                </select>
            </div>

            <!-- Campos específicos por perfil -->
            <?php if ($rolUsuario === 'NOMINAS'): ?>
                <div class="form-group">
                    <label for="emitido">Subir Archivo (Emitido)</label>
                    <input type="file" name="emitido" id="emitido" accept=".pdf">
                </div>
                <div class="form-group">
                    <label for="emitido_observacion">Observación Emitido</label>
                    <textarea name="emitido_observacion" id="emitido_observacion" rows="2"></textarea>
                </div>
            <?php elseif ($rolUsuario === 'RRHH'): ?>
                <div class="form-group">
                    <label for="subido">Subir Archivo (Subido)</label>
                    <input type="file" name="subido" id="subido" accept=".pdf">
                </div>
                <div class="form-group">
                    <label for="subido_observacion">Observación Subido</label>
                    <textarea name="subido_observacion" id="subido_observacion" rows="2"></textarea>
                </div>
            <?php elseif ($rolUsuario === 'RECEPCION'): ?>
                <div class="form-group">
                    <label for="fisico">Documento Físico</label>
                    <select name="fisico" id="fisico" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fisico_observacion">Observación Física</label>
                    <textarea name="fisico_observacion" id="fisico_observacion" rows="2"></textarea>
                </div>
            <?php endif; ?>

            <button type="submit" class="button save">Guardar</button>
            <a href="/legajo" class="cancel-button">Cancelar</a>
        </form>
    </div>

    <script>
        async function buscarNombre() {
            const tipoDocumento = document.getElementById("tipo_documento").value;
            const nDocumento = document.getElementById("n_documento").value;
            const url = `/colaboradores/search?tipo_documento=${tipoDocumento}&n_documento=${nDocumento}`;
            try {
                const response = await fetch(url);
                const result = await response.json();
                if (result.data && result.data.length > 0) {
                    document.getElementById("apellidos_nombres").value = result.data[0].APELLIDOS_NOMBRES;
                } else {
                    alert("No existe trabajador con ese documento.");
                    document.getElementById("apellidos_nombres").value = "";
                }
            } catch (error) {
                alert("Error al consultar nombre.");
                console.error(error);
            }
        }

        async function cargarDocumentos() {
            const response = await fetch('/documentos/list');
            const result = await response.json();
            const select = document.getElementById('documento_id');
            select.innerHTML = '<option value="">Seleccione un documento</option>';
            result.data.forEach(doc => {
                const option = document.createElement('option');
                option.value = doc.ID;
                option.textContent = `${doc.DESCRIPCION} (${doc.CODIGO})`;
                select.appendChild(option);
            });
        }

        cargarDocumentos();
    </script>
</body>
</html>
