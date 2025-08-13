<?php

require_once "connection.php";

class ModelUnidades
{
    /**=====================================
     * CREAR UNIDADES DE MEDIDAS
     * ======================================**/
    static public function mdlIngresarUnidad($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, nombre, unidad, feregistro) VALUES (:id_usuario, :nombre, :unidad, :feregistro)");
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":unidad", $datos["unidad"], PDO::PARAM_STR);
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
     * MOSTRAR UNIDADES DE MEDIDAS
     * ======================================**/
    static public function mdlMostrarUnidad($tabla, $item, $valor)
    {
        if ($item != null) {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt->close();
        $stmt = null;
    }

    /**=====================================
     * EDITAR UNIDADES DE MEDIDAS
     * ======================================**/
    static public function mdlEditarUnidad($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET nombre = :nombre, unidad = :unidad  WHERE id_unidad = :id_unidad");
        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":unidad", $datos["unidad"], PDO::PARAM_STR);
        $stmt->bindParam(":id_unidad", $datos["id_unidad"], PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /**=====================================
     * BORRAR UNIDADES DE MEDIDAS
     * ======================================**/
    static public function mdlBorrarUnidad($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_unidad = :id_unidad");
        $stmt->bindParam(":id_unidad", $datos, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}