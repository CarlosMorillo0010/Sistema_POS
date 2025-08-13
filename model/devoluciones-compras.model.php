<?php
require_once "connection.php";

class ModelDevolucionesCompras
{
    /*=============================================
	MOSTRAR DEVOLUCION COMPRA
	=============================================*/
    static public function mdlMostrarDevolucionCompra($tabla, $item, $valor){
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
}