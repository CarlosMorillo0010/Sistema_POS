<?php

require_once "connection.php";

class ModelDivisas
{
    /**=====================================
     * CREAR DIVISAS
     * ======================================**/
    static public function mdlIngresarDivisa($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, divisa, simbolo, feregistro) VALUES (:id_usuario, :divisa, :simbolo, :feregistro)");
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":divisa", $datos["divisa"], PDO::PARAM_STR);
        $stmt->bindParam(":simbolo", $datos["simbolo"], PDO::PARAM_STR);
        $stmt->bindParam(":feregistro", $datos["fecha"], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /**=====================================
     * EDITAR DIVISAS
     * ======================================**/
    static public function mdlEditarDivisa($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET divisa = :divisa, simbolo = :simbolo  WHERE id_divisa = :id_divisa");
        $stmt->bindParam(":divisa", $datos["divisa"], PDO::PARAM_STR);
        $stmt->bindParam(":simbolo", $datos["simbolo"], PDO::PARAM_STR);
        $stmt->bindParam(":id_divisa", $datos["id_divisa"], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /**=====================================
     * BORRAR DIVISAS
     * ======================================**/
    static public function mdlBorrarDivisa($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_divisa = :id_divisa");
        $stmt->bindParam(":id_divisa", $datos, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    static public function mdlMostrarDivisa($tabla, $item, $valor) {
        if ($item != null) {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla ORDER BY es_local DESC, nombre ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
    static public function mdlMostrarDivisasConTasa($tablaDivisas, $tablaTasas) {
        $sql = "SELECT d.*, t.tasa as ultima_tasa, t.fecha as fecha_tasa 
                FROM $tablaDivisas d
                LEFT JOIN (
                    SELECT t1.divisa_id, t1.tasa, t1.fecha 
                    FROM $tablaTasas t1
                    INNER JOIN (
                        SELECT divisa_id, MAX(fecha) as max_fecha 
                        FROM $tablaTasas GROUP BY divisa_id
                    ) t2 ON t1.divisa_id = t2.divisa_id AND t1.fecha = t2.max_fecha
                ) t ON d.id = t.divisa_id
                ORDER BY d.es_local DESC, d.nombre ASC";
        
        $stmt = Connection::connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    static public function mdlGuardarTasaCambio($tabla, $datos) {
        $stmt_check = Connection::connect()->prepare("SELECT id, tasa FROM $tabla WHERE divisa_id = :divisa_id AND fecha = :fecha");
        $stmt_check->bindParam(":divisa_id", $datos["divisa_id"], PDO::PARAM_INT);
        $stmt_check->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
        $stmt_check->execute();
        $existente = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($existente) {
            if (number_format((float)$existente['tasa'], 4) != number_format((float)$datos['tasa'], 4)) {
                $stmt = Connection::connect()->prepare("UPDATE $tabla SET tasa = :tasa WHERE id = :id");
                $stmt->bindParam(":tasa", $datos["tasa"], PDO::PARAM_STR);
                $stmt->bindParam(":id", $existente["id"], PDO::PARAM_INT);
                return $stmt->execute() ? "ok_updated" : "error";
            } else {
                return "not_changed";
            }
        } else {
            $stmt = Connection::connect()->prepare("INSERT INTO $tabla (divisa_id, tasa, fecha) VALUES (:divisa_id, :tasa, :fecha)");
            $stmt->bindParam(":divisa_id", $datos["divisa_id"], PDO::PARAM_INT);
            $stmt->bindParam(":tasa", $datos["tasa"], PDO::PARAM_STR);
            $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
            return $stmt->execute() ? "ok_inserted" : "error";
        }
    }

    static public function mdlObtenerTasaActual($tablaTasas, $tablaDivisas, $codigoDivisa) {
        $stmt = Connection::connect()->prepare(
            "SELECT t.tasa 
             FROM $tablaTasas t
             INNER JOIN $tablaDivisas d ON t.divisa_id = d.id
             WHERE d.codigo = :codigo
             ORDER BY t.fecha DESC
             LIMIT 1"
        );
    
        $stmt->bindParam(":codigo", $codigoDivisa, PDO::PARAM_STR);
        $stmt->execute();
        
        // Devolvemos solo el valor de la tasa, o false si no se encuentra
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $resultado ? (float)$resultado['tasa'] : false;
    }

    /*===================================================================
     GUARDAR O ACTUALIZAR LA TASA DE CAMBIO PARA UN DÍA ESPECÍFICO
     Usa INSERT ... ON DUPLICATE KEY UPDATE para eficiencia.
     PRE-REQUISITO: La tabla debe tener una UNIQUE KEY en (id_divisa, fecha_tasa)
    ===================================================================*/
    static public function mdlGuardarTasaDelDia($datos)
    {
        // El nombre de la tabla donde guardas el historial de tasas. ¡AJÚSTALO!
        $tabla = "tasas_cambio";

        $sql = "INSERT INTO $tabla (divisa_id, tasa, fecha) 
                VALUES (:divisa_id, :tasa, :fecha)
                ON DUPLICATE KEY UPDATE tasa = :tasa_update";
        
        try {
            $stmt = Connection::connect()->prepare($sql);

            $stmt->bindParam(":divisa_id", $datos["id_divisa"], PDO::PARAM_INT);
            $stmt->bindParam(":tasa", $datos["tasa"], PDO::PARAM_STR);
            $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
            $stmt->bindParam(":tasa_update", $datos["tasa"], PDO::PARAM_STR); // El mismo valor para la actualización

            if ($stmt->execute()) {
                return "ok";
            } else {
                return implode(" - ", $stmt->errorInfo());
            }
        } catch (PDOException $e) {
            return "Error de base de datos: " . $e->getMessage();
        }
    }
}