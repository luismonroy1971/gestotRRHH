<?php include '../Layouts/Header.php'; 
// /Views/Usuarios/Create.php
?>

<h2>Agregar Usuario</h2>
<form action="/usuarios" method="POST">
    <label for="nombre_usuario">Nombre de Usuario</label>
    <input type="text" name="nombre_usuario" id="nombre_usuario" required>

    <label for="contrasena">Contraseña</label>
    <input type="password" name="contrasena" id="contrasena" required>

    <label for="rol">Rol</label>
    <select name="rol" id="rol" required>
        <option value="NOMINAS">NOMINAS</option>
        <option value="RRHH">RRHH</option>
        <option value="RECEPCION">RECEPCION</option>
    </select>

    <button type="submit">Guardar</button>
</form>

<?php include '../Layouts/Footer.php'; ?>
