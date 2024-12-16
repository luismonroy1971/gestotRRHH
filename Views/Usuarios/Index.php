<style>
    /* Estilos sugeridos anteriormente o personalizados */
    .container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 20px;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .container h2 {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 1.5rem;
        color: #495057;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 10px;
    }

    .actions {
        margin-bottom: 20px;
    }

    .actions .button {
        display: inline-block;
        text-decoration: none;
        background: #007bff;
        color: #fff;
        font-weight: 600;
        padding: 10px 15px;
        border-radius: 4px;
        transition: background 0.3s ease;
        margin-right: 10px;
    }

    .actions .button:hover {
        background: #0056b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        border-radius: 5px;
        overflow: hidden;
    }

    table thead {
        background: #f1f3f5;
    }

    table thead th {
        text-align: left;
        padding: 12px 15px;
        font-weight: 600;
        color: #495057;
        border-bottom: 1px solid #dee2e6;
    }

    table tbody tr {
        border-bottom: 1px solid #dee2e6;
    }

    table tbody td {
        padding: 12px 15px;
        color: #333;
    }

    table tbody tr:hover {
        background: #f8f9fa;
    }

    .table-actions a {
        text-decoration: none;
        padding: 6px 10px;
        border-radius: 3px;
        font-weight: 600;
        margin-right: 5px;
        font-size: 0.9rem;
        display: inline-block;
    }

    .table-actions .edit {
        background: #ffc107;
        color: #212529;
    }

    .table-actions .edit:hover {
        background: #e0a800;
    }

    .table-actions .delete {
        background: #dc3545;
        color: #fff;
    }

    .table-actions .delete:hover {
        background: #c82333;
    }
</style>

<div class="container">
    <?php if (isset($_GET['message'])): ?>
        <div id="alert-message" class="alert" style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
            <?= htmlspecialchars($_GET['message']) ?>
        </div>

        <script>
            // Esperar 3 segundos antes de ocultar el mensaje
            setTimeout(function() {
                var alertMessage = document.getElementById('alert-message');
                if (alertMessage) {
                    alertMessage.style.display = 'none';
                }
            }, 3000);
        </script>
    <?php endif; ?>


    <h2>Gestión de Usuarios</h2>
    <div class="actions">
        <a href="/usuarios/create" class="button">Agregar Nuevo Usuario</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['ID']) ?></td>
                    <td><?= htmlspecialchars($usuario['NOMBRE_USUARIO']) ?></td>
                    <td><?= htmlspecialchars($usuario['ROL']) ?></td>
                    <td class="table-actions">
                        <a href="/usuarios/update/<?= urlencode($usuario['ID']) ?>" class="edit">Editar</a>
                        <form action="/usuarios/delete/<?= urlencode($usuario['ID']) ?>"  method="POST" onsubmit="return confirm('¿Está seguro de eliminar este usuario?')" style="display:inline;">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['ID']) ?>">
                            <button type="submit" class="delete">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
