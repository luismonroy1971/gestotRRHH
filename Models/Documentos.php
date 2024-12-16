<?php

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
        $result = Database::query("SELECT * FROM DOCUMENTOS WHERE ID = ?", [$id]);
        
        // Verificar si la consulta retorna algún resultado
        if (empty($result)) {
            return null; // Retornar null si no hay resultados
        }

        return $result[0]; // Retorna la primera fila como un arreglo asociativo
    }
    
    public static function findByCodigo($codigo)
    {
        return Database::query("SELECT * FROM DOCUMENTOS WHERE CODIGO = ?", [$codigo]);
    }

    public static function create($data)
    {
        // Verificar si el código ya existe
        if (self::findByCodigo($data['codigo'])) {
            return false; // Código duplicado
        }
    
        // Insertar el documento
        return Database::insert(
            "INSERT INTO DOCUMENTOS (CODIGO, DESCRIPCION, CATEGORIA) VALUES (?, ?, ?)",
            [$data['codigo'], $data['descripcion'], $data['categoria']]
        );
    }
    

    public static function update($id, $data)
    {
        // Verificar si existe otro documento con el mismo código
        $existing = self::findByCodigo($data['codigo']);
    
        // Si existe y NO corresponde al ID actual, mostrar error
        if ($existing && $existing['ID'] != $id) {
            return false; // El código pertenece a otro registro
        }
    
        // Ejecutar la actualización
        return Database::update(
            "UPDATE DOCUMENTOS SET CODIGO = ?, DESCRIPCION = ?, CATEGORIA = ? WHERE ID = ?",
            [$data['codigo'], $data['descripcion'], $data['categoria'], $id]
        );
    }
    
    

    public static function delete($id)
    {
        return Database::delete("DELETE FROM DOCUMENTOS WHERE ID = ?", [$id]);
    }

}
