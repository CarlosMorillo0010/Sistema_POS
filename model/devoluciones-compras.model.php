<?php
require_once "connection.php";

class ModelDevolucionesCompras
{
    /*=============================================
	MOSTRAR DEVOLUCION COMPRA
	=============================================*/
    static public function mdlMostrarDevolucionCompra($tabla, $item, $valor){
        if($item != null){
            $stmt = Connection::connect()->prepare("SELECT dc.*, p.nombre as proveedor FROM $tabla dc JOIN proveedores p ON dc.id_proveedor = p.id_proveedor WHERE dc.$item = :$item");
            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt -> fetch();
        }else{
            $stmt = Connection::connect()->prepare("SELECT dc.*, p.nombre as proveedor FROM $tabla dc JOIN proveedores p ON dc.id_proveedor = p.id_proveedor ORDER BY dc.id_devolucion_compra DESC");
            $stmt -> execute();
            return $stmt -> fetchAll();
        }
        $stmt = null;
    }

    /*=============================================
    INGRESAR DEVOLUCION COMPRA
    =============================================*/
    static public function mdlIngresarDevolucionCompra($tabla, $datos)
    {
        $db = Connection::connect();
        $db->beginTransaction();

        try {
            $stmt = $db->prepare("INSERT INTO $tabla (id_compra, id_proveedor, fecha_devolucion, monto_total) VALUES (:id_compra, :id_proveedor, :fecha_devolucion, :monto_total)");

            $stmt->bindParam(":id_compra", $datos["id_compra"], PDO::PARAM_INT);
            $stmt->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_INT);
            $stmt->bindParam(":fecha_devolucion", $datos["fecha_devolucion"], PDO::PARAM_STR);
            $stmt->bindParam(":monto_total", $datos["monto_total"], PDO::PARAM_STR);

            $stmt->execute();
            $id_devolucion_compra = $db->lastInsertId();

            $productos = json_decode($datos["productos"], true);

            foreach ($productos as $key => $value) {
                $stmt_detalle = $db->prepare("INSERT INTO detalle_devoluciones_compras (id_devolucion_compra, id_producto, cantidad, precio_unitario, subtotal) VALUES (:id_devolucion_compra, :id_producto, :cantidad, :precio_unitario, :subtotal)");

                $stmt_detalle->bindParam(":id_devolucion_compra", $id_devolucion_compra, PDO::PARAM_INT);
                $stmt_detalle->bindParam(":id_producto", $value["id_producto"], PDO::PARAM_INT);
                $stmt_detalle->bindParam(":cantidad", $value["cantidad"], PDO::PARAM_INT);
                $stmt_detalle->bindParam(":precio_unitario", $value["costo"], PDO::PARAM_STR);
                $stmt_detalle->bindParam(":subtotal", $value["subtotal"], PDO::PARAM_STR);
                $stmt_detalle->execute();

                // Actualizar stock
                $stmt_stock = $db->prepare("UPDATE productos SET stock = stock - :cantidad WHERE id_producto = :id_producto");
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