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
                $data['subido'],
                $data['subido_usuario'],
                $data['subido_fecha'],
                $data['firmado'],
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
                EMITIDO_USUARIO = ?,
                EMITIDO_HORA = ?,
                SUBIDO = ?,
                SUBIDO_USUARIO = ?,
                SUBIDO_HORA = ?,
                FISICO = ?,
                FISICO_USUARIO = ?,
                FISICO_HORA = ?,
                OBSERVACION = ?, 
                OBSERVACION_USUARIO = ?, 
                OBSERVACION_HORA = ? 
            WHERE ID = ?", [
                $data['tipo_documento'],
                $data['n_documento'],
                $data['documento_id'],
                $data['ejercicio'],
                $data['periodo'],
                $data['emitido'],
                $data['emitido_usuario'],
                $data['emitido_hora'],
                $data['subido'],
                $data['subido_usuario'],
                $data['subido_hora'],
                $data['fisico'],
                $data['fisico_usuario'],
                $data['fisico_hora'],
                $data['observacion'] ?? null,
                $data['observacion_usuario'],
                $data['observacion_hora'],
                $id
            ]
        );
    }

    public static function delete($id)
    {
        return Database::delete("DELETE FROM LEGAJO WHERE ID = ?", [$id]);
    }
}
