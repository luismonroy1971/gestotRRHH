<?php include '../Layouts/Header.php'; 
//  /Views/Colaboradores/Index.php
?>

<h2>Lista de Colaboradores</h2>
<a href="/colaboradores/create">Agregar Nuevo</a>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Tipo Documento</th>
            <th>Número Documento</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($colaboradores as $colaborador): ?>
            <tr>
                <td><?= $colaborador['id'] ?></td>
                <td><?= $colaborador['tipo_documento'] ?></td>
                <td><?= $colaborador['n_documento'] ?></td>
                <td><?= $colaborador['apellidos_nombres'] ?></td>
                <td>
                    <a href="/colaboradores/edit/<?= $colaborador['id'] ?>">Editar</a>
                    <a href="/colaboradores/delete/<?= $colaborador['id'] ?>" onclick="return confirm('¿Está seguro?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../Layouts/Footer.php'; ?>
