<?php

namespace Controllers;

use Models\Documento;

class DocumentosController
{
    public static function getAll()
    {
        $documentos = Documento::getAll();
        
        // Aquí puedes transformar el resultado a JSON u otro formato, 
        // o bien retornarlo directamente.
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($documentos);
    }

    public static function create()
    {
        // Aquí asumimos que los datos vienen vía POST
        $data = [
            'codigo' => $_POST['codigo'] ?? null,
            'descripcion' => $_POST['descripcion'] ?? null,
            'categoria' => $_POST['categoria'] ?? null,
        ];

        $result = Documento::create($data);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => $result !== false, 'id' => $result]);
    }

    public static function update()
    {
        // Aquí asumimos que el ID también viene vía POST, o podrías extraerlo de la URL dependiendo de tu enrutador.
        $id = $_POST['id'] ?? null;

        $data = [
            'codigo' => $_POST['codigo'] ?? null,
            'descripcion' => $_POST['descripcion'] ?? null,
            'categoria' => $_POST['categoria'] ?? null,
        ];

        $result = Documento::update($id, $data);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => $result !== false]);
    }

    public static function delete()
    {
        $id = $_POST['id'] ?? null;
        
        $result = Documento::delete($id);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => $result !== false]);
    }
}
