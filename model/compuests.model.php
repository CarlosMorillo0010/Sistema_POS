<?php

/*======================================
 CONNECTION DATA BASE
======================================**/
require_once "connection.php";

class ModelCompuestos{
        /*======================================
         MOSTRAR PRODUCTOS COMPUESTO
        ======================================**/
        static public function mdlMostrarCompuesto($tabla, $item, $valor){
            if ($item != null){
                $stmt = Connection::  connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY codigo DESC");
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
         REGISTRO DE PRODUCTOS COMPUESTO
        ======================================**/
        static public function mdlIngresarCompuesto($tabla, $datos){
            $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, id, codigo, servicio, descripcion, stock, unidad,precio_unitario, precio_unitario_total, precio_oferta, precio_oferta_total, productos, imagen, feregistro) VALUES (:id_usuario, :id, :codigo, :servicio, :descripcion, :stock, :unidad, :precio_unitario, :precio_unitario_total, :precio_oferta, :precio_oferta_total, :productos, :imagen, :feregistro)");

            $stmt -> bindParam(":id_usuario", $datos["usuario"], PDO::PARAM_STR);
            $stmt -> bindParam(":id", $datos["id"], PDO::PARAM_STR);
            $stmt -> bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
            $stmt -> bindParam(":servicio", $datos["servicio"], PDO::PARAM_STR);
            $stmt -> bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
            $stmt -> bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
            $stmt -> bindParam(":unidad", $datos["unidad"], PDO::PARAM_STR);
            $stmt -> bindParam(":precio_unitario", $datos["precio_unitario"], PDO::PARAM_STR);
            $stmt -> bindParam(":precio_unitario_total", $datos["precio_unitario_total"], PDO::PARAM_STR);
            $stmt -> bindParam(":precio_oferta", $datos["precio_oferta"], PDO::PARAM_STR);
            $stmt -> bindParam(":precio_oferta_total", $datos["precio_oferta_total"], PDO::PARAM_STR);
            $stmt -> bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
            $stmt -> bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
            $stmt -> bindParam(":feregistro", $datos["fecha"], PDO::PARAM_STR);

            if ($stmt->execute()){
                return "ok";
            }else{
                return "error";
            }
            $stmt->close();
            $stmt = null;
        }

    /*======================================
     EDITAR PRODUCTOS COMPUESTO
    ======================================**/
    static public function mdlEditarCompuesto($tabla, $datos){
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET id = :id, servicio = :servicio, descripcion = :descripcion, stock = :stock, unidad = :unidad, precio_unitario = :precio_unitario, precio_unitario_total = :precio_unitario_total, precio_oferta = :precio_oferta, precio_oferta_total = :precio_oferta_total, precio_mayor = :precio_mayor, precio_mayor_total = :precio_mayor_total, codigo_barras = :codigo_barras, imagen = :imagen WHERE codigo = :codigo");

        $stmt -> bindParam(":id", $datos["id"], PDO::PARAM_STR);
        $stmt -> bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
        $stmt -> bindParam(":servicio", $datos["servicio"], PDO::PARAM_STR);
        $stmt -> bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
        $stmt -> bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
        $stmt -> bindParam(":unidad", $datos["unidad"], PDO::PARAM_STR);
        $stmt -> bindParam(":precio_unitario", $datos["precio_unitario"], PDO::PARAM_STR);
        $stmt -> bindParam(":precio_unitario_total", $datos["precio_unitario_total"], PDO::PARAM_STR);
        $stmt -> bindParam(":precio_oferta", $datos["precio_oferta"], PDO::PARAM_STR);
        $stmt -> bindParam(":precio_oferta_total", $datos["precio_oferta_total"], PDO::PARAM_STR);
        $stmt -> bindParam(":precio_mayor", $datos["precio_mayor"], PDO::PARAM_STR);
        $stmt -> bindParam(":precio_mayor_total", $datos["precio_mayor_total"], PDO::PARAM_STR);
        $stmt -> bindParam(":codigo_barras", $datos["codigo_barras"], PDO::PARAM_STR);
        $stmt -> bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);

        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
     BORRAR PRODUCTOS COMPUESTO
    ======================================**/
    static public function mdlEliminarCompuesto($tabla, $datos){
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_compuesto = :id_compuesto");
        $stmt -> bindParam(":id_compuesto", $datos, PDO::PARAM_INT);
        if($stmt -> execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt -> close();
        $stmt = null;
    }
}