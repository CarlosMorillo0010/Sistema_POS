<?php

require_once "connection.php";

class ModelMarcas{

    /**=====================================
    CREAR MARCAS
    ======================================**/
    static public function mdlIngresarMarca($tabla, $datos){
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, marca, feregistro) VALUES (:id_usuario, :marca, :feregistro)");
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":marca", $datos["marca"], PDO::PARAM_STR);
        $stmt->bindParam(":feregistro", $datos["fecha"], PDO::PARAM_STR);
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /**=====================================
    MOSTRAR MARCAS
    ======================================**/
    static public function mdlMostrarMarca($tabla, $item, $valor){
        if($item != null){
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt -> fetch();
        }else{
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla");
            $stmt -> execute();
            return $stmt -> fetchAll();
        }
        $stmt -> close();
        $stmt = null;
    }

    /**=====================================
    EDITAR MARCA
    ======================================**/
    static public function mdlEditarMarca($tabla, $datos){
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET marca = :marca  WHERE id_marca = :id_marca");
        $stmt->bindParam(":marca", $datos["marca"], PDO::PARAM_STR);
        $stmt->bindParam(":id_marca", $datos["id_marca"], PDO::PARAM_STR);
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /**=====================================
    BORRAR MARCAS
    ======================================**/
    static public function mdlBorrarMarca($tabla, $datos){
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_marca = :id_marca");
        $stmt->bindParam(":id_marca", $datos, PDO::PARAM_INT);
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}