<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    color: #333;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #007bff;
}

.button {
    padding: 10px 15px;
    border: none;
    color: #fff;
    border-radius: 4px;
    text-decoration: none;
    cursor: pointer;
}

.button.save { background-color: #28a745; }
.button.cancel { background-color: #6c757d; }
.button.edit { background-color: #ffc107; color: #212529; }
.button.delete { background-color: #dc3545; }

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

th {
    background-color: #007bff;
    color: white;
}

.alert {
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    text-align: center;
}

.alert.success { background-color: #d4edda; color: #155724; }
.alert.error { background-color: #f8d7da; color: #721c24; }

</style>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Legajo</title>
</head>
<body>
    <div class="container">
        <h1>Agregar Legajo</h1>

        <form action="/legajo/create" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="tipo_documento">Tipo Documento</label>
                <input type="text" name="tipo_documento" id="tipo_documento" required>
            </div>

            <div class="form-group">
                <label for="n_documento">Número Documento</label>
                <input type="text" name="n_documento" id="n_documento" required>
            </div>

            <div class="form-group">
                <label for="documento_id">Documento</label>
                <select name="documento_id" id="documento_id" required>
                    <!-- Opciones dinámicas -->
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
                <label for="emitido">Subir Archivo (Emitido - NOMINAS)</label>
                <input type="file" name="emitido" id="emitido" accept="application/pdf">
            </div>

            <div class="form-group">
                <label for="subido">Subir Archivo (Subido - RRHH)</label>
                <input type="file" name="subido" id="subido" accept="application/pdf">
            </div>

            <div class="form-group">
                <label for="fisico">¿Documento Físico? (Recepción)</label>
                <select name="fisico" id="fisico">
                    <option value="1">SI</option>
                    <option value="0">NO</option>
                </select>
            </div>

            <div class="form-group">
                <label for="observacion">Observación</label>
                <textarea name="observacion" id="observacion" rows="4"></textarea>
            </div>

            <button type="submit" class="button save">Guardar</button>
            <a href="/legajo" class="button cancel">Cancelar</a>
        </form>
    </div>
</body>
</html>
