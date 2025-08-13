<?php
require_once "connection.php";

class ModelImpuestos{

    /**=====================================
    CREAR IMPUESTOS
    ======================================**/
    static public function mdlIngresarImpuesto($tabla, $datos){
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, impuesto, tasa, feregistro) VALUES (:id_usuario, :impuesto, :tasa, :feregistro)");
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":impuesto", $datos["impuesto"], PDO::PARAM_STR);
        $stmt->bindParam(":tasa", $datos["tasa"], PDO::PARAM_STR);
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
    MOSTRAR IMPUESTO
    ======================================**/

    static public function mdlMostrarImpuesto($tabla, $item, $valor){
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
    EDITAR IMPUESTO
    ======================================**/

    static public function mdlEditarImpuesto($tabla, $datos){
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET impuesto = :impuesto, tasa = :tasa  WHERE id_impuesto = :id_impuesto");
        $stmt->bindParam(":impuesto", $datos["impuesto"], PDO::PARAM_STR);
        $stmt->bindParam(":tasa", $datos["tasa"], PDO::PARAM_INT);
        $stmt->bindParam(":id_impuesto", $datos["id_impuesto"], PDO::PARAM_STR);
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /**=====================================
    BORRAR IMPUESTO
    ======================================**/

    static public function mdlBorrarImpuesto($tabla, $datos){
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_impuesto = :id_impuesto");
        $stmt->bindParam(":id_impuesto", $datos, PDO::PARAM_INT);
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}