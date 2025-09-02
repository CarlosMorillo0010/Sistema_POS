<?php
require_once "connection.php";

class ModelDevolucionesVentas
{
    /*=============================================
	MOSTRAR DEVOLUCION VENTA
	=============================================*/
    static public function mdlMostrarDevolucionVenta($tabla, $item, $valor){
        if($item != null){
            $stmt = Connection::connect()->prepare("SELECT dv.*, c.nombre as cliente FROM $tabla dv JOIN clientes c ON dv.id_cliente = c.id WHERE dv.$item = :$item");
            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt -> fetch();
        }else{
            $stmt = Connection::connect()->prepare("SELECT dv.*, c.nombre as cliente FROM $tabla dv JOIN clientes c ON dv.id_cliente = c.id ORDER BY dv.id_devolucion_venta DESC");
            $stmt -> execute();
            return $stmt -> fetchAll();
        }
        $stmt = null;
    }

    /*=============================================
    INGRESAR DEVOLUCION VENTA
    =============================================*/
    static public function mdlIngresarDevolucionVenta($tabla, $datos)
    {
        $db = Connection::connect();
        $db->beginTransaction();

        try {
            $stmt = $db->prepare("INSERT INTO $tabla (id_venta, id_cliente, fecha_devolucion, monto_total) VALUES (:id_venta, :id_cliente, :fecha_devolucion, :monto_total)");

            $stmt->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_INT);
            $stmt->bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_INT);
            $stmt->bindParam(":fecha_devolucion", $datos["fecha_devolucion"], PDO::PARAM_STR);
            $stmt->bindParam(":monto_total", $datos["monto_total"], PDO::PARAM_STR);

            $stmt->execute();
            $id_devolucion_venta = $db->lastInsertId();

            $productos = json_decode($datos["productos"], true);

            foreach ($productos as $key => $value) {
                $stmt_detalle = $db->prepare("INSERT INTO detalle_devoluciones_ventas (id_devolucion_venta, id_producto, cantidad, precio_unitario, subtotal) VALUES (:id_devolucion_venta, :id_producto, :cantidad, :precio_unitario, :subtotal)");

                $stmt_detalle->bindParam(":id_devolucion_venta", $id_devolucion_venta, PDO::PARAM_INT);
                $stmt_detalle->bindParam(":id_producto", $value["id_producto"], PDO::PARAM_INT);
                $stmt_detalle->bindParam(":cantidad", $value["cantidad"], PDO::PARAM_INT);
                $stmt_detalle->bindParam(":precio_unitario", $value["precio"], PDO::PARAM_STR);
                $stmt_detalle->bindParam(":subtotal", $value["total"], PDO::PARAM_STR);
                $stmt_detalle->execute();

                // Actualizar stock
                $stmt_stock = $db->prepare("UPDATE productos SET stock = stock + :cantidad WHERE id_producto = :id_producto");
                $stmt_stock->bindParam(":cantidad", $value["cantidad"], PDO::PARAM_INT);
                $stmt_stock->bindParam(":id_producto", $value["id_producto"], PDO::PARAM_INT);
                $stmt_stock->execute();
            }

            $db->commit();
            return "ok";
        } catch (Exception $e) {
            $db->rollBack();
            return "Error: " . $e->getMessage();
        }
    }
}