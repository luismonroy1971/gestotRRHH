<style>
/* Estilos Generales */
body {
    font-family: "Segoe UI", Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
    color: #333;
}

h1 {
    font-size: 1.8rem;
    color: #343a40;
    margin-bottom: 20px;
}

.container {
    max-width: 1200px;
    margin: 40px auto;
    background: #fff;
    padding: 20px 30px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Botones */
.button {
    display: inline-block;
    padding: 8px 15px;
    font-size: 0.9rem;
    text-decoration: none;
    border: none;
    border-radius: 4px;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-weight: 600;
}

.button.save {
    background-color: #28a745;
    color: white;
}

.button.save:hover {
    background-color: #218838;
}

.button.edit {
    background-color: #ffc107;
    color: #212529;
}

.button.edit:hover {
    background-color: #e0a800;
}

.button.delete {
    background-color: #dc3545;
    color: white;
}

.button.delete:hover {
    background-color: #c82333;
}

.button.cancel {
    background-color: #6c757d;
    color: white;
}

.button.cancel:hover {
    background-color: #5a6268;
}

/* Tabla */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    margin-top: 20px;
}

table thead {
    background-color: #f1f3f5;
}

table th, table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

table th {
    font-weight: bold;
    color: #495057;
}

table tr:nth-child(even) {
    background-color: #f8f9fa;
}

table tr:hover {
    background-color: #e9ecef;
}

table td.actions {
    white-space: nowrap;
}

/* Formularios */
form .form-group {
    margin-bottom: 20px;
}

form label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #495057;
}

form input, form select, form textarea {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ced4da;
    border-radius: 4px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}

form input:focus, form select:focus, form textarea:focus {
    border-color: #80bdff;
    outline: none;
    background-color: #f0f8ff;
}

form button {
    margin-right: 10px;
}

.alert {
    padding: 10px 15px;
    margin-bottom: 20px;
    border-radius: 4px;
    font-size: 0.9rem;
}

.alert.success {
    background-color: #d4edda;
    color: #155724;
}

.alert.error {
    background-color: #f8d7da;
    color: #721c24;
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }

    table th, table td {
        padding: 8px 10px;
    }

    form input, form select, form textarea {
        padding: 8px;
    }

    .button {
        padding: 6px 12px;
        font-size: 0.8rem;
    }
}

</style>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Colaboradores</title>
    <link rel="stylesheet" href="/Styles.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Colaboradores</h1>

        <!-- Mensajes de éxito o error -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert success"><?= htmlspecialchars($_GET['message']) ?></div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <!-- Botón para agregar un nuevo colaborador -->
        <a href="/colaboradores/create" class="button save">Agregar Nuevo Colaborador</a>

        <!-- Tabla de colaboradores -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo Documento</th>
                    <th>Número Documento</th>
                    <th>Nombre</th>
                    <th>Fecha Ingreso</th>
                    <th>Área</th>
                    <th>Correo</th>
                    <th>Aprobador 1</th>
                    <th>Aprobador 2</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($colaboradores)): ?>
                    <?php foreach ($colaboradores as $colaborador): ?>
                        <tr>
                            <td><?= htmlspecialchars($colaborador['ID']) ?></td>
                            <td><?= htmlspecialchars($colaborador['TIPO_DOCUMENTO']) ?></td>
                            <td><?= htmlspecialchars($colaborador['N_DOCUMENTO']) ?></td>
                            <td><?= htmlspecialchars($colaborador['APELLIDOS_NOMBRES']) ?></td>
                            <td><?= htmlspecialchars($colaborador['FECHA_INGRESO']) ?></td>
                            <td><?= htmlspecialchars($colaborador['AREA']) ?></td>
                            <td><?= htmlspecialchars($colaborador['CORREO']) ?></td>
                            <td><?= htmlspecialchars($colaborador['APROBADOR_1']) ?></td>
                            <td><?= htmlspecialchars($colaborador['APROBADOR_2']) ?></td>
                            <td>
                                <a href="/colaboradores/update?id=<?= $colaborador['ID'] ?>" class="button edit">Editar</a>
                                <form action="/colaboradores/delete" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este colaborador?');">
                                    <input type="hidden" name="id" value="<?= $colaborador['ID'] ?>">
                                    <button type="submit" class="button delete">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Mensaje cuando no hay registros -->
                    <tr>
                        <td colspan="10" style="text-align: center;">No hay registros disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
