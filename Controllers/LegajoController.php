<?php

namespace Controllers;

use Models\Legajo;
use Libs\Response;
use Libs\Database;

class LegajoController
{
        public static function getAll()
        {
            $result = Database::query("SELECT * FROM LEGAJO");
            var_dump($result); // Depura para ver los datos que retornan
            return $result;
        }
    
        public static function create()
        {
        session_start();
        $usuarioActual = $_SESSION['user_id'] ?? null;
        $rolUsuario = $_SESSION['role'] ?? null;

        if (!$usuarioActual || !$rolUsuario) {
            return Response::json(['error' => 'Usuario no autenticado.'], 403);
        }

        $data = [
            'tipo_documento' => $_POST['tipo_documento'] ?? null,
            'n_documento' => $_POST['n_documento'] ?? null,
            'documento_id' => $_POST['documento_id'] ?? null,
            'ejercicio' => $_POST['ejercicio'] ?? null,
            'periodo' => $_POST['periodo'] ?? null,
            'observacion' => $_POST['observacion'] ?? null
        ];

        // Subida de archivo y campos adicionales según rol
        if (isset($_FILES['emitido']) && $rolUsuario === 'NOMINAS') {
            $data['emitido'] = self::uploadFile('emitido');
            $data['emitido_usuario'] = $usuarioActual;
            $data['emitido_hora'] = date('Y-m-d H:i:s');
        } elseif (isset($_FILES['subido']) && $rolUsuario === 'RRHH') {
            $data['subido'] = self::uploadFile('subido');
            $data['subido_usuario'] = $usuarioActual;
            $data['subido_hora'] = date('Y-m-d H:i:s');
        }

        if (isset($_POST['fisico']) && $rolUsuario === 'RECEPCION') {
            $data['fisico'] = $_POST['fisico'] ? 1 : 0;
            $data['fisico_usuario'] = $usuarioActual;
            $data['fisico_hora'] = date('Y-m-d H:i:s');
        }

        $result = Legajo::create($data);

        if ($result) {
            return Response::json(['message' => 'Legajo creado correctamente.']);
        } else {
            return Response::json(['error' => 'Error al crear el legajo.']);
        }
    }

    public static function update()
    {
        session_start();
        $usuarioActual = $_SESSION['user_id'] ?? null;
        $rolUsuario = $_SESSION['role'] ?? null;

        if (!$usuarioActual || !$rolUsuario) {
            return Response::json(['error' => 'Usuario no autenticado.'], 403);
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            return Response::json(['error' => 'ID no proporcionado.'], 400);
        }

        $data = [
            'tipo_documento' => $_POST['tipo_documento'] ?? null,
            'n_documento' => $_POST['n_documento'] ?? null,
            'documento_id' => $_POST['documento_id'] ?? null,
            'ejercicio' => $_POST['ejercicio'] ?? null,
            'periodo' => $_POST['periodo'] ?? null,
            'observacion' => $_POST['observacion'] ?? null
        ];

        // Modificar según perfil
        if ($rolUsuario === 'NOMINAS' && isset($_FILES['emitido'])) {
            $data['emitido'] = self::uploadFile('emitido');
            $data['emitido_usuario'] = $usuarioActual;
            $data['emitido_hora'] = date('Y-m-d H:i:s');
        } elseif ($rolUsuario === 'RRHH' && isset($_FILES['subido'])) {
            $data['subido'] = self::uploadFile('subido');
            $data['subido_usuario'] = $usuarioActual;
            $data['subido_hora'] = date('Y-m-d H:i:s');
        } elseif ($rolUsuario === 'RECEPCION' && isset($_POST['fisico'])) {
            $data['fisico'] = $_POST['fisico'] ? 1 : 0;
            $data['fisico_usuario'] = $usuarioActual;
            $data['fisico_hora'] = date('Y-m-d H:i:s');
        }

        $result = Legajo::update($id, $data);

        if ($result) {
            return Response::json(['message' => 'Legajo actualizado correctamente.']);
        } else {
            return Response::json(['error' => 'Error al actualizar el legajo.']);
        }
    }

    private static function uploadFile($inputName)
    {
        $file = $_FILES[$inputName];
        $targetDir = __DIR__ . "/../Uploads/";
        $fileName = time() . "_" . basename($file["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return "/Uploads/" . $fileName; // Devuelve la ruta del archivo
        } else {
            return null; // Manejo de error en la carga
        }
    }

    public static function delete()
    {
        session_start();
        $usuarioActual = $_SESSION['user_id'] ?? null;

        if (!$usuarioActual) {
            return Response::json(['error' => 'Usuario no autenticado.'], 403);
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            return Response::json(['error' => 'ID no proporcionado.'], 400);
        }

        // Intentar eliminar el registro
        $result = Legajo::delete($id);

        if ($result) {
            return Response::json(['message' => 'Legajo eliminado correctamente.']);
        } else {
            return Response::json(['error' => 'Error al eliminar el legajo.']);
        }
    }

}
