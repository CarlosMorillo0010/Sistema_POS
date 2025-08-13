<?php

require_once "connection.php";

class ModelBancos{

    /**=====================================
    CREAR BANCO
    ======================================**/
    static public function mdlIngresarBanco($tabla, $datos){
	$stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, banco, feregistro) VALUES (:id_usuario, :banco, :feregistro)");
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
	    $stmt->bindParam(":banco", $datos["nombreBanco"], PDO::PARAM_STR);
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
    MOSTRAR BANCOS
    ======================================**/

	static public function mdlMostrarBanco($tabla, $item, $valor){
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
    EDITAR BANCOS
    ======================================**/
    static public function mdlEditarBanco($tabla, $datos){
    $stmt = Connection::connect()->prepare("UPDATE $tabla SET banco = :banco  WHERE id_banco = :id_banco");
    $stmt->bindParam(":banco", $datos["banco"], PDO::PARAM_STR);
    $stmt->bindParam(":id_banco", $datos["id_banco"], PDO::PARAM_STR);
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

  /**=====================================
  BORRAR BANCOS
  ======================================**/
    static public function mdlBorrarBanco($tabla, $datos){
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_banco = :id_banco");
        $stmt->bindParam(":id_banco", $datos, PDO::PARAM_INT);
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}