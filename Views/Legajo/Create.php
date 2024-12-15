<?php include '../Layouts/Header.php'; 
// /Views/Legajo/Create.php

?>

<h2>Registrar Documento en Legajo</h2>

<form action="/legajo" method="POST" enctype="multipart/form-data">
    <label for="tipo_documento">Tipo Documento</label>
    <select name="tipo_documento" id="tipo_documento" <?= $usuario['rol'] === 'RECEPCION' ? 'disabled' : '' ?>>
        <?php foreach ($tipos_documento as $tipo): ?>
            <option value="<?= $tipo['id'] ?>"><?= $tipo['descripcion'] ?></option>
        <?php endforeach; ?>
    </select>

    <label for="n_documento">Número Documento</label>
    <input type="text" name="n_documento" id="n_documento" <?= $usuario['rol'] === 'RECEPCION' ? 'disabled' : '' ?> required>

    <label for="documento_id">Documento</label>
    <select name="documento_id" id="documento_id" <?= $usuario['rol'] === 'RECEPCION' ? 'disabled' : '' ?>>
        <?php foreach ($documentos as $doc): ?>
            <option value="<?= $doc['id'] ?>"><?= $doc['descripcion'] ?></option>
        <?php endforeach; ?>
    </select>

    <label for="ejercicio">Ejercicio</label>
    <select name="ejercicio" id="ejercicio" <?= $usuario['rol'] === 'RECEPCION' ? 'disabled' : '' ?>>
        <option value="2024">2024</option>
        <option value="2025">2025</option>
    </select>

    <label for="periodo">Periodo</label>
    <select name="periodo" id="periodo" <?= $usuario['rol'] === 'RECEPCION' ? 'disabled' : '' ?>>
        <option value="01">Enero</option>
        <option value="02">Febrero</option>
        <!-- ...otros meses -->
    </select>

    <?php if ($usuario['rol'] === 'NOMINAS' || $usuario['rol'] === 'RRHH'): ?>
        <label for="emitido">Subir Archivo PDF</label>
        <input type="file" name="emitido" id="emitido" accept="application/pdf">
    <?php endif; ?>

    <?php if ($usuario['rol'] === 'RRHH' || $usuario['rol'] === 'RECEPCION'): ?>
        <label for="fisico">¿Documento Físico?</label>
        <input type="checkbox" name="fisico" id="fisico" value="1">
    <?php endif; ?>

    <label for="observacion">Observación</label>
    <textarea name="observacion" id="observacion" rows="5"></textarea>

    <button type="submit">Guardar</button>
</form>

<?php include '../Layouts/Footer.php'; ?>
