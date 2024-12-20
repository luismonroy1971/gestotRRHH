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
    <title>Editar Legajo</title>
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
    <div class="top-bar">
        <a href="/legajo">← Retornar Gestión de Legajos</a>
        <div><?= htmlspecialchars($nombreUsuario) ?> | Perfil: <?= htmlspecialchars($rolUsuario) ?></div>
    </div>

    <div class="container">
        <h1>Editar Legajo</h1>
        <form action="/legajo/update" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($legajo['id']) ?>">
            
            <!-- Campos comunes (readonly) -->
            <div class="form-group">
                <label for="tipo_documento">Tipo Documento</label>
                <input type="text" id="tipo_documento" value="<?= $legajo['tipo_documento'] === '1' ? 'DNI' : 'CE' ?>" readonly>
                <input type="hidden" name="tipo_documento" value="<?= htmlspecialchars($legajo['tipo_documento']) ?>">
            </div>

            <div class="form-group">
                <label for="n_documento">Número Documento</label>
                <input type="text" id="n_documento" value="<?= htmlspecialchars($legajo['n_documento']) ?>" readonly>
                <input type="hidden" name="n_documento" value="<?= htmlspecialchars($legajo['n_documento']) ?>">
            </div>

            <div class="form-group">
                <label for="apellidos_nombres">Apellidos y Nombres</label>
                <input type="text" id="apellidos_nombres" value="<?= htmlspecialchars($legajo['apellidos_nombres']) ?>" readonly>
            </div>

            <div class="form-group">
                <label for="documento_id">Documento</label>
                <input type="text" id="documento_id" value="<?= htmlspecialchars($documentoDescripcion) ?>" readonly>
                <input type="hidden" name="documento_id" value="<?= htmlspecialchars($legajo['documento_id']) ?>">
            </div>

            <div class="form-group">
                <label for="ejercicio">Ejercicio</label>
                <input type="text" id="ejercicio" value="<?= htmlspecialchars($legajo['ejercicio']) ?>" readonly>
                <input type="hidden" name="ejercicio" value="<?= htmlspecialchars($legajo['ejercicio']) ?>">
            </div>

            <div class="form-group">
                <label for="periodo">Periodo</label>
                <input type="text" id="periodo" value="<?= $meses[$legajo['periodo']-1] ?>" readonly>
                <input type="hidden" name="periodo" value="<?= htmlspecialchars($legajo['periodo']) ?>">
            </div>

            <?php if ($rolUsuario === 'RRHH'): ?>
                <div class="form-group">
                    <label for="subido">Subir Archivo (Subido)</label>
                    <input type="file" name="subido" id="subido" accept=".pdf">
                    <?php if ($legajo['subido']): ?>
                        <a href="<?= htmlspecialchars($legajo['subido']) ?>" target="_blank">Ver archivo actual</a>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="subido_observacion">Observación Subido</label>
                    <textarea name="subido_observacion" id="subido_observacion" rows="2"><?= htmlspecialchars($legajo['subido_observacion']) ?></textarea>
                </div>
            <?php elseif ($rolUsuario === 'RECEPCION'): ?>
                <div class="form-group">
                    <label for="fisico">Documento Físico</label>
                    <select name="fisico" id="fisico">
                        <option value="1" <?= $legajo['fisico'] == 1 ? 'selected' : '' ?>>Sí</option>
                        <option value="0" <?= $legajo['fisico'] == 0 ? 'selected' : '' ?>>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fisico_observacion">Observación Física</label>
                    <textarea name="fisico_observacion" id="fisico_observacion" rows="2"><?= htmlspecialchars($legajo['fisico_observacion']) ?></textarea>
                </div>
            <?php endif; ?>

            <button type="submit" class="button save">Guardar Cambios</button>
            <a href="/legajo" class="cancel-button">Cancelar</a>
        </form>
    </div>
</body>
</html>
