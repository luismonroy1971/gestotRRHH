<?php

namespace Controllers;

use Models\Legajo;
use Libs\Response;
use Libs\Database;
use Exception;

class LegajoController
{
    public static function getAll()
    {
        session_start();
        $usuarioActual = $_SESSION['user_id'] ?? null;
    
        if (!$usuarioActual) {
            if (self::isAjaxRequest()) {
                header('HTTP/1.1 401 Unauthorized');
                return Response::json(['error' => 'Usuario no autenticado']);
            }
            header('Location: /login');
            exit;
        }
    
        // Si es una solicitud AJAX, devolver datos JSON
        if (self::isAjaxRequest()) {
            try {
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
                
                $filters = [
                    'tipo_documento' => $_GET['tipo_documento'] ?? null,
                    'n_documento' => $_GET['n_documento'] ?? null,
                    'apellidos_nombres' => $_GET['apellidos_nombres'] ?? null,
                    'ejercicio' => $_GET['ejercicio'] ?? null,
                    'periodo' => $_GET['periodo'] ?? null,
                    'emitido' => isset($_GET['emitido']) ? $_GET['emitido'] === '1' : null,
                    'subido' => isset($_GET['subido']) ? $_GET['subido'] === '1' : null,
                    'fisico' => isset($_GET['fisico']) ? $_GET['fisico'] === '1' : null
                ];
    
                // Eliminar filtros vacíos
                $filters = array_filter($filters, function($value) {
                    return $value !== null && $value !== '';
                });
    
                $result = Legajo::getAll($page, $perPage, $filters);
                
                if (!$result) {
                    throw new Exception('Error al obtener los datos');
                }
    
                header('Content-Type: application/json');
                return Response::json($result);
            } catch (Exception $e) {
                header('HTTP/1.1 500 Internal Server Error');
                return Response::json(['error' => $e->getMessage()]);
            }
        }
    
        // Si no es AJAX, mostrar la vista
        require_once __DIR__ . '/../Views/Legajo/Index.php';
    }
    
    private static function isAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ||
               strpos($_SERVER['REQUEST_URI'], '/api') !== false; // También considera peticiones a /api como AJAX
    }
 
    public static function create()
    {
        session_start();
        $usuarioActual = $_SESSION['user_id'] ?? null;
        $rolUsuario = $_SESSION['role'] ?? null;

        if (!$usuarioActual || $rolUsuario !== 'NOMINAS') {
            return Response::json(['error' => 'No autorizado para crear legajos'], 403);
        }

        // Asegurarnos de capturar apellidos_nombres
        $apellidosNombres = $_POST['apellidos_nombres'] ?? null;
        if (!$apellidosNombres) {
            return Response::json(['error' => 'El nombre del trabajador es requerido'], 400);
        }

        // Obtener la descripción del documento
        $documentoDescripcion = '';
        try {
            $docResult = Database::query("SELECT DESCRIPCION FROM DOCUMENTOS WHERE ID = ?", [$_POST['documento_id']]);
            if (!empty($docResult)) {
                $documentoDescripcion = $docResult[0]['DESCRIPCION'];
            }
        } catch (Exception $e) {
            return Response::json(['error' => 'Error al obtener la descripción del documento'], 500);
        }

        $data = [
            'tipo_documento' => $_POST['tipo_documento'] ?? null,
            'n_documento' => $_POST['n_documento'] ?? null,
            'documento_id' => $_POST['documento_id'] ?? null,
            'ejercicio' => $_POST['ejercicio'] ?? null,
            'periodo' => $_POST['periodo'] ?? null,
            'apellidos_nombres' => $apellidosNombres
        ];

        // Validar existencia previa
        if (Legajo::existeCombinacion($data)) {
            return Response::json(['error' => 'Ya existe un legajo con esta combinación de datos'], 400);
        }

        if (isset($_FILES['emitido'])) {
            $data['emitido'] = self::uploadFile(
                'emitido', 
                $apellidosNombres, 
                $data['ejercicio'], 
                str_pad($data['periodo'], 2, '0', STR_PAD_LEFT), // Asegurar formato de 2 dígitos
                $documentoDescripcion
            );
            $data['emitido_usuario'] = $usuarioActual;
            $data['emitido_hora'] = date('Y-m-d H:i:s');
            $data['emitido_observacion'] = $_POST['emitido_observacion'] ?? '';
        }

        $result = Legajo::create($data);
        
        if ($result) {
            return Response::json(['message' => 'Legajo creado correctamente']);
        } else {
            return Response::json(['error' => 'Error al crear el legajo']);
        }
    }
    public static function update()
    {
        session_start();
        $usuarioActual = $_SESSION['user_id'] ?? null;
        $rolUsuario = $_SESSION['role'] ?? null;
    
        if (!$usuarioActual) {
            return Response::json(['error' => 'Usuario no autenticado'], 403);
        }
    
        $id = $_POST['id'] ?? null;
        if (!$id) {
            return Response::json(['error' => 'ID no proporcionado'], 400);
        }
    
        $data = [];
    
        switch ($rolUsuario) {
            case 'RRHH':
                if (isset($_FILES['subido'])) {
                    $apellidosNombres = $_POST['apellidos_nombres'] ?? 'Sin_Nombre';
                    $documentoDescripcion = $_POST['documento_id'] ?? 'Documento';
                    $data['subido'] = self::uploadFile('subido', $apellidosNombres, $_POST['ejercicio'], $_POST['periodo'], $documentoDescripcion);
                    $data['subido_usuario'] = $usuarioActual;
                    $data['subido_hora'] = date('Y-m-d H:i:s');
                    $data['subido_observacion'] = $_POST['subido_observacion'] ?? '';
                }
                break;
    
            case 'RECEPCION':
                if (isset($_POST['fisico'])) {
                    $data['fisico'] = $_POST['fisico'] ? 1 : 0;
                    $data['fisico_usuario'] = $usuarioActual;
                    $data['fisico_hora'] = date('Y-m-d H:i:s');
                    $data['fisico_observacion'] = $_POST['fisico_observacion'] ?? '';
                }
                break;
        }
    
        if (empty($data)) {
            return Response::json(['error' => 'No hay datos para actualizar'], 400);
        }
    
        $result = Legajo::update($id, $data);
    
        if ($result) {
            return Response::json(['message' => 'Legajo actualizado correctamente']);
        } else {
            return Response::json(['error' => 'Error al actualizar el legajo']);
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
