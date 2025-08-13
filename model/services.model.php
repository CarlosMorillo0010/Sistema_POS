<?php

require_once "connection.php";

class ModelServicies
{

    /**=====================================
     * CREAR SERVICIOS
     * ======================================**/
    static public function mdlIngresarServicio($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, servicio, imagen, feregistro) VALUES (:id_usuario, :servicio, :imagen, :feregistro)");
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":servicio", $datos["servicio"], PDO::PARAM_STR);
        $stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
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
     * MOSTRAR SERVICIOS
     * ======================================**/
    static public function mdlMostrarServicio($tabla, $item, $valor)
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
     * EDITAR SERVICIOS
     * ======================================**/
    static public function mdlEditarServicio($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET servicio = :servicio  WHERE id_servicio = :id_servicio");
        $stmt->bindParam(":servicio", $datos["servicio"], PDO::PARAM_STR);
        $stmt->bindParam(":id_servicio", $datos["id_servicio"], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /**=====================================
     * BORRAR CATEGORIAS
     * ======================================**/
    static public function mdlBorrarServicio($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_servicio = :id_servicio");
        $stmt->bindParam(":id_servicio", $datos, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}