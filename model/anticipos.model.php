<?php


require_once "connection.php";

class ModelAnticipos{

	/**=====================================
	CREAR ANTICIPOS
	======================================**/

	static public function mdlIngresarAnticipo($tabla, $datos){

		$stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, codigo, proveedor, monto, saldo, fecha, descripcion, estatus, feregistro) VALUES (:id_usuario, :codigo, :proveedor, :monto, :saldo, :fecha, :descripcion, :estatus, :feregistro)");

		$stmt -> bindParam(":id_usuario", $datos["usuario"], PDO::PARAM_STR);
		$stmt -> bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
		$stmt -> bindParam(":proveedor", $datos["proveedor"], PDO::PARAM_STR);
		$stmt -> bindParam(":monto", $datos["monto"], PDO::PARAM_INT);
		$stmt -> bindParam(":saldo", $datos["saldo"], PDO::PARAM_INT);
		$stmt -> bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
		$stmt -> bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt -> bindParam(":estatus", $datos["estatus"], PDO::PARAM_STR);
		$stmt -> bindParam(":feregistro", $datos["fecha"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR ANTICIPOS
	=============================================*/

	static public function mdlMostrarAnticipos($tabla, $item, $valor){

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
	EDITAR ANTICIPO
	======================================**/

	static public function mdlEditarAnticipo($tabla, $datos){

		$stmt = Connection::connect()->prepare("UPDATE $tabla SET codigo = :codigo, monto = :monto, saldo = :saldo, fecha = :fecha, descripcion = :descripcion,  estatus = :estatus WHERE id_anticipo = :id_anticipo");

		$stmt -> bindParam(":id_anticipo", $datos["id_anticipo"], PDO::PARAM_INT);
		$stmt -> bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
		$stmt -> bindParam(":monto", $datos["monto"], PDO::PARAM_INT);
		$stmt -> bindParam(":saldo", $datos["saldo"], PDO::PARAM_INT);
		$stmt -> bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
		$stmt -> bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt -> bindParam(":estatus", $datos["estatus"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt -> close();
		
		$stmt = null;

	}

  	/**=====================================
    ELIMINAR ANTICIPO
    ======================================**/

    static public function mdlEliminarAnticipo($tabla, $datos){

        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_anticipo = :id_anticipo");

        $stmt -> bindParam(":id_anticipo", $datos, PDO::PARAM_INT);

        if($stmt -> execute()){

            return "ok";

        }else{

            return "error";
        
        }

        $stmt -> close();

        $stmt = null;

    }

}