<?php

require_once "connection.php";
require_once "compras.model.php"; // Incluir el modelo de compras
require_once "ordenes-compras.model.php"; // Incluir el modelo de ordenes

class ModelEntradas
{

    /*=============================================
     MOSTRAR ENTRADAS
    =============================================*/
    static public function mdlMostrarEntradas($tabla, $item, $valor)
    {
        // Lógica de base de datos para mostrar entradas (se puede desarrollar después)
    }

    /*=============================================
     REGISTRAR ENTRADA (NUEVO FLUJO DE COMPRA)
    =============================================*/
    static public function mdlIngresarEntrada($tablaEntrada, $tablaDetalleEntrada, $datos)
    {
        $db = Connection::connect();
        $db->beginTransaction();

        try {
            // 1. Insertar la cabecera de la entrada de almacén
            $stmtEntrada = $db->prepare("INSERT INTO $tablaEntrada(id_orden_compra, id_proveedor, id_usuario, fecha, monto_total) VALUES (:id_orden_compra, :id_proveedor, :id_usuario, :fecha, :monto_total)");
            $stmtEntrada->bindParam(":id_orden_compra", $datos["id_orden_compra"], PDO::PARAM_INT);
            $stmtEntrada->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_INT);
            $stmtEntrada->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
            $stmtEntrada->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
            $stmtEntrada->bindParam(":monto_total", $datos["monto_total"], PDO::PARAM_STR);
            $stmtEntrada->execute();
            $idEntrada = $db->lastInsertId();

            $productos = json_decode($datos["productos"], true);

            // 2. Insertar el detalle de la entrada y actualizar stock (a través de mdlIngresarCompra)
            foreach ($productos as $value) {
                $stmtDetalle = $db->prepare("INSERT INTO $tablaDetalleEntrada(id_entrada, id_producto, cantidad) VALUES (:id_entrada, :id_producto, :cantidad)");
                $stmtDetalle->bindParam(":id_entrada", $idEntrada, PDO::PARAM_INT);
                $stmtDetalle->bindParam(":id_producto", $value["id_producto"], PDO::PARAM_INT);
                $stmtDetalle->bindParam(":cantidad", $value["cantidad"], PDO::PARAM_INT);
                $stmtDetalle->execute();
            }

            // 3. Llamar a la función existente para crear la Compra y actualizar el stock
            $datosCompra = [
                "id_orden_compra" => $datos["id_orden_compra"],
                "id_proveedor" => $datos["id_proveedor"],
                "fecha_compra" => $datos["fecha"],
                "monto_total" => $datos["monto_total"],
                "productos" => $datos["productos"] // El formato ya es compatible
            ];
            
            // Pasamos la conexión de la transacción actual a la función
            $resultadoCompra = ModelCompras::mdlIngresarCompra("compras", $datosCompra);

            if ($resultadoCompra !== "ok") {
                throw new Exception("Error al ingresar la compra y actualizar el stock: " . $resultadoCompra);
            }

            // 4. Actualizar el estado de la Orden de Compra a 'Recibida'
            ModelOrdenesCompras::mdlActualizarOrden("orden_compra", "estado", "Recibida", "id_orden_compra", $datos["id_orden_compra"]);

            $db->commit();
            return "ok";

        } catch (Exception $e) {
            $db->rollBack();
            return "Error en la transacción: " . $e->getMessage();
        }
    }

    /*=============================================
     EDITAR ENTRADA
    =============================================*/
    static public function mdlEditarEntrada($tabla, $datos)
    {
        // Lógica de base de datos para editar la entrada
    }

    /*=============================================
     ANULAR ENTRADA
    =============================================*/
    static public function mdlAnularEntrada($tabla, $datos)
    {
        // Lógica de base de datos para anular la entrada
    }

}
