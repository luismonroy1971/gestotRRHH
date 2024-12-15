<?php

namespace Models;

use Libs\Database;

class Legajo
{
    public static function getAll()
    {
        return Database::query("SELECT * FROM LEGAJO");
    }

    public static function findById($id)
    {
        return Database::query("SELECT * FROM LEGAJO WHERE ID = ?", [$id]);
    }

    public static function create($data)
    {
        return Database::insert(
            "INSERT INTO LEGAJO 
            (TIPO_DOCUMENTO, N_DOCUMENTO, DOCUMENTO_ID, EJERCICIO, PERIODO, EMITIDO, EMITIDO_USUARIO, EMITIDO_FECHA, SUBIDO_USUARIO, SUBIDO_FECHA, FIRMADO_USUARIO, FIRMADO_FECHA, OBSERVACION) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                $data['tipo_documento'],
                $data['n_documento'],
                $data['documento_id'],
                $data['ejercicio'],
                $data['periodo'],
                $data['emitido'],
                $data['emitido_usuario'],
                $data['emitido_fecha'],
                $data['subido_usuario'],
                $data['subido_fecha'],
                $data['firmado_usuario'],
                $data['firmado_fecha'],
                $data['observacion'] ?? null
            ]
        );
    }

    public static function update($id, $data)
    {
        return Database::update(
            "UPDATE LEGAJO 
            SET TIPO_DOCUMENTO = ?, 
                N_DOCUMENTO = ?, 
                DOCUMENTO_ID = ?, 
                EJERCICIO = ?, 
                PERIODO = ?, 
                EMITIDO = ?, 
                OBSERVACION = ?, 
                MODIFICADO_USUARIO = ?, 
                MODIFICADO_FECHA = ? 
            WHERE ID = ?", [
                $data['tipo_documento'],
                $data['n_documento'],
                $data['documento_id'],
                $data['ejercicio'],
                $data['periodo'],
                $data['emitido'],
                $data['observacion'] ?? null,
                $data['modificado_usuario'],
                $data['modificado_fecha'],
                $id
            ]
        );
    }

    public static function delete($id)
    {
        return Database::delete("DELETE FROM LEGAJO WHERE ID = ?", [$id]);
    }
}
