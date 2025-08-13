<?php

require_once "connection.php";

class ModelPedidos{
    /*=============================================
	 MOSTRAR PEDIDOS
	=============================================*/
    static public function mdlMostrarPedidos($tabla, $item, $valor){
        if ($item != null){
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        }else{
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt->close();
        $stmt = null;
    }

    /*=============================================
	 REGISTRO PEDIDOS
	=============================================*/
    static public function mdlIngresarPedidos($tabla, $datos)
    {
        $stmt = Connection::  connect()->prepare("INSERT INTO $tabla(id_usuario, id_cliente, id_vendedor, codigo, vendedor, fecha_registro, cliente, productos, total_pedido, feregistro) VALUES (:id_usuario, :id_cliente, :id_vendedor, :codigo, :vendedor, :fecha_registro, :cliente, :productos, :total_pedido, :feregistro)");
        $stmt -> bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt -> bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_INT);
        $stmt -> bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_INT);
        $stmt -> bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
        $stmt -> bindParam(":vendedor", $datos["vendedor"], PDO::PARAM_STR);
        $stmt -> bindParam(":fecha_registro", $datos["fecha_registro"], PDO::PARAM_STR);
        $stmt -> bindParam(":cliente", $datos["cliente"], PDO::PARAM_STR);
        $stmt -> bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
        $stmt -> bindParam(":total_pedido", $datos["total_pedido"], PDO::PARAM_STR);
        $stmt -> bindParam(":feregistro", $datos["feregistro"], PDO::PARAM_STR);
        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
     EDITAR PEDIDOS
    =======================================*/
    static public function mdlEditarPedido($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET cliente = :cliente, productos = :productos WHERE id_pedido = :id_pedido");
        $stmt->bindParam(":proveedor", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":productos", $datos["password"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
     BORRAR PEDIDOS
    =======================================*/
    static public function mdlBorrarPedido($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_pedido = :id_pedido");
        $stmt->bindParam(":id_pedido", $datos, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}