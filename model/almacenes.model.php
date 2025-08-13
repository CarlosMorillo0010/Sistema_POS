<?php

require_once "connection.php";

class ModelAlmacenes{

    /**=====================================
    CREAR ALMACEN
    ======================================**/
    static public function mdlIngresarAlmacen($tabla, $datos){
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, id_sucursal, nombre, feregistro) VALUES (:id_usuario, :id_sucursal, :nombre, :feregistro)");
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":id_sucursal", $datos["id_sucursal"], PDO::PARAM_STR);
        $stmt->bindParam(":nombre", $datos["almacen"], PDO::PARAM_STR);
        $stmt->bindParam(":feregistro", $datos["fecha"], PDO::PARAM_STR);
        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /**=====================================
    MOSTRAR ALMACEN
    ======================================**/
    static public function mdlMostrarAlmacen($tabla, $item, $valor){
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
    EDITAR ALMACEN
    ======================================**/
    static public function mdlEditarAlmacen($tabla, $datos){
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET nombre = :nombre WHERE id_almacen = :id_almacen");
        $stmt->bindParam(":nombre", $datos["almacen"], PDO::PARAM_STR);
        $stmt->bindParam(":id_almacen", $datos["id_almacen"], PDO::PARAM_INT);
        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /**=====================================
    BORRAR ALMACEN
    ======================================**/
    static public function mdlBorrarAlamacen($tabla, $datos){
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_almacen = :id_almacen");
        $stmt->bindParam(":id_almacen", $datos, PDO::PARAM_INT);
        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}