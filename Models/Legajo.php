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
            (TIPO_DOCUMENTO, N_DOCUMENTO, DOCUMENTO_ID, EJERCICIO, PERIODO, EMITIDO, EMITIDO_USUARIO, EMITIDO_HORA, EMITIDO_OBSERVACION, SUBIDO_USUARIO, SUBIDO_HORA, SUBIDO_OBSERVACION, FIRMADO_USUARIO, FIRMADO_HORA, FIRMADO_OBSERVACION) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                $data['tipo_documento'],
                $data['n_documento'],
                $data['documento_id'],
                $data['ejercicio'],
                $data['periodo'],
                $data['emitido'],
                $data['emitido_usuario'],
                $data['emitido_hora'],
                $data['emitido_observacion'],
                $data['subido'],
                $data['subido_usuario'],
                $data['subido_hora'],
                $data['subido_observacion'],
                $data['firmado'],
                $data['firmado_usuario'],
                $data['firmado_hora'],
                $data['firmado_observacion']
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
                EMITIDO_USUARIO = ?,
                EMITIDO_HORA = ?,
                EMITIDO_OBSERVACION = ?,
                SUBIDO = ?,
                SUBIDO_USUARIO = ?,
                SUBIDO_HORA = ?,
                SUBIDO_OBSERVACION = ?,
                FISICO = ?,
                FISICO_USUARIO = ?,
                FISICO_HORA = ?,
                FISICO_OBSERVACION = ?, 
            WHERE ID = ?", [
                $data['tipo_documento'],
                $data['n_documento'],
                $data['documento_id'],
                $data['ejercicio'],
                $data['periodo'],
                $data['emitido'],
                $data['emitido_usuario'],
                $data['emitido_hora'],
                $data['emitido_observacion'],
                $data['subido'],
                $data['subido_usuario'],
                $data['subido_hora'],
                $data['subido_observacion'],
                $data['fisico'],
                $data['fisico_usuario'],
                $data['fisico_hora'],
                $data['fisico_observacion'],
                $id
            ]
        );
    }

    public static function delete($id)
    {
        return Database::delete("DELETE FROM LEGAJO WHERE ID = ?", [$id]);
    }
}
