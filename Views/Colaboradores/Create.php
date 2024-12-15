<?php include '../Layouts/Header.php'; 
//  /Views/Colaboradores/Create.php
?>



<h2>Agregar Colaborador</h2>
<form action="/colaboradores" method="POST">
    <label for="tipo_documento">Tipo Documento</label>
    <select name="tipo_documento" id="tipo_documento">
        <?php foreach ($tipos_documento as $tipo): ?>
            <option value="<?= $tipo['id'] ?>"><?= $tipo['descripcion'] ?></option>
        <?php endforeach; ?>
    </select>

    <label for="n_documento">Número Documento</label>
    <input type="text" name="n_documento" id="n_documento" required>

    <label for="apellidos_nombres">Nombre Completo</label>
    <input type="text" name="apellidos_nombres" id="apellidos_nombres" required>

    <button type="submit">Guardar</button>
</form>

<?php include '../Layouts/Footer.php'; ?>
