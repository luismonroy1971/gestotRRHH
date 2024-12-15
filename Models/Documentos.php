<?php

//  /Models/Documento.php

namespace Models;

use \Libs\Database;

class Documento
{
    public static function getAll()
    {
        return Database::query("SELECT * FROM DOCUMENTOS");
    }

    public static function findById($id)
    {
        return Database::query("SELECT * FROM DOCUMENTOS WHERE ID = ?", [$id]);
    }

    public static function create($data)
    {
        return Database::insert("INSERT INTO DOCUMENTOS (CODIGO, DESCRIPCION, CATEGORIA) VALUES (?, ?, ?)", [
            $data['codigo'],
            $data['descripcion'],
            $data['categoria']
        ]);
    }

    public static function update($id, $data)
    {
        return Database::update("UPDATE DOCUMENTOS SET CODIGO = ?, DESCRIPCION = ?, CATEGORIA = ? WHERE ID = ?", [
            $data['codigo'],
            $data['descripcion'],
            $data['categoria'],
            $id
        ]);
    }

    public static function delete($id)
    {
        return Database::delete("DELETE FROM DOCUMENTOS WHERE ID = ?", [$id]);
    }
}
