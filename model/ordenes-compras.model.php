<?php
require_once "connection.php";

class ModelOrdenesCompras
{
    /*=============================================
	 MOSTRAR ORDEN COMPRA
	=============================================*/
    static public function mdlMostrarOrdenCompra($tabla, $item, $valor){
        if($item != null){
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        }else{
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla");
            $stmt ->execute();
            return $stmt->fetchAll();
        }
        $stmt->close();
        $stmt = null;
    }

    /*=============================================
	 REGISTRO DE ORDEN DE COMPRA
	=============================================*/
    static public function mdlIngresarOrdenCompra($tabla, $datos)
    {
        $stmt = Connection::  connect()->prepare("INSERT INTO $tabla(id_usuario, id_proveedor, codigo, fecha, proveedor, productos, feregistro) VALUES (:id_usuario, :id_proveedor, :codigo, :fecha, :proveedor, :productos, :feregistro)");
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_INT);
        $stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
        $stmt->bindParam(":fecha", $datos["fecha_emision"], PDO::PARAM_STR);
        $stmt->bindParam(":proveedor", $datos["proveedor"], PDO::PARAM_STR);
        $stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
        $stmt->bindParam(":feregistro", $datos["feregistro"], PDO::PARAM_STR);
        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
     EDITAR ORDEN DE COMPRA
    =======================================*/
    static public function mdlEditarOrdenCompra($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET id_proveedor = :id_proveedor, proveedor = :proveedor, productos = :productos WHERE codigo = :codigo");
        $stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
        $stmt->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_INT);
        $stmt->bindParam(":proveedor", $datos["proveedor"], PDO::PARAM_STR);
        $stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
     BORRAR ORDEN DE COMPRA
    =======================================*/
    static public function mdlBorrarOrdenCompra($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_orden_compra = :id_orden_compra");
        $stmt->bindParam(":id_orden_compra", $datos, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}