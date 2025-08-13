<?php

require_once "connection.php";

class ModelPagos{

    /*======================================
    CREAR PAGO
    ======================================**/
    static public function mdlIngresarPago($tabla, $datos){
	    $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, forma_pago, feregistro) VALUES (:id_usuario, :forma_pago, :feregistro)");
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
	    $stmt->bindParam(":forma_pago", $datos["formaPago"], PDO::PARAM_STR);
        $stmt->bindParam(":feregistro", $datos["fecha"], PDO::PARAM_STR);
    	if($stmt->execute()){
    		return "ok";
    	}else{
    		return "error";
    	}
    	$stmt->close();
    	$stmt = null;
    }

/*======================================
MOSTRAR PAGOS
======================================**/

	static public function mdlMostrarPago($tabla, $item, $valor){
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

    /*======================================
    EDITAR PAGOS
    ======================================**/

    static public function mdlEditarPago($tabla, $datos){
    $stmt = Connection::connect()->prepare("UPDATE $tabla SET forma_pago = :forma_pago  WHERE id_forma_pagos = :id_forma_pagos");
    $stmt->bindParam(":forma_pago", $datos["forma_pago"], PDO::PARAM_STR);
    $stmt->bindParam(":id_forma_pagos", $datos["id_forma_pagos"], PDO::PARAM_STR);
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

  /*======================================
  BORRAR PAGOS
  ======================================**/
    static public function mdlBorrarPago($tabla, $datos){
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_forma_pagos = :id_forma_pagos");
        $stmt->bindParam(":id_forma_pagos", $datos, PDO::PARAM_INT);
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}