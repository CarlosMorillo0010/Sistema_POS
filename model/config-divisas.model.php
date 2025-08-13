<?php

/*======================================
CONNECTION DATABASE
======================================**/
require_once "connection.php";

class ModelConfiguracionesDivisas
{
    /*======================================
    REGISTRO DE VALOR DIVISA
    ======================================**/
    static public function mdlIngresarConfiguracionDivisa($tabla, $datos){
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, divisa, simbolo, valor_inventario, valor_venta, feregistro) VALUES (:id_usuario, :divisa, :simbolo, :valor_inventario, :valor_venta, :feregistro)");
        $stmt -> bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
        $stmt -> bindParam(":divisa", $datos["divisa"], PDO::PARAM_STR);
        $stmt -> bindParam(":simbolo", $datos["simbolo"], PDO::PARAM_STR);
        $stmt -> bindParam(":valor_inventario", $datos["valor_inventario"], PDO::PARAM_STR);
        $stmt -> bindParam(":valor_venta", $datos["valor_venta"], PDO::PARAM_STR);
        $stmt -> bindParam(":feregistro", $datos["fecha"]);

        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
     MOSTRAR VALOR DIVISAS
    ======================================**/
    static public function mdlMostrarConfiguracionDivisa($tabla, $item, $valor){
        if ($item != null){
            $stmt = Connection::  connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt -> fetch();
        }else{
            $stmt = Connection::  connect()->prepare("SELECT * FROM $tabla");
            $stmt -> execute();
            return $stmt -> fetchAll();
        }
        $stmt -> close();
        $stmt = null;
    }

    /*======================================
     EDITAR VALOR DIVISAS
    ======================================**/
    static public function mdlEditarConfiguracionDivisa($tabla, $datos){
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET valor_inventario = :valor_inventario, valor_venta = :valor_venta WHERE id_valor = :id_valor");
        $stmt->bindParam(":valor_inventario", $datos["valor_inventario"], PDO::PARAM_STR);
        $stmt->bindParam(":valor_venta", $datos["valor_venta"], PDO::PARAM_STR);
        $stmt->bindParam(":id_valor", $datos["id_valor"], PDO::PARAM_INT);
        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
     BORRAR VALOR DIVISAS
    ======================================**/
    static public function mdlBorrarConfiguracionDivisa($tabla, $datos){
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_valor = :id_valor");
        $stmt->bindParam(":id_valor", $datos, PDO::PARAM_INT);
        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}