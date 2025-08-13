<?php

require_once "connection.php";

class ModelSucursales
{
    /**=====================================
     * CREAR SUCURSALES
     * ======================================**/
    static public function mdlIngresarSucursal($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, codigo, nombre, feregistro) VALUES (:id_usuario, :codigo, :nombre, :feregistro)");
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
        $stmt->bindParam(":nombre", $datos["sucursal"], PDO::PARAM_STR);
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
     * MOSTRAR SUCURSALES
     * ======================================**/
    static public function mdlMostrarSucursal($tabla, $item, $valor)
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
     * EDITAR SUCURSALES
     * ======================================**/
    static public function mdlEditarSucursal($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET codigo = :codigo, nombre = :nombre  WHERE id_sucursal = :id_sucursal");
        $stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
        $stmt->bindParam(":nombre", $datos["sucursal"], PDO::PARAM_STR);
        $stmt->bindParam(":id_sucursal", $datos["id_sucursal"], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /**=====================================
     * BORRAR SUCURSALES
     * ======================================**/
    static public function mdlBorrarSucursal($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_sucursal = :id_sucursal");
        $stmt->bindParam(":id_sucursal", $datos, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}