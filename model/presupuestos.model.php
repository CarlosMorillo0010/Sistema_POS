<?php
require_once "connection.php";

class ModelPresupuestos
{
    /*=============================================
	MOSTRAR PRESUPUESTO
	=============================================*/
    static public function mdlMostrarPresupuesto($tabla, $item, $valor){
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

    /*=============================================
	 REGISTRO PRESUPUESTO
	=============================================*/
    static public function mdlIngresarPresupuesto($tabla, $datos)
    {
        $stmt = Connection::  connect()->prepare("INSERT INTO $tabla(id_usuario, id_cliente, id_vendedor, codigo, vendedor, fecha_registro, fecha_vencimiento, cliente, productos, total_presupuesto, feregistro) VALUES (:id_usuario, :id_cliente, :id_vendedor, :codigo, :vendedor, :fecha_registro, :fecha_vencimiento, :cliente, :productos, :total_presupuesto, :feregistro)");
        $stmt -> bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt -> bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_INT);
        $stmt -> bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_INT);
        $stmt -> bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
        $stmt -> bindParam(":vendedor", $datos["vendedor"], PDO::PARAM_STR);
        $stmt -> bindParam(":fecha_registro", $datos["fecha_registro"], PDO::PARAM_STR);
        $stmt -> bindParam(":fecha_vencimiento", $datos["fecha_vencimiento"], PDO::PARAM_STR);
        $stmt -> bindParam(":cliente", $datos["cliente"], PDO::PARAM_STR);
        $stmt -> bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
        $stmt -> bindParam(":total_presupuesto", $datos["total_presupuesto"], PDO::PARAM_STR);
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
     EDITAR PRESUPUESTO
    =======================================*/
    static public function mdlEditarPresupuesto($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET cliente = :cliente, productos = :productos WHERE id_factura_venta = :id_factura_venta");
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
     BORRAR PRESUPUESTO
    =======================================*/
    static public function mdlBorrarPresupuesto($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_presupuesto = :id_presupuesto");
        $stmt->bindParam(":id_presupuesto", $datos, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}