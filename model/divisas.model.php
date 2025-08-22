<?php

require_once "connection.php";

class ModelDivisas
{
    /**
     * GUARDA O ACTUALIZA LA TASA DE CAMBIO.
     * @throws Exception Si ocurre un error en la base de datos.
     */
    static public function mdlGuardarOActualizarTasa($datos)
    {
        $sql = "INSERT INTO tasas_cambio (divisa_id, tasa, fecha) VALUES (:divisa_id, :tasa, :fecha) ON DUPLICATE KEY UPDATE tasa = VALUES(tasa)";
        try {
            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(":divisa_id", $datos["id_divisa"], PDO::PARAM_INT);
            $stmt->bindParam(":tasa", $datos["tasa"], PDO::PARAM_STR);
            $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
            $stmt->execute();
            switch ($stmt->rowCount()) {
                case 1: return 'inserted';
                case 2: return 'updated';
                default: return 'not_changed';
            }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

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
}