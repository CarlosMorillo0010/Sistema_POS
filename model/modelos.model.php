<?php

require_once "connection.php";

class ModelModelos{

    /**=====================================
    CREAR MODELO
    ======================================**/
    static public function mdlIngresarModelo($tabla, $datos){
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, modelo, feregistro) VALUES (:id_usuario, :modelo, :feregistro)");
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":modelo", $datos["modelo"], PDO::PARAM_STR);
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
    MOSTRAR MODELOS
    ======================================**/
    static public function mdlMostrarModelos($tabla, $item, $valor){
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
    EDITAR MODELO
    ======================================**/
    static public function mdlEditarModelo($tabla, $datos){
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET modelo = :modelo  WHERE id_modelo = :id_modelo");
        $stmt->bindParam(":modelo", $datos["modelo"], PDO::PARAM_STR);
        $stmt->bindParam(":id_modelo", $datos["id_modelo"], PDO::PARAM_STR);
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /**=====================================
    BORRAR MODELOS
    ======================================**/
    static public function mdlBorrarModelo($tabla, $datos){
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_modelo = :id_modelo");
        $stmt->bindParam(":id_modelo", $datos, PDO::PARAM_INT);
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}