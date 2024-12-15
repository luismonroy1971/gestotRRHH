<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav li {
            margin: 10px 0;
        }

        nav a {
            text-decoration: none;
            color: #007bff;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .logout {
            margin-top: 20px;
        }

        .logout a {
            color: #d9534f;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Panel de Administración</h1>
    <nav>
        <ul>
            <li><a href="/usuarios">Administrar Usuarios</a></li>
            <li><a href="/documentos">Administrar Documentos</a></li>
            <li><a href="/colaboradores">Administrar Colaboradores</a></li>
            <li><a href="/legajo">Administrar Legajos</a></li>
        </ul>
    </nav>
    <div class="logout">
        <a href="/logout">Cerrar Sesión</a>
    </div>
</body>
</html>
