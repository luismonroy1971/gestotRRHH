<?php

namespace Models;

use Libs\Database;
use Exception; // Añadimos esta línea para importar Exception

class Legajo
{
    public static function getAll($page = 1, $perPage = 10, $filters = [])
    {
        try {
            // Construir la consulta base
            $baseSql = "FROM LEGAJO WHERE 1=1";
            $params = [];
    
            // Aplicar filtros
            if (!empty($filters['tipo_documento'])) {
                $baseSql .= " AND TIPO_DOCUMENTO = ?";
                $params[] = $filters['tipo_documento'];
            }
    
            if (!empty($filters['n_documento'])) {
                $baseSql .= " AND N_DOCUMENTO LIKE ?";
                $params[] = "%{$filters['n_documento']}%";
            }
    
            if (!empty($filters['ejercicio'])) {
                $baseSql .= " AND EJERCICIO = ?";
                $params[] = $filters['ejercicio'];
            }
    
            if (!empty($filters['periodo'])) {
                $baseSql .= " AND PERIODO = ?";
                $params[] = $filters['periodo'];
            }
    
            if (isset($filters['emitido'])) {
                $baseSql .= $filters['emitido'] ? " AND EMITIDO IS NOT NULL" : " AND EMITIDO IS NULL";
            }
    
            if (isset($filters['subido'])) {
                $baseSql .= $filters['subido'] ? " AND SUBIDO IS NOT NULL" : " AND SUBIDO IS NULL";
            }
    
            if (isset($filters['fisico'])) {
                $baseSql .= " AND FISICO = ?";
                $params[] = $filters['fisico'];
            }
    
            // Contar total de registros
            $countSql = "SELECT COUNT(*) as total " . $baseSql;
            $totalResult = Database::query($countSql, $params);
            $total = (int)$totalResult[0]['total'];
    
            // Calcular paginación
            $totalPages = ceil($total / $perPage);
            $offset = ($page - 1) * $perPage;
    
            // Consulta principal con paginación
            $mainSql = "SELECT * " . $baseSql . " ORDER BY ID DESC";
            if ($perPage > 0) {
                $mainSql .= " LIMIT " . (int)$perPage;
                if ($offset > 0) {
                    $mainSql .= " OFFSET " . (int)$offset;
                }
            }
    
            $data = Database::query($mainSql, $params);
    
            return [
                'data' => $data,
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'pages' => $totalPages
            ];
        } catch (Exception $e) {
            error_log("Error in getAll: " . $e->getMessage());
            throw $e;
        }
    }

    public static function findById($id)
    {
        return Database::query("SELECT * FROM LEGAJO WHERE ID = ?", [$id]);
    }

    public static function create($data)
    {
        // Campos básicos que siempre deben estar presentes
        $fields = [
            'TIPO_DOCUMENTO',
            'N_DOCUMENTO',
            'DOCUMENTO_ID',
            'EJERCICIO',
            'PERIODO',
            'APELLIDOS_NOMBRES'  // Movido al final
        ];
        
        $values = [
            $data['tipo_documento'],
            $data['n_documento'],
            $data['documento_id'],
            $data['ejercicio'],
            $data['periodo'],
            $data['apellidos_nombres'] 
        ];
    
        // Agregar campos opcionales si están presentes
        if (isset($data['emitido'])) {
            $fields[] = 'EMITIDO';
            $values[] = $data['emitido'];
        }
        
        if (isset($data['emitido_usuario'])) {
            $fields[] = 'EMITIDO_USUARIO';
            $values[] = $data['emitido_usuario'];
        }
        
        if (isset($data['emitido_hora'])) {
            $fields[] = 'EMITIDO_HORA';
            $values[] = $data['emitido_hora'];
        }
        
        if (isset($data['emitido_observacion'])) {
            $fields[] = 'EMITIDO_OBSERVACION';
            $values[] = $data['emitido_observacion'];
        }
        
        // Crear los placeholders para la consulta SQL
        $placeholders = array_fill(0, count($fields), '?');
        
        // Construir la consulta SQL
        $sql = "INSERT INTO LEGAJO (" . implode(", ", $fields) . ") 
                VALUES (" . implode(", ", $placeholders) . ")";
        
        try {
            return Database::insert($sql, $values);
        } catch (Exception $e) {
            error_log("Error creating legajo: " . $e->getMessage());
            return false;
        }
    }
    
    public static function existeCombinacion($data)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM LEGAJO 
                WHERE TIPO_DOCUMENTO = ? 
                AND N_DOCUMENTO = ? 
                AND DOCUMENTO_ID = ? 
                AND EJERCICIO = ? 
                AND PERIODO = ?";
                
        $values = [
            $data['tipo_documento'],
            $data['n_documento'],
            $data['documento_id'],
            $data['ejercicio'],
            $data['periodo']
        ];
    
        try {
            $result = Database::query($sql, $values);
            return isset($result[0]['count']) && $result[0]['count'] > 0;
        } catch (Exception $e) {
            error_log("Error checking combination: " . $e->getMessage());
            return false;
        }
    }
    

    public static function update($id, $data)
    {
        // Construir la consulta SQL dinámicamente basada en los campos proporcionados
        $setClauses = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            if ($value !== null) {
                $setClauses[] = strtoupper($key) . " = ?";
                $params[] = $value;
            }
        }
        
        if (empty($setClauses)) {
            return false;
        }
        
        // Agregar el ID al final de los parámetros
        $params[] = $id;
        
        $sql = "UPDATE LEGAJO SET " . implode(", ", $setClauses) . " WHERE ID = ?";
        
        return Database::update($sql, $params);
    }
    

    public static function delete($id)
    {
        return Database::delete("DELETE FROM LEGAJO WHERE ID = ?", [$id]);
    }
}
