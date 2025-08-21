<?php

require_once "connection.php";

class ModelAjustes
{
    /**
     * ===================================================================
     * MOSTRAR AJUSTES (JOIN con almacén para mostrar el nombre)
     * ===================================================================
     */
    static public function mdlMostrarAjustes($tabla, $item, $valor)
    {
        if ($item != null) {
            $stmt = Connection::connect()->prepare("SELECT a.*, al.nombre as nombre_almacen FROM $tabla a JOIN almacenes al ON a.id_almacen = al.id_almacen WHERE a.$item = :$item ORDER BY a.fecha_ajuste DESC");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Connection::connect()->prepare("SELECT a.*, al.nombre as nombre_almacen FROM $tabla a JOIN almacenes al ON a.id_almacen = al.id_almacen ORDER BY a.fecha_ajuste DESC");
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }

    /**
     * ===================================================================
     * INGRESAR ENCABEZADO DE AJUSTE (dentro de una transacción)
     * ===================================================================
     */
    static public function mdlIngresarAjuste($tabla, $datos, $pdo)
    {
        $stmt = $pdo->prepare("INSERT INTO $tabla (id_almacen, id_usuario, fecha_ajuste, descripcion) VALUES (:id_almacen, :id_usuario, :fecha_ajuste, :descripcion)");

        $stmt->bindParam(":id_almacen", $datos["id_almacen"], PDO::PARAM_INT);
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":fecha_ajuste", $datos["fecha_ajuste"], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return $pdo->lastInsertId();
        } else {
            throw new Exception("Error al insertar en $tabla: " . implode(" - ", $stmt->errorInfo()));
        }
    }

    /**
     * ===================================================================
     * INGRESAR DETALLE DE AJUSTE (dentro de una transacción)
     * ===================================================================
     */
    static public function mdlIngresarDetalleAjuste($tabla, $datos, $pdo)
    {
        $stmt = $pdo->prepare("INSERT INTO $tabla (id_ajuste, id_producto, tipo_ajuste, cantidad_anterior, cantidad_ajustada, cantidad_final) VALUES (:id_ajuste, :id_producto, :tipo_ajuste, :cantidad_anterior, :cantidad_ajustada, :cantidad_final)");
        
        $stmt->bindParam(":id_ajuste", $datos["id_ajuste"], PDO::PARAM_INT);
        $stmt->bindParam(":id_producto", $datos["id_producto"], PDO::PARAM_INT);
        $stmt->bindParam(":tipo_ajuste", $datos["tipo_ajuste"], PDO::PARAM_STR);
        $stmt->bindParam(":cantidad_anterior", $datos["cantidad_anterior"], PDO::PARAM_INT);
        $stmt->bindParam(":cantidad_ajustada", $datos["cantidad_ajustada"], PDO::PARAM_INT);
        $stmt->bindParam(":cantidad_final", $datos["cantidad_final"], PDO::PARAM_INT);

        if (!$stmt->execute()) {
            throw new Exception("Error al insertar en $tabla: " . implode(" - ", $stmt->errorInfo()));
        }
    }

    /**
     * ===================================================================
     * MOSTRAR DETALLES DE UN AJUSTE ESPECÍFICO
     * ===================================================================
     */
    static public function mdlMostrarDetallesAjuste($tabla, $item, $valor)
    {
        $stmt = Connection::connect()->prepare(
            "SELECT d.*, p.descripcion AS descripcion_producto 
             FROM $tabla d
             JOIN productos p ON d.id_producto = p.id_producto
             WHERE d.$item = :$item"
        );

        $stmt->bindParam(":" . $item, $valor, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}