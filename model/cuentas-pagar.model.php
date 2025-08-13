<?php

require_once "connection.php";

class ModelCuentasPagar{

	/*======================================
	CREAR CUENTA POR PAGAR
	======================================**/

	static public function mdlIngresarCuentaPagar($tabla, $datos) {
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla (id_usuario, id_libro_compra, proveedor, factura, monto, iva, total, fecha_emision, fecha_vencimiento, estado, metodo_pago, observacion, feregistro) VALUES (:id_usuario, :id_libro_compra, :proveedor, :factura, :monto, :iva, :total, :fecha_emision, :fecha_vencimiento, :estado, :metodo_pago, :observacion, :feregistro)");

        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":id_libro_compra", $datos["id_libro_compra"], PDO::PARAM_INT);
        $stmt->bindParam(":proveedor", $datos["proveedor"], PDO::PARAM_STR);
        $stmt->bindParam(":factura", $datos["factura"], PDO::PARAM_STR);
        $stmt->bindParam(":monto", $datos["monto"], PDO::PARAM_STR);
        $stmt->bindParam(":iva", $datos["iva"], PDO::PARAM_STR);
        $stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_emision", $datos["fecha_emision"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_vencimiento", $datos["fecha_vencimiento"], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
        $stmt->bindParam(":metodo_pago", $datos["metodo_pago"], PDO::PARAM_STR);
        $stmt->bindParam(":observacion", $datos["observacion"], PDO::PARAM_STR);
        $stmt->bindParam(":feregistro", $datos["feregistro"], PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

        $stmt->close();
        $stmt = null;
    }

	/*=============================================
	MOSTRAR CUENTAS POR PAGAR
	=============================================*/
	static public function mdlMostrarCuentaPagar($tabla, $item, $valor){
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
	EDITAR CUENTA POR PAGAR
	======================================**/

	static public function mdlEditarCuentaPagar($tabla, $datos){

		$stmt = Connection::connect()->prepare("UPDATE $tabla SET proveedor = :proveedor, factura = :factura, monto = :monto, iva = :iva, total = :total, fecha_emision = :fecha_emision, fecha_vencimiento = :fecha_vencimiento, estado = :estado, metodo_pago = :metodo_pago, observacion = :observacion WHERE id_libro_compra = :id_libro_compra");

		$stmt->bindParam(":id_libro_compra", $datos["id_libro_compra"], PDO::PARAM_INT);
        $stmt->bindParam(":proveedor", $datos["proveedor"], PDO::PARAM_STR);
        $stmt->bindParam(":factura", $datos["factura"], PDO::PARAM_STR);
        $stmt->bindParam(":monto", $datos["monto"], PDO::PARAM_STR);
        $stmt->bindParam(":iva", $datos["iva"], PDO::PARAM_STR);
        $stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_emision", $datos["fecha_emision"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_vencimiento", $datos["fecha_vencimiento"], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
        $stmt->bindParam(":metodo_pago", $datos["metodo_pago"], PDO::PARAM_STR);
        $stmt->bindParam(":observacion", $datos["observacion"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt -> close();
		
		$stmt = null;

	}

	/*=============================================
	ACTUALIZAR ESTADO DE LA CUENTA A PAGADA
	=============================================*/
	 static public function mdlPagarCuenta($datos) {
        
        $db = Connection::connect();
        $db->beginTransaction();

        try {
            // 1. Obtener el id_libro_compra desde la tabla cuentas_pagar usando el ID de la cuenta por pagar
            $stmt_get_id = $db->prepare("SELECT id_libro_compra FROM cuentas_pagar WHERE id_libro_compra = :id_libro_compra");
            $stmt_get_id->bindParam(":id_libro_compra", $datos["id_libro_compra"], PDO::PARAM_INT);
            $stmt_get_id->execute();
            $resultado = $stmt_get_id->fetch(PDO::FETCH_ASSOC);

            if (!$resultado) {
                $db->rollBack();
                return "error_no_encontrado";
            }
            $idLibroCompra = $resultado['id_libro_compra'];

            // 2. Actualizar la tabla 'cuentas_pagar' (SIN CAMBIOS AQUÍ)
            $stmt1 = $db->prepare(
                "UPDATE cuentas_pagar SET 
                 estado = :estado, 
                 fecha_pago = :fecha_pago,
                 nota_pago = :nota_pago
                 WHERE id_libro_compra = :id_libro_compra"
            );
            $stmt1->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
            $stmt1->bindParam(":fecha_pago", $datos["fecha_pago"], PDO::PARAM_STR);
            $stmt1->bindParam(":nota_pago", $datos["nota_pago"], PDO::PARAM_STR);
            $stmt1->bindParam(":id_libro_compra", $datos["id_libro_compra"], PDO::PARAM_INT);
            $stmt1->execute();

            // 3. Actualizar la tabla 'libro_compras' (AQUÍ ESTÁ LA CORRECCIÓN)
            $stmt2 = $db->prepare(
                "UPDATE libro_compras SET 
                 estado = :estado 
                 WHERE id = :id" 
            );
            $stmt2->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
            $stmt2->bindParam(":id", $idLibroCompra, PDO::PARAM_INT);
            $stmt2->execute();

            // 4. Si todo fue bien, confirmamos los cambios
            $db->commit();
            
            return "ok";

        } catch (Exception $e) {
            $db->rollBack();
            // Para depuración, puedes registrar el error: error_log($e->getMessage());
            return "error_transaccion";
        }
    }

	/*===================================================================
    MOSTRAR DEUDAS PRÓXIMAS A VENCER O VENCIDAS
    ===================================================================*/
    static public function mdlMostrarRecordatoriosDeuda()
    {
        // Define el rango de días para el recordatorio
        $diasRecordatorio = 7;

        $stmt = Connection::connect()->prepare(
            "SELECT *, DATEDIFF(fecha_vencimiento, CURDATE()) as dias_para_vencer
             FROM cuentas_pagar
             WHERE estado = 'Pendiente'
             AND fecha_vencimiento <= DATE_ADD(CURDATE(), INTERVAL :diasRecordatorio DAY)
             ORDER BY fecha_vencimiento ASC"
        );

        $stmt->bindParam(":diasRecordatorio", $diasRecordatorio, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();

        $stmt->close();
        $stmt = null;
    }

}