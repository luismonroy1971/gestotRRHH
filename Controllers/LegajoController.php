<?php

namespace Controllers;

use Models\Legajo;
use Libs\FileHandler;
use Libs\Database;
use Libs\Response;
use thiagoalessio\TesseractOCR\TesseractOCR;

class LegajoController
{
    // Obtener todos los registros
    public static function getAll()
    {
        $legajos = Legajo::getAll();
        return Response::json($legajos);
    }

    // Obtener un registro por ID
    public static function findById()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            return Response::json(['error' => 'ID no proporcionado'], 400);
        }

        $legajo = Legajo::findById($id);
        return Response::json($legajo);
    }

    // Crear un nuevo registro
    public static function create()
    {
        session_start();
        $usuarioActual = $_SESSION['user_id'] ?? null;

        if (!$usuarioActual) {
            return Response::json(['error' => 'Usuario no autenticado.'], 403);
        }

        $data = [
            'tipo_documento' => $_POST['tipo_documento'] ?? null,
            'n_documento' => $_POST['n_documento'] ?? null,
            'documento_id' => $_POST['documento_id'] ?? null,
            'ejercicio' => $_POST['ejercicio'] ?? null,
            'periodo' => $_POST['periodo'] ?? null,
            'emitido' => $_POST['emitido'] ?? null,
            'observacion' => $_POST['observacion'] ?? null
        ];

        // Agregar campos de usuario y tiempos
        $data['emitido_usuario'] = $usuarioActual;
        $data['emitido_fecha'] = date('Y-m-d H:i:s');
        $data['subido_usuario'] = null;
        $data['subido_fecha'] = null;
        $data['firmado_usuario'] = null;
        $data['firmado_fecha'] = null;

        $result = Legajo::create($data);

        if ($result) {
            return Response::json(['message' => 'Legajo creado correctamente.'], 201);
        } else {
            return Response::json(['error' => 'Error al crear el legajo.'], 500);
        }
    }

    // Actualizar un registro existente
    public static function update()
    {
        session_start();
        $usuarioActual = $_SESSION['user_id'] ?? null;

        if (!$usuarioActual) {
            return Response::json(['error' => 'Usuario no autenticado.'], 403);
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            return Response::json(['error' => 'ID no proporcionado'], 400);
        }

        $data = [
            'tipo_documento' => $_POST['tipo_documento'] ?? null,
            'n_documento' => $_POST['n_documento'] ?? null,
            'documento_id' => $_POST['documento_id'] ?? null,
            'ejercicio' => $_POST['ejercicio'] ?? null,
            'periodo' => $_POST['periodo'] ?? null,
            'emitido' => $_POST['emitido'] ?? null,
            'observacion' => $_POST['observacion'] ?? null
        ];

        // Registrar campos de modificación
        $data['modificado_usuario'] = $usuarioActual;
        $data['modificado_fecha'] = date('Y-m-d H:i:s');

        $result = Legajo::update($id, $data);

        if ($result !== false) {
            return Response::json(['message' => 'Legajo actualizado correctamente.']);
        } else {
            return Response::json(['error' => 'Error al actualizar el legajo.'], 500);
        }
    }

    // Eliminar un registro
    public static function delete()
    {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            return Response::json(['error' => 'ID no proporcionado'], 400);
        }

        $result = Legajo::delete($id);

        if ($result !== false) {
            return Response::json(['message' => 'Legajo eliminado correctamente.']);
        } else {
            return Response::json(['error' => 'Error al eliminar el legajo.'], 500);
        }
    }

    // Guardar un archivo en el legajo (store)
    public static function store()
    {
        $data = $_POST;

        session_start();
        $usuarioActual = $_SESSION['user_id'] ?? null;
        if (!$usuarioActual) {
            return Response::json(['error' => 'Usuario no autenticado.'], 403);
        }

        // Validar el archivo subido
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            $filePath = $file['tmp_name'];
            $fileType = mime_content_type($filePath);

            // Usar OCR para validar el contenido del archivo
            if (strpos($fileType, 'image/') === 0) {
                $ocr = new TesseractOCR($filePath);
                $extractedText = $ocr->run();

                $nDocumento = $data['n_documento'];
                $nombreCompleto = $data['apellidos_nombres'];

                if (strpos($extractedText, $nDocumento) === false && strpos($extractedText, $nombreCompleto) === false) {
                    return Response::json(['error' => 'El archivo no contiene el número de documento ni el nombre completo del trabajador'], 400);
                }
            } else {
                return Response::json(['error' => 'El archivo debe ser una imagen para validar con OCR'], 400);
            }

            $result = FileHandler::processFile($file, $data);
            if ($result['error']) {
                return Response::json(['error' => $result['message']], 500);
            }

            $data['emitido'] = $result['path'];

            // Agregar usuario y tiempo
            $data['emitido_usuario'] = $usuarioActual;
            $data['emitido_fecha'] = date('Y-m-d H:i:s');
            $data['subido_usuario'] = null;
            $data['subido_fecha'] = null;
            $data['firmado_usuario'] = null;
            $data['firmado_fecha'] = null;
        } else {
            return Response::json(['error' => 'No se subió ningún archivo válido'], 400);
        }

        // Crear registro en la base de datos utilizando el modelo
        $createResult = Legajo::create($data);

        if ($createResult) {
            return Response::json(['message' => 'Archivo subido y legajo creado correctamente.']);
        } else {
            return Response::json(['error' => 'Error al crear el legajo.'], 500);
        }
    }
}
