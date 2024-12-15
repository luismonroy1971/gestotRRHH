<?php

// /Models/DocumentoIdentidad.php

namespace Models;

use \Libs\Database;

class DocumentoIdentidad
{
    public static function getAll()
    {
        return Database::query("SELECT * FROM DOCUMENTOS_IDENTIDAD");
    }

    public static function findById($id)
    {
        return Database::query("SELECT * FROM DOCUMENTOS_IDENTIDAD WHERE ID = ?", [$id]);
    }

    public static function create($data)
    {
        return Database::insert("INSERT INTO DOCUMENTOS_IDENTIDAD (TIPO_DOCUMENTO) VALUES (?)", [$data['tipo_documento']]);
    }

    public static function update($id, $data)
    {
        return Database::update("UPDATE DOCUMENTOS_IDENTIDAD SET TIPO_DOCUMENTO = ? WHERE ID = ?", [$data['tipo_documento'], $id]);
    }

    public static function delete($id)
    {
        return Database::delete("DELETE FROM DOCUMENTOS_IDENTIDAD WHERE ID = ?", [$id]);
    }
}
