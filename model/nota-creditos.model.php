<?php
require_once "connection.php";

class ModelNotaCreditos
{
    /*=============================================
	MOSTRAR NOTAS DE CREDITO
	=============================================*/
    static public function mdlMostrarNotaCredito($tabla, $item, $valor){
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
	 REGISTRO NOTAS DE CREDITO
	=============================================*/
    static public function mdlIngresarNotaCredito($tabla, $datos)
    {
        $stmt = Connection::  connect()->prepare("INSERT INTO $tabla(id_usuario, id_cliente, id_vendedor, codigo, vendedor, fecha_registro, fecha_vencimiento, cliente, productos, total_nota_credito, feregistro) VALUES (:id_usuario, :id_cliente, :id_vendedor, :codigo, :vendedor, :fecha_registro, :fecha_vencimiento, :cliente, :productos, :total_nota_credito, :feregistro)");
        $stmt -> bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt -> bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_INT);
        $stmt -> bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_INT);
        $stmt -> bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
        $stmt -> bindParam(":vendedor", $datos["vendedor"], PDO::PARAM_STR);
        $stmt -> bindParam(":fecha_registro", $datos["fecha_registro"], PDO::PARAM_STR);
        $stmt -> bindParam(":fecha_vencimiento", $datos["fecha_vencimiento"], PDO::PARAM_STR);
        $stmt -> bindParam(":cliente", $datos["cliente"], PDO::PARAM_STR);
        $stmt -> bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
        $stmt -> bindParam(":total_nota_credito", $datos["total_nota_credito"], PDO::PARAM_STR);
        $stmt -> bindParam(":feregistro", $datos["feregistro"], PDO::PARAM_STR);
        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}