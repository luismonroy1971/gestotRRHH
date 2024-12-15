<?php include '../Layouts/Header.php'; 
//  /Views/Documentos/Index.php
?>



<h2>Lista de Documentos</h2>
<a href="/documentos/create">Agregar Nuevo</a>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Descripción</th>
            <th>Categoría</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($documentos as $documento): ?>
            <tr>
                <td><?= $documento['id'] ?></td>
                <td><?= $documento['codigo'] ?></td>
                <td><?= $documento['descripcion'] ?></td>
                <td><?= $documento['categoria'] ?></td>
                <td>
                    <a href="/documentos/edit/<?= $documento['id'] ?>">Editar</a>
                    <a href="/documentos/delete/<?= $documento['id'] ?>" onclick="return confirm('¿Está seguro?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../Layouts/Footer.php'; ?>
