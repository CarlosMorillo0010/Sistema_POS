<?php

require_once "connection.php";

class ModelVentas{

     /*=============================================
    ACTUALIZAR ESTADO EN LA BD
    =============================================*/
    static public function mdlActualizarEstadoVenta($tabla, $datos) {

        $stmt = Connection::connect()->prepare("UPDATE $tabla SET estado = :estado WHERE id_venta = :id_venta");

        $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
        $stmt->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }

        $stmt->close();
        $stmt = null;

    }

    /*=============================================
     MOSTRAR VENTAS
    =============================================*/
    static public function mdlMostrarVenta($tabla, $item, $valor)
    {
        if ($item != null) {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id_venta ASC");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla ORDER BY id_venta ASC");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        // No es necesario close() para PDO cuando se maneja así.
    }

    /*=============================================
     RANGO FECHAS
    =============================================*/
    static public function mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal)
    {
        if ($fechaInicial == null) {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla ORDER BY id_venta ASC");
            $stmt->execute();
            return $stmt->fetchAll();
        } else if ($fechaInicial == $fechaFinal) {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE fecha LIKE :fecha");
            $stmt->bindValue(":fecha", "%$fechaFinal%", PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            // Lógica mejorada y segura con parámetros
            $fechaFinalMasUno = date('Y-m-d', strtotime($fechaFinal . ' +1 day'));
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE fecha BETWEEN :fechaInicial AND :fechaFinal");
            $stmt->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
            $stmt->bindParam(":fechaFinal", $fechaFinalMasUno, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }

    /*=============================================
    INGRESAR VENTA Y DETALLE DENTRO DE UNA TRANSACCIÓN
    =============================================*/
    static public function mdlIngresarVentaConexion($pdo, $tablaVentas, $tablaDetalle, $datos)
    {
        // 1. --- INSERTAR EN LA TABLA MAESTRA 'ventas' ---
        $sqlVenta = "INSERT INTO $tablaVentas (id_usuario, id_cliente, id_vendedor, codigo_venta, vendedor, productos, metodo_pago, tasa_dia, subtotal_usd, subtotal_bs, iva_usd, iva_bs, total_usd, total_bs, fecha) 
                     VALUES (:id_usuario, :id_cliente, :id_vendedor, :codigo_venta, :vendedor, :productos, :metodo_pago, :tasa_dia, :subtotal_usd, :subtotal_bs, :iva_usd, :iva_bs, :total_usd, :total_bs, :fecha)";
        
        $stmtVenta = $pdo->prepare($sqlVenta);

        $stmtVenta->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmtVenta->bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_INT);
        $stmtVenta->bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_INT);
        $stmtVenta->bindParam(":codigo_venta", $datos["codigo_venta"], PDO::PARAM_STR);
        $stmtVenta->bindParam(":vendedor", $datos["vendedor"], PDO::PARAM_STR);
        $stmtVenta->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
        $stmtVenta->bindParam(":metodo_pago", $datos["metodo_pago"], PDO::PARAM_STR);
        $stmtVenta->bindParam(":tasa_dia", $datos["tasa_dia"], PDO::PARAM_STR);
        $stmtVenta->bindParam(":subtotal_usd", $datos["subtotal_usd"], PDO::PARAM_STR);
        $stmtVenta->bindParam(":subtotal_bs", $datos["subtotal_bs"], PDO::PARAM_STR);
        $stmtVenta->bindParam(":iva_usd", $datos["iva_usd"], PDO::PARAM_STR);
        $stmtVenta->bindParam(":iva_bs", $datos["iva_bs"], PDO::PARAM_STR);
        $stmtVenta->bindParam(":total_usd", $datos["total_usd"], PDO::PARAM_STR);
        $stmtVenta->bindParam(":total_bs", $datos["total_bs"], PDO::PARAM_STR);
        $stmtVenta->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
        
        if (!$stmtVenta->execute()) {
            throw new Exception("Falló al guardar la venta principal: " . implode(" - ", $stmtVenta->errorInfo()));
        }

        // Obtenemos el ID de la venta recién creada
        $idVenta = $pdo->lastInsertId();

        // 2. --- INSERTAR EN LA TABLA 'detalle_ventas' ---
        $listaProductos = json_decode($datos["productos"], true);

        foreach ($listaProductos as $key => $value) {
            $sqlDetalle = "INSERT INTO $tablaDetalle (id_venta, id_producto, descripcion, cantidad, precio_unitario_usd, total_linea_usd) 
                           VALUES (:id_venta, :id_producto, :descripcion, :cantidad, :precio_unitario_usd, :total_linea_usd)";
            
            $stmtDetalle = $pdo->prepare($sqlDetalle);
            $stmtDetalle->bindParam(":id_venta", $idVenta, PDO::PARAM_INT);
            $stmtDetalle->bindParam(":id_producto", $value["id"], PDO::PARAM_INT);
            $stmtDetalle->bindParam(":descripcion", $value["descripcion"], PDO::PARAM_STR);
            $stmtDetalle->bindParam(":cantidad", $value["cantidad"], PDO::PARAM_INT);
            $stmtDetalle->bindParam(":precio_unitario_usd", $value["pvp_referencia"], PDO::PARAM_STR);
            $stmtDetalle->bindParam(":total_linea_usd", $value["total"], PDO::PARAM_STR);

            if (!$stmtDetalle->execute()) {
                 throw new Exception("Falló al guardar el detalle del producto: " . $value["descripcion"]);
            }
        }
        
        return true; // Si todo sale bien, retorna true
    }
}