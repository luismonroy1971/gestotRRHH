<?php include '../Layouts/Header.php'; 
// /Views/Usuarios/Index.php
?>

<h2>Gestión de Usuarios</h2>
<a href="/usuarios/create" class="button">Agregar Nuevo Usuario</a>

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
                <td><?= $usuario['id'] ?></td>
                <td><?= $usuario['nombre_usuario'] ?></td>
                <td><?= $usuario['rol'] ?></td>
                <td>
                    <a href="/usuarios/edit/<?= $usuario['id'] ?>" class="button">Editar</a>
                    <a href="/usuarios/delete/<?= $usuario['id'] ?>" class="button" onclick="return confirm('¿Está seguro?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../Layouts/Footer.php'; ?>
