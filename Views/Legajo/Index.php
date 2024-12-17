<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    color: #333;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 100%;
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
    <title>Gestión de Legajos</title>
</head>
<body>
    <div class="container">
        <h1>Gestión de Legajos</h1>

        <!-- Mensajes de éxito o error -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert success"><?= htmlspecialchars($_GET['message']) ?></div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <!-- Botón para agregar un nuevo legajo -->
        <a href="/legajo/create" class="button save">Agregar Nuevo Legajo</a>

            <!-- Tabla de legajos -->
    <table style="width: 100%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo Documento</th>
                <th>Número Documento</th>
                <th>Apellidos y Nombres</th>
                <th>Ejercicio</th>
                <th>Periodo</th>
                <th>Emitido</th>
                <th>Subido</th>
                <th>Físico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($legajos)): ?>
                <?php foreach ($legajos as $legajo): ?>
                    <tr>
                        <td><?= htmlspecialchars($legajo['ID']) ?></td>
                        <td><?= htmlspecialchars($legajo['TIPO_DOCUMENTO']) ?></td>
                        <td><?= htmlspecialchars($legajo['N_DOCUMENTO']) ?></td>
                        <td><?= htmlspecialchars($legajo['APELLIDOS_NOMBRES'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($legajo['EJERCICIO']) ?></td>
                        <td><?= htmlspecialchars($legajo['PERIODO'] ?? '-') ?></td>
                        <td>
                            <?php if ($legajo['EMITIDO']): ?>
                                <a href="<?= htmlspecialchars($legajo['EMITIDO']) ?>" target="_blank">Ver</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($legajo['SUBIDO']): ?>
                                <a href="<?= htmlspecialchars($legajo['SUBIDO']) ?>" target="_blank">Ver</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?= $legajo['FISICO'] == 1 ? 'SI' : 'NO' ?></td>
                        <td>
                            <a href="/legajo/update/<?= $legajo['ID'] ?>" class="button edit">Editar</a>
                            <form action="/legajo/delete" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este legajo?');">
                                <input type="hidden" name="id" value="<?= $legajo['ID'] ?>">
                                <button type="submit" class="button delete">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" style="text-align: center;">No hay registros disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    </div>
</body>
</html>




