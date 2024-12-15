<?php

namespace Controllers;

use Models\Usuario;
use Libs\Response;

class UsuariosController {
    public static function getAll() {
        $usuarios = Usuario::getAll();
        return Response::json($usuarios);
    }

    public static function create() {
        $data = [
            'nombre_usuario' => $_POST['nombre_usuario'] ?? null,
            'contrasena' => $_POST['contrasena'] ?? null,
            'rol' => $_POST['rol'] ?? null
        ];

        $result = Usuario::create($data);

        if ($result) {
            return Response::json(['message' => 'Usuario creado correctamente.'], 201);
        } else {
            return Response::json(['error' => 'Error al crear usuario'], 500);
        }
    }

    public static function update() {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            return Response::json(['error' => 'ID no proporcionado'], 400);
        }

        $data = [
            'nombre_usuario' => $_POST['nombre_usuario'] ?? null,
            'contrasena' => $_POST['contrasena'] ?? null,
            'rol' => $_POST['rol'] ?? null
        ];

        $result = Usuario::update($id, $data);

        if ($result !== false) {
            return Response::json(['message' => 'Usuario actualizado correctamente.']);
        } else {
            return Response::json(['error' => 'Error al actualizar el usuario.'], 500);
        }
    }

    public static function delete() {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            return Response::json(['error' => 'ID no proporcionado'], 400);
        }

        $result = Usuario::delete($id);

        if ($result !== false) {
            return Response::json(['message' => 'Usuario eliminado correctamente.']);
        } else {
            return Response::json(['error' => 'Error al eliminar el usuario.'], 500);
        }
    }
}
