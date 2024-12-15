<?php

// /Models/Colaborador.php

namespace Models;

use \Libs\Database;

class Colaborador
{
    public static function getAll()
    {
        return Database::query("SELECT * FROM COLABORADORES");
    }

    public static function findByDocument($tipoDocumento, $nDocumento)
    {
        return Database::query("SELECT * FROM COLABORADORES WHERE TIPO_DOCUMENTO = ? AND N_DOCUMENTO = ?", [$tipoDocumento, $nDocumento]);
    }

    public static function searchByName($query)
    {
        return Database::query("SELECT * FROM COLABORADORES WHERE APELLIDOS_NOMBRES LIKE ?", ["%$query%"]);
    }

    public static function create($data)
    {
        return Database::insert("INSERT INTO COLABORADORES (TIPO_DOCUMENTO, N_DOCUMENTO, APELLIDOS_NOMBRES, FECHA_INGRESO, AREA, CORREO, APROBADOR_1, APROBADOR_2) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [
            $data['tipo_documento'],
            $data['n_documento'],
            $data['apellidos_nombres'],
            $data['fecha_ingreso'],
            $data['area'],
            $data['correo'],
            $data['aprobador_1'],
            $data['aprobador_2']
        ]);
    }

    public static function update($id, $data)
    {
        return Database::update("UPDATE COLABORADORES SET TIPO_DOCUMENTO = ?, N_DOCUMENTO = ?, APELLIDOS_NOMBRES = ?, FECHA_INGRESO = ?, AREA = ?, CORREO = ?, APROBADOR_1 = ?, APROBADOR_2 = ? WHERE ID = ?", [
            $data['tipo_documento'],
            $data['n_documento'],
            $data['apellidos_nombres'],
            $data['fecha_ingreso'],
            $data['area'],
            $data['correo'],
            $data['aprobador_1'],
            $data['aprobador_2'],
            $id
        ]);
    }

    public static function delete($id)
    {
        return Database::delete("DELETE FROM COLABORADORES WHERE ID = ?", [$id]);
    }
}
