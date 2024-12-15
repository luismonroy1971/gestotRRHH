<?php

namespace Controllers;

use Libs\Database;
use Libs\Response;

class ColaboradoresController
{
    // Listar todos los colaboradores
    public static function index()
    {
        $colaboradores = self::getAll();
        return Response::json($colaboradores);
    }

    // Obtener todos los colaboradores
    public static function getAll()
    {
        return Database::query("SELECT * FROM COLABORADORES");
    }

    // Buscar colaboradores por documento
    public static function search()
    {
        $data = $_GET; // Obtener parámetros de búsqueda
        $tipoDocumento = $data['tipo_documento'] ?? null;
        $nDocumento = $data['n_documento'] ?? null;
        $nombre = $data['nombre'] ?? null;

        if ($tipoDocumento && $nDocumento) {
            $colaborador = self::findByDocument($tipoDocumento, $nDocumento);
            return Response::json($colaborador);
        } elseif ($nombre) {
            $resultados = self::searchByName($nombre);
            return Response::json($resultados);
        } else {
            return Response::json(['error' => 'Parámetros de búsqueda incorrectos'], 400);
        }
    }

    public static function findByDocument($tipoDocumento, $nDocumento)
    {
        return Database::query("SELECT * FROM COLABORADORES WHERE TIPO_DOCUMENTO = ? AND N_DOCUMENTO = ?", [$tipoDocumento, $nDocumento]);
    }

    public static function searchByName($query)
    {
        return Database::query("SELECT * FROM COLABORADORES WHERE APELLIDOS_NOMBRES LIKE ?", ["%$query%"]);
    }

    // Crear un nuevo colaborador
    public static function create()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['tipo_documento'], $data['n_documento'], $data['apellidos_nombres'])) {
            return Response::json(['error' => 'Datos incompletos'], 400);
        }

        $result = Database::insert("INSERT INTO COLABORADORES (TIPO_DOCUMENTO, N_DOCUMENTO, APELLIDOS_NOMBRES) VALUES (?, ?, ?)", [
            $data['tipo_documento'], $data['n_documento'], $data['apellidos_nombres']
        ]);

        return Response::json(['message' => 'Colaborador creado exitosamente', 'id' => $result]);
    }

    // Actualizar un colaborador
    public static function update()
    {
        $id = $_GET['id'] ?? null;
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$id || !$data) {
            return Response::json(['error' => 'ID o datos incompletos'], 400);
        }

        Database::update("UPDATE COLABORADORES SET TIPO_DOCUMENTO = ?, N_DOCUMENTO = ?, APELLIDOS_NOMBRES = ? WHERE ID = ?", [
            $data['tipo_documento'], $data['n_documento'], $data['apellidos_nombres'], $id
        ]);

        return Response::json(['message' => 'Colaborador actualizado exitosamente']);
    }

    // Eliminar un colaborador
    public static function delete()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            return Response::json(['error' => 'ID requerido'], 400);
        }

        Database::delete("DELETE FROM COLABORADORES WHERE ID = ?", [$id]);

        return Response::json(['message' => 'Colaborador eliminado exitosamente']);
    }
}
