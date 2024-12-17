<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Legajo</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
        .container { max-width: 800px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; margin-bottom: 20px; color: #007bff; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 15px; border: none; color: #fff; border-radius: 4px; cursor: pointer; }
        .button.save { background-color: #28a745; }
        .button.cancel { background-color: #6c757d; }
    </style>
</head>
<body>
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

            <button type="button" id="buscar_nombre" onclick="buscarNombre()">Buscar Nombre Completo</button>

            <div class="form-group">
                <label for="apellidos_nombres">Apellidos y Nombres</label>
                <input type="text" name="apellidos_nombres" id="apellidos_nombres" disabled>
            </div>

            <div class="form-group">
                <label for="documento_id">Documento</label>
                <select name="documento_id" id="documento_id" required>
                    <!-- Cargar opciones dinámicas -->
                </select>
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
                    <script>
                        const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                        for (let i = 0; i < meses.length; i++) {
                            document.write(`<option value="${i + 1}">${meses[i]}</option>`);
                        }
                    </script>
                </select>
            </div>

            <div class="form-group">
                <label for="emitido">Subir Archivo (Emitido - NOMINAS)</label>
                <input type="file" name="emitido" id="emitido" accept=".pdf">
            </div>
            <div class="form-group" id="emitido_observacion">
                <label for="emitido_observacion">Observación Emitido (NOMINAS)</label>
                <textarea name="emitido_observacion" rows="2"></textarea>
            </div>

            <div class="form-group">
                <label for="subido">Subir Archivo (Subido - RRHH)</label>
                <input type="file" name="subido" id="subido" accept=".pdf">
            </div>

            <div class="form-group" id="subido_observacion">
                <label for="subido_observacion">Observación Subido (RRHH)</label>
                <textarea name="subido_observacion" rows="2"></textarea>
            </div>

            <div class="form-group">
                <label for="fisico">¿Documento Físico? (Recepción)</label>
                <select name="fisico" id="fisico">
                    <option value="1">SI</option>
                    <option value="0">NO</option>
                </select>
            </div>
            <div class="form-group" id="fisico_observacion">
                <label for="fisico_observacion">Observación Físico (Recepción)</label>
                <textarea name="fisico_observacion" rows="2"></textarea>
            </div>

            <button type="submit" class="button save">Guardar</button>
            <a href="/legajo" class="button cancel">Cancelar</a>
        </form>
    </div>

    <script>
        // Obtener el nombre completo desde el API
        async function buscarNombre() {
            const tipoDocumento = document.getElementById("tipo_documento").value;
            const nDocumento = document.getElementById("n_documento").value;

            if (!tipoDocumento || !nDocumento) {
                alert("Debe llenar Tipo Documento y Número Documento.");
                return;
            }

            // Endpoint local de la aplicación
            const url = `/colaboradores/search?tipo_documento=${tipoDocumento}&n_documento=${nDocumento}`;

            try {
                const response = await fetch(url);
                const result = await response.json();

                // Verificar si la respuesta tiene datos válidos
                if (result.data && Array.isArray(result.data) && result.data.length > 0) {
                    // Extraer el campo APELLIDOS_NOMBRES
                    document.getElementById("apellidos_nombres").value = result.data[0].APELLIDOS_NOMBRES;
                } else {
                    alert("No existe trabajador con ese documento.");
                    document.getElementById("apellidos_nombres").value = ""; // Limpiar el campo si no hay datos
                }
            } catch (error) {
                console.error("Error al consultar el endpoint local:", error);
                alert("Error al consultar el nombre.");
            }
        }



        // Cargar opciones del campo DOCUMENTO desde el API
        async function cargarDocumentos() {
            const response = await fetch('/documentos');
            const documentos = await response.json();
            const select = document.getElementById('documento_id');

            documentos.forEach(doc => {
                const option = document.createElement('option');
                option.value = doc.id;
                option.textContent = doc.descripcion;
                select.appendChild(option);
            });
        }

        cargarDocumentos();
    </script>
</body>
</html>
