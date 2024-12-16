<?php

namespace Controllers;

use Libs\Database;

class ColaboradoresController
{
    // Listar todos los colaboradores
    public static function index()
    {
        $colaboradores = self::getAll();
        require_once __DIR__ . '/../Views/Colaboradores/Index.php';
    }

    // Obtener todos los colaboradores
    public static function getAll()
    {
        return Database::query("SELECT * FROM COLABORADORES");
    }

    // Mostrar formulario de creación
    public static function showCreateForm()
    {
        require_once __DIR__ . '/../Views/Colaboradores/Create.php';
    }

    // Crear un nuevo colaborador
    public static function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tipo_documento' => $_POST['tipo_documento'] ?? null,
                'n_documento' => $_POST['n_documento'] ?? null,
                'apellidos_nombres' => $_POST['apellidos_nombres'] ?? null,
                'fecha_ingreso' => $_POST['fecha_ingreso'] ?? null,
                'area' => $_POST['area'] ?? null,
                'correo' => $_POST['correo'] ?? null,
                'aprobador_1' => $_POST['aprobador_1'] ?? null,
                'aprobador_2' => $_POST['aprobador_2'] ?? null
            ];

            Database::insert(
                "INSERT INTO COLABORADORES (TIPO_DOCUMENTO, N_DOCUMENTO, APELLIDOS_NOMBRES, FECHA_INGRESO, AREA, CORREO, APROBADOR_1, APROBADOR_2)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                array_values($data)
            );

            header('Location: /colaboradores?message=Colaborador creado exitosamente');
            exit;
        }

        self::showCreateForm();
    }

    public static function showEditForm($id)
    {
        if (!$id) {
            // Redirige si el ID no está definido
            header('Location: /colaboradores?error=ID no proporcionado');
            exit;
        }
    
        // Buscar colaborador
        $colaborador = Database::query("SELECT * FROM COLABORADORES WHERE ID = ?", [$id]);
        
        if (empty($colaborador)) {
            // Redirige si no se encuentra el colaborador
            header('Location: /colaboradores?error=Colaborador no encontrado');
            exit;
        }
    
        // Pasar el colaborador a la vista
        $colaborador = $colaborador[0];
        require_once __DIR__ . '/../Views/Colaboradores/Edit.php';
    }
    

    // Actualizar un colaborador
    public static function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                header('Location: /colaboradores?error=ID no proporcionado');
                exit;
            }

            $data = [
                'tipo_documento' => $_POST['tipo_documento'] ?? null,
                'n_documento' => $_POST['n_documento'] ?? null,
                'apellidos_nombres' => $_POST['apellidos_nombres'] ?? null,
                'fecha_ingreso' => $_POST['fecha_ingreso'] ?? null,
                'area' => $_POST['area'] ?? null,
                'correo' => $_POST['correo'] ?? null,
                'aprobador_1' => $_POST['aprobador_1'] ?? null,
                'aprobador_2' => $_POST['aprobador_2'] ?? null
            ];

            Database::update(
                "UPDATE COLABORADORES SET TIPO_DOCUMENTO = ?, N_DOCUMENTO = ?, APELLIDOS_NOMBRES = ?, FECHA_INGRESO = ?, AREA = ?, CORREO = ?, APROBADOR_1 = ?, APROBADOR_2 = ? WHERE ID = ?",
                [...array_values($data), $id]
            );

            header('Location: /colaboradores?message=Colaborador actualizado exitosamente');
            exit;
        }

        $id = $_GET['id'] ?? null;
        self::showEditForm($id);
    }

    // Eliminar un colaborador
    public static function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                header('Location: /colaboradores?error=ID no proporcionado');
                exit;
            }

            Database::delete("DELETE FROM COLABORADORES WHERE ID = ?", [$id]);

            header('Location: /colaboradores?message=Colaborador eliminado exitosamente');
            exit;
        }
    }
}
