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
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla ORDER BY id_orden_compra DESC");
            $stmt ->execute();
            return $stmt->fetchAll();
        }
        $stmt->close();
        $stmt = null;
    }

    /*=============================================
	 MOSTRAR DETALLE DE ORDEN COMPRA
	=============================================*/
    static public function mdlMostrarOrdenCompraDetalle($tabla, $item, $valor){
        $stmt = Connection::connect()->prepare("SELECT d.*, p.codigo FROM $tabla d JOIN productos p ON d.id_producto = p.id_producto WHERE d.$item = :$item");
        $stmt->bindParam(":".$item, $valor, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*=============================================
	 REGISTRO DE ORDEN DE COMPRA (NUEVA LÓGICA)
	=============================================*/
    static public function mdlIngresarOrdenCompra($tablaOrden, $tablaDetalle, $datos)
    {
        $db = Connection::connect();
        $db->beginTransaction();

        try {
            $stmt = $db->prepare("INSERT INTO $tablaOrden(id_usuario, id_proveedor, codigo, fecha, subtotal, impuestos, descuento, costo_envio, total, terminos_pago, notas, estado, feregistro) VALUES (:id_usuario, :id_proveedor, :codigo, :fecha, :subtotal, :impuestos, :descuento, :costo_envio, :total, :terminos_pago, :notas, :estado, :feregistro)");

            $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
            $stmt->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_INT);
            $stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
            $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
            $stmt->bindParam(":subtotal", $datos["subtotal"], PDO::PARAM_STR);
            $stmt->bindParam(":impuestos", $datos["impuestos"], PDO::PARAM_STR);
            $stmt->bindParam(":descuento", $datos["descuento"], PDO::PARAM_STR);
            $stmt->bindParam(":costo_envio", $datos["costo_envio"], PDO::PARAM_STR);
            $stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
            $stmt->bindParam(":terminos_pago", $datos["terminos_pago"], PDO::PARAM_STR);
            $stmt->bindParam(":notas", $datos["notas"], PDO::PARAM_STR);
            $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
            $stmt->bindParam(":feregistro", $datos["feregistro"], PDO::PARAM_STR);
            
            $stmt->execute();
            $idOrdenCompra = $db->lastInsertId();

            $productos = json_decode($datos["productos"], true);

            foreach ($productos as $key => $value) {
                $stmtDetalle = $db->prepare("INSERT INTO $tablaDetalle(id_orden_compra, id_producto, descripcion, cantidad, precio_compra, subtotal) VALUES (:id_orden_compra, :id_producto, :descripcion, :cantidad, :precio_compra, :subtotal)");
                $stmtDetalle->bindParam(":id_orden_compra", $idOrdenCompra, PDO::PARAM_INT);
                $stmtDetalle->bindParam(":id_producto", $value["id_producto"], PDO::PARAM_INT);
                $stmtDetalle->bindParam(":descripcion", $value["descripcion"], PDO::PARAM_STR);
                $stmtDetalle->bindParam(":cantidad", $value["cantidad"], PDO::PARAM_INT);
                $stmtDetalle->bindParam(":precio_compra", $value["precio_compra"], PDO::PARAM_STR);
                $stmtDetalle->bindParam(":subtotal", $value["subtotal"], PDO::PARAM_STR);
                $stmtDetalle->execute();
            }

            $db->commit();
            return "ok";

        } catch (Exception $e) {
            $db->rollBack();
            return "Error: " . $e->getMessage();
        }
    }

    /*======================================
     EDITAR ORDEN DE COMPRA
    =======================================*/
    static public function mdlEditarOrdenCompra($tablaOrden, $tablaDetalle, $datos)
    {
        $db = Connection::connect();
        $db->beginTransaction();

        try {
            $stmt = $db->prepare("UPDATE $tablaOrden SET id_proveedor = :id_proveedor, subtotal = :subtotal, impuestos = :impuestos, descuento = :descuento, costo_envio = :costo_envio, total = :total, terminos_pago = :terminos_pago, notas = :notas, estado = :estado WHERE id_orden_compra = :id_orden_compra");

            $stmt->bindParam(":id_orden_compra", $datos["id_orden_compra"], PDO::PARAM_INT);
            $stmt->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_INT);
            $stmt->bindParam(":subtotal", $datos["subtotal"], PDO::PARAM_STR);
            $stmt->bindParam(":impuestos", $datos["impuestos"], PDO::PARAM_STR);
            $stmt->bindParam(":descuento", $datos["descuento"], PDO::PARAM_STR);
            $stmt->bindParam(":costo_envio", $datos["costo_envio"], PDO::PARAM_STR);
            $stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
            $stmt->bindParam(":terminos_pago", $datos["terminos_pago"], PDO::PARAM_STR);
            $stmt->bindParam(":notas", $datos["notas"], PDO::PARAM_STR);
            $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
            $stmt->execute();

            // Borrar detalles anteriores
            $stmtDelete = $db->prepare("DELETE FROM $tablaDetalle WHERE id_orden_compra = :id_orden_compra");
            $stmtDelete->bindParam(":id_orden_compra", $datos["id_orden_compra"], PDO::PARAM_INT);
            $stmtDelete->execute();

            // Insertar nuevos detalles
            $productos = json_decode($datos["productos"], true);
            foreach ($productos as $key => $value) {
                $stmtDetalle = $db->prepare("INSERT INTO $tablaDetalle(id_orden_compra, id_producto, descripcion, cantidad, precio_compra, subtotal) VALUES (:id_orden_compra, :id_producto, :descripcion, :cantidad, :precio_compra, :subtotal)");
                $stmtDetalle->bindParam(":id_orden_compra", $datos["id_orden_compra"], PDO::PARAM_INT);
                $stmtDetalle->bindParam(":id_producto", $value["id_producto"], PDO::PARAM_INT);
                $stmtDetalle->bindParam(":descripcion", $value["descripcion"], PDO::PARAM_STR);
                $stmtDetalle->bindParam(":cantidad", $value["cantidad"], PDO::PARAM_INT);
                $stmtDetalle->bindParam(":precio_compra", $value["precio_compra"], PDO::PARAM_STR);
                $stmtDetalle->bindParam(":subtotal", $value["subtotal"], PDO::PARAM_STR);
                $stmtDetalle->execute();
            }

            $db->commit();
            return "ok";

        } catch (Exception $e) {
            $db->rollBack();
            return "Error: " . $e->getMessage();
        }
    }

    /*======================================
     ACTUALIZAR ORDEN DE COMPRA (GENÉRICO)
    =======================================*/
    static public function mdlActualizarOrden($tabla, $item1, $valor1, $item2, $valor2)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET $item1 = :valor1 WHERE $item2 = :valor2");
        $stmt->bindParam(":valor1", $valor1, PDO::PARAM_STR);
        $stmt->bindParam(":valor2", $valor2, PDO::PARAM_STR);
        
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

    /*=============================================
	 MOSTRAR MULTIPLES ORDENES POR FILTRO
	=============================================*/
    static public function mdlMostrarMultiplesOrdenes($tabla, $item, $valor){
        $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY fecha DESC");
        $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(); // Devuelve siempre un array
    }
}
