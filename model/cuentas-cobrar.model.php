<?php

require_once "connection.php";

class ModelCuentasCobrar{

	/*======================================
	CREAR CUENTA POR COBRAR
	======================================**/

	static public function mdlIngresarCuentaCobrar($tabla, $datos){

		$stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, codigo, proveedor, fecha_cuenta, fecha_ano, fecha_factura, nombre, tipo_documento, documento, monto, saldo, descripcion, estatus, feregistro) VALUES (:id_usuario, :codigo, :proveedor, :fecha_cuenta, :fecha_ano, :fecha_factura, :nombre, :tipo_documento, :documento, :monto, :saldo, :descripcion, :estatus, :feregistro)");

		$stmt -> bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
		$stmt -> bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
		$stmt -> bindParam(":proveedor", $datos["proveedor"], PDO::PARAM_STR);
		$stmt -> bindParam(":fecha_cuenta", $datos["fecha_cuenta"], PDO::PARAM_STR);
		$stmt -> bindParam(":fecha_ano", $datos["fecha_ano"], PDO::PARAM_STR);
		$stmt -> bindParam(":fecha_factura", $datos["fecha_factura"], PDO::PARAM_STR);
		$stmt -> bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt -> bindParam(":tipo_documento", $datos["tipo_documento"], PDO::PARAM_STR);
		$stmt -> bindParam(":documento", $datos["documento"], PDO::PARAM_INT);
		$stmt -> bindParam(":monto", $datos["monto"], PDO::PARAM_INT);
		$stmt -> bindParam(":saldo", $datos["saldo"], PDO::PARAM_INT);
		$stmt -> bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt -> bindParam(":estatus", $datos["estatus"], PDO::PARAM_STR);
		$stmt -> bindParam(":feregistro", $datos["feregistro"], PDO::PARAM_STR);

		if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
		$stmt = null;
		
	}

	/*=============================================
	MOSTRAR CUENTAS POR COBRAR
	=============================================*/
	static public function mdlMostrarCuentaCobrar($tabla, $item, $valor){
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
	EDITAR CUENTA POR COBRAR
	======================================**/

	static public function mdlEditarCuentaCobrar($tabla, $datos){

		$stmt = Connection::connect()->prepare("UPDATE $tabla SET codigo = :codigo, proveedor = :proveedor, fecha_cuenta = :fecha_cuenta, fecha_ano = :fecha_ano, fecha_factura = :fecha_factura, nombre = :nombre, tipo_documento = :tipo_documento, documento = :documento, monto = :monto, saldo = :saldo, descripcion = :descripcion, estatus = :estatus WHERE id_cuentas_cobrar = :id_cuentas_cobrar");

		$stmt -> bindParam(":id_cuentas_cobrar", $datos["id_cuentas_cobrar"], PDO::PARAM_INT);
		$stmt -> bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
		$stmt -> bindParam(":proveedor", $datos["proveedor"], PDO::PARAM_STR);
		$stmt -> bindParam(":fecha_cuenta", $datos["fecha_cuenta"], PDO::PARAM_STR);
		$stmt -> bindParam(":fecha_ano", $datos["fecha_ano"], PDO::PARAM_STR);
		$stmt -> bindParam(":fecha_factura", $datos["fecha_factura"], PDO::PARAM_STR);
		$stmt -> bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt -> bindParam(":tipo_documento", $datos["tipo_documento"], PDO::PARAM_STR);
		$stmt -> bindParam(":documento", $datos["documento"], PDO::PARAM_INT);
		$stmt -> bindParam(":monto", $datos["monto"], PDO::PARAM_INT);
		$stmt -> bindParam(":saldo", $datos["saldo"], PDO::PARAM_INT);
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

  	/*======================================
    ELIMINAR CUENTA POR COBRAR
    ======================================**/

    static public function mdlEliminarCuentaCobrar($tabla, $datos){

        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_cuentas_cobrar = :id_cuentas_cobrar");

        $stmt -> bindParam(":id_cuentas_cobrar", $datos, PDO::PARAM_INT);

        if($stmt -> execute()){

            return "ok";

        }else{

            return "error";
        
        }

        $stmt -> close();

        $stmt = null;

    }

}