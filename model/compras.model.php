<?php

require_once "connection.php";

class ModelCompras
{

    /*=============================================
    INGRESAR COMPRA
    =============================================*/

    static public function mdlIngresarCompra($tabla, $datos)
    {

        $db = Connection::connect();
        $db->beginTransaction();

        try {

            $stmt = $db->prepare("INSERT INTO $tabla (id_orden_compra, id_proveedor, fecha_compra, monto_total) VALUES (:id_orden_compra, :id_proveedor, :fecha_compra, :monto_total)");

            $stmt->bindParam(":id_orden_compra", $datos["id_orden_compra"], PDO::PARAM_INT);
            $stmt->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_INT);
            $stmt->bindParam(":fecha_compra", $datos["fecha_compra"], PDO::PARAM_STR);
            $stmt->bindParam(":monto_total", $datos["monto_total"], PDO::PARAM_STR);

            $stmt->execute();
            $id_compra = $db->lastInsertId();

            $productos = json_decode($datos["productos"], true);

            foreach ($productos as $key => $value) {

                $stmt_detalle = $db->prepare("INSERT INTO detalle_compras (id_compra, id_producto, cantidad, precio_unitario, subtotal) VALUES (:id_compra, :id_producto, :cantidad, :precio_unitario, :subtotal)");

                $stmt_detalle->bindParam(":id_compra", $id_compra, PDO::PARAM_INT);
                $stmt_detalle->bindParam(":id_producto", $value["id_producto"], PDO::PARAM_INT);
                $stmt_detalle->bindParam(":cantidad", $value["cantidad"], PDO::PARAM_INT);
                $stmt_detalle->bindParam(":precio_unitario", $value["costo"], PDO::PARAM_STR);
                $stmt_detalle->bindParam(":subtotal", $value["subtotal"], PDO::PARAM_STR);
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

    /*=============================================
    MOSTRAR COMPRAS
    =============================================*/
    static public function mdlMostrarCompras($tabla, $item, $valor){
        if($item != null){
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        }else{
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla ORDER BY id_compra DESC");
            $stmt ->execute();
            return $stmt->fetchAll();
        }
        $stmt->close();
        $stmt = null;
    }
}
