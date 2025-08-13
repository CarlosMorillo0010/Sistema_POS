<?php

/**=====================================
    CONNECTION DATA BASE
======================================**/
require_once "connection.php";

class ModelArticulos{
        /**=====================================
            MOSTRAR ARTICULOS
        ======================================**/
        static public function mdlMostrarArticulos($tabla, $item, $valor){
            if ($item != null){
                $stmt = Connection::  connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item ");
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

        /**=====================================
            REGISTRO DEL ARTICULO
        ======================================**/
        static public function mdlIngresarArticulo($tabla, $datos){
            $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, id, articulo, imagen, unidades, precio_compra, precio_venta, feregistro) VALUES (:id_usuario, :id, :articulo, :imagen, :unidades, :precio_compra, :precio_venta, :feregistro)");

            $stmt -> bindParam(":id_usuario", $datos["usuario"], PDO::PARAM_STR);
            $stmt -> bindParam(":id", $datos["id"], PDO::PARAM_STR);
            $stmt -> bindParam(":articulo", $datos["articulo"], PDO::PARAM_STR);
            $stmt -> bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
            $stmt -> bindParam(":unidades", $datos["unidades"], PDO::PARAM_STR);
            $stmt -> bindParam(":precio_compra", $datos["precio_compra"], PDO::PARAM_STR);
            $stmt -> bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);
            $stmt -> bindParam(":feregistro", $datos["fecha"], PDO::PARAM_STR);

            if ($stmt->execute()){
                return "ok";
            }else{
                return "error";
            }
            $stmt->close();
            $stmt = null;
        }

    /**=====================================
        EDITAR ARTICULO
    ======================================**/
    static public function mdlEditarArticulo($tabla, $datos){
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET id = :id, articulo = :articulo, imagen = :imagen, unidades = :unidades, precio_compra = :precio_compra, precio_venta = :precio_venta WHERE articulo = :articulo");

        $stmt -> bindParam(":id", $datos["id"], PDO::PARAM_INT);
        $stmt -> bindParam(":articulo", $datos["articulo"], PDO::PARAM_STR);
        $stmt -> bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
        $stmt -> bindParam(":unidades", $datos["unidades"], PDO::PARAM_STR);
        $stmt -> bindParam(":precio_compra", $datos["precio_compra"], PDO::PARAM_STR);
        $stmt -> bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);

        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /**=====================================
        ELIMINAR ARTICULO
    ======================================**/
    static public function mdlEliminarArticulo($tabla, $datos){

        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_articulo = :id_articulo");
        $stmt -> bindParam(":id_articulo", $datos, PDO::PARAM_INT);

        if($stmt -> execute()){
            return "ok";
        }else{
            return "error";
        }

        $stmt -> close();
        $stmt = null;
    }
}