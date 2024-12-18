<?php

namespace Controllers;

use Models\Legajo;
use Libs\Response;
use Libs\Database;

class LegajoController
{
    public static function getAll()
    {
        session_start();
        $usuarioActual = $_SESSION['user_id'] ?? null;

        if (!$usuarioActual) {
            if (self::isAjaxRequest()) {
                return Response::json(['error' => 'Usuario no autenticado'], 403);
            }
            header('Location: /login');
            exit;
        }

        // Si es una solicitud AJAX, devolver datos JSON
        if (self::isAjaxRequest()) {
            $page = $_GET['page'] ?? 1;
            $perPage = $_GET['per_page'] ?? 10;
            
            $filters = [
                'tipo_documento' => $_GET['tipo_documento'] ?? null,
                'n_documento' => $_GET['n_documento'] ?? null,
                'ejercicio' => $_GET['ejercicio'] ?? null,
                'periodo' => $_GET['periodo'] ?? null,
                'emitido' => isset($_GET['emitido']) ? $_GET['emitido'] === '1' : null,
                'subido' => isset($_GET['subido']) ? $_GET['subido'] === '1' : null,
                'fisico' => isset($_GET['fisico']) ? $_GET['fisico'] === '1' : null
            ];

            $result = Legajo::getAll($page, $perPage, $filters);
            return Response::json($result);
        }

        // Si no es AJAX, mostrar la vista
        require_once __DIR__ . '/../Views/Legajo/Index.php';
    }

    private static function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
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
            'periodo' => $_POST['periodo'] ?? null
        ];
    
        // Subida de archivo y campos adicionales según rol
        if ($rolUsuario === 'NOMINAS' && isset($_FILES['emitido'])) {
            $apellidosNombres = $_POST['apellidos_nombres'] ?? 'Sin_Nombre';
            $documentoDescripcion = $_POST['documento_id'] ?? 'Documento';
            $data['emitido'] = self::uploadFile('emitido', $apellidosNombres, $data['ejercicio'], $data['periodo'], $documentoDescripcion);
            $data['emitido_usuario'] = $usuarioActual;
            $data['emitido_hora'] = date('Y-m-d H:i:s');
            $data['emitido_observacion'] = $_POST['emitido_observacion'] ?? ''; // Asignación de observación
        } elseif ($rolUsuario === 'RRHH' && isset($_FILES['subido'])) {
            $apellidosNombres = $_POST['apellidos_nombres'] ?? 'Sin_Nombre';
            $documentoDescripcion = $_POST['documento_id'] ?? 'Documento';
            $data['subido'] = self::uploadFile('subido', $apellidosNombres, $data['ejercicio'], $data['periodo'], $documentoDescripcion);
            $data['subido_usuario'] = $usuarioActual;
            $data['subido_hora'] = date('Y-m-d H:i:s');
            $data['subido_observacion'] = $_POST['subido_observacion'] ?? ''; // Asignación de observación
        }
    
        if ($rolUsuario === 'RECEPCION' && isset($_POST['fisico'])) {
            $data['fisico'] = $_POST['fisico'] ? 1 : 0;
            $data['fisico_usuario'] = $usuarioActual;
            $data['fisico_hora'] = date('Y-m-d H:i:s');
            $data['fisico_observacion'] = $_POST['fisico_observacion'] ?? ''; // Asignación de observación
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
            'periodo' => $_POST['periodo'] ?? null
        ];
    
        // Modificar según perfil
        if ($rolUsuario === 'NOMINAS' && isset($_FILES['emitido'])) {
            $apellidosNombres = $_POST['apellidos_nombres'] ?? 'Sin_Nombre';
            $documentoDescripcion = $_POST['documento_id'] ?? 'Documento';
            $data['emitido'] = self::uploadFile('emitido', $apellidosNombres, $data['ejercicio'], $data['periodo'], $documentoDescripcion);
            $data['emitido_usuario'] = $usuarioActual;
            $data['emitido_hora'] = date('Y-m-d H:i:s');
            $data['emitido_observacion'] = $_POST['emitido_observacion'] ?? ''; // Observación del usuario
        }
    
        if ($rolUsuario === 'RRHH' && isset($_FILES['subido'])) {
            $apellidosNombres = $_POST['apellidos_nombres'] ?? 'Sin_Nombre';
            $documentoDescripcion = $_POST['documento_id'] ?? 'Documento';
            $data['subido'] = self::uploadFile('subido', $apellidosNombres, $data['ejercicio'], $data['periodo'], $documentoDescripcion);
            $data['subido_usuario'] = $usuarioActual;
            $data['subido_hora'] = date('Y-m-d H:i:s');
            $data['subido_observacion'] = $_POST['subido_observacion'] ?? ''; // Observación del usuario
        }
    
        if ($rolUsuario === 'RECEPCION' && isset($_POST['fisico'])) {
            $data['fisico'] = $_POST['fisico'] ? 1 : 0;
            $data['fisico_usuario'] = $usuarioActual;
            $data['fisico_hora'] = date('Y-m-d H:i:s');
            $data['fisico_observacion'] = $_POST['fisico_observacion'] ?? ''; // Observación del usuario
        }
    
        // Llamada al modelo para actualizar la información
        $result = Legajo::update($id, $data);
    
        if ($result) {
            return Response::json(['message' => 'Legajo actualizado correctamente.']);
        } else {
            return Response::json(['error' => 'Error al actualizar el legajo.']);
        }
    }
        

    private static function uploadFile($inputName, $apellidos_nombres, $ejercicio, $periodo, $documento)
    {
        $file = $_FILES[$inputName];
        $fechaHora = date("YmdHis"); // Fecha y hora en el formato solicitado
        $fileExtension = pathinfo($file["name"], PATHINFO_EXTENSION);
    
        // Crear nombre del archivo con el formato deseado
        $fileName = "{$apellidos_nombres} - {$ejercicio} - {$periodo} - {$documento} - {$fechaHora}.{$fileExtension}";
    
        $targetDir = __DIR__ . "/../Uploads/";
        $targetFile = $targetDir . $fileName;
    
        // Mover el archivo al directorio de destino
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return "/Uploads/" . $fileName; // Retorna la ruta del archivo subido
        }
        return null; // Si hay un error en la subida
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
