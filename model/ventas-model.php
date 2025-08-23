<?php

require_once "connection.php";

class ModelVentas
{

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
    MOSTRAR VENTA DETALLADA
    =============================================*/
    static public function mdlMostrarVentaDetallada($tablaVentas, $tablaClientes, $item, $valor)
    {

        // Primero, obtenemos la venta y los datos del cliente con un JOIN
        $stmtVenta = Connection::connect()->prepare("
            SELECT v.*, c.nombre, c.documento, c.tipo_documento 
            FROM $tablaVentas v
            INNER JOIN $tablaClientes c ON v.id_cliente = c.id
            WHERE v.$item = :$item
        ");
        $stmtVenta->bindParam(":" . $item, $valor, PDO::PARAM_INT);
        $stmtVenta->execute();
        $venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

        // Si se encontró la venta, ahora buscamos sus productos
        if ($venta) {

            $productos = json_decode($venta["productos"], true);
            $venta["productos"] = $productos;
        }

        return $venta;
    }

    /*=============================================
     RANGO FECHAS (VERSIÓN ADAPTADA CON LEFT JOIN)
    =============================================*/
    static public function mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal)
    {
        // La consulta base ahora incluye el LEFT JOIN
        $consultaBase = "
            FROM $tabla v
            LEFT JOIN cobros_estado_factura c ON v.id_venta = c.id_venta
        ";

        // Las columnas que queremos seleccionar
        $columnas = "
            SELECT 
                v.*, 
                c.estado,
                c.saldo_pendiente_usd
        ";

        if ($fechaInicial == null) {

            // CASO 1: SIN FECHAS
            $stmt = Connection::connect()->prepare($columnas . $consultaBase . "ORDER BY v.id_venta DESC");

        } else if ($fechaInicial == $fechaFinal) {

            // CASO 2: UN SOLO DÍA
            $stmt = Connection::connect()->prepare($columnas . $consultaBase . "WHERE v.fecha LIKE :fecha ORDER BY v.id_venta DESC");
            $stmt->bindValue(":fecha", "%$fechaFinal%", PDO::PARAM_STR);

        } else {

            // CASO 3: RANGO DE FECHAS
            // Es más preciso usar >= y < para rangos de fecha y hora.
            $fechaFinalHastaFinDia = date('Y-m-d', strtotime($fechaFinal . ' +1 day'));

            $stmt = Connection::connect()->prepare($columnas . $consultaBase . "WHERE v.fecha >= :fechaInicial AND v.fecha < :fechaFinal ORDER BY v.id_venta DESC");

            $stmt->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
            $stmt->bindParam(":fechaFinal", $fechaFinalHastaFinDia, PDO::PARAM_STR);

        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Usar FETCH_ASSOC es buena práctica
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

    /*=============================================
    LIBRO DE VENTAS
    =============================================*/


    /*=============================================
    ACTUALIZAR ESTADO EN LA TABLA DE COBROS
    =============================================*/
    static public function mdlActualizarEstadoCobro($datos)
    {

        // Apuntamos directamente a la tabla 'Cobros_Estado_Factura'
        $stmt = Connection::connect()->prepare("
            UPDATE Cobros_Estado_Factura 
            SET estado = :estado 
            WHERE id_venta = :id_venta
        ");

        $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
        $stmt->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    /*=============================================
    CREAR EL ESTADO DE COBRO AL MARCAR COMO PENDIENTE --- LIBRO DE VENTAS
    =============================================*/
    static public function mdlCrearEstadoCobro($pdo, $tabla, $datos)
    {
        $stmt = $pdo->prepare("INSERT INTO $tabla (id_venta, fecha_vencimiento, estado, saldo_pendiente_usd) VALUES (:id_venta, :fecha_vencimiento, :estado, :saldo_pendiente_usd)");

        $stmt->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_INT);
        $stmt->bindParam(":fecha_vencimiento", $datos["fecha_vencimiento"], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
        $stmt->bindParam(":saldo_pendiente_usd", $datos["saldo_pendiente_usd"], PDO::PARAM_STR);

        if (!$stmt->execute()) {
            // Verificamos si el error es por clave duplicada (ya existe)
            if ($stmt->errorInfo()[1] == 1062) {
                throw new Exception("Esta venta ya tiene un estado de cobro asignado.");
            }
            throw new Exception("Falló al crear el registro de estado de cobro.");
        }
        return true;
    }

    /*=============================================
    OBTENER CUENTAS POR COBRAR (FACTURAS PENDIENTES) -- LIBRO DE VENTAS
    =============================================*/
    static public function mdlObtenerCuentasPorCobrar()
    {

        $stmt = Connection::connect()->prepare("
            SELECT 
                v.id_venta,
                v.codigo_venta,
                v.total_usd AS monto_total_usd, -- Renombrar para claridad
                c.estado,
                c.saldo_pendiente_usd,
                c.fecha_vencimiento,
                cli.nombre AS nombre_cliente,
                cli.documento AS documento_cliente,
                cli.tipo_documento AS tipo_doc_cliente
            FROM ventas v
            INNER JOIN Cobros_Estado_Factura c ON v.id_venta = c.id_venta
            INNER JOIN clientes cli ON v.id_cliente = cli.id
            WHERE c.estado = 'Pendiente' OR c.estado = 'Pagada Parcialmente'
            ORDER BY c.fecha_vencimiento ASC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*=============================================
    INGRESAR UN PAGO RECIBIDO CUENTAS POR COBRAR (FACTURAS PENDIENTES) -- LIBRO DE VENTAS
    =============================================*/
    static public function mdlIngresarPagoRecibido($pdo, $tabla, $datos)
    {

        // 1. AÑADIMOS la columna 'tasa_cambio_pago' a la consulta INSERT
        $sql = "INSERT INTO $tabla (
                    cliente_id, 
                    fecha_pago, 
                    monto_recibido, 
                    moneda_pago, 
                    tasa_cambio_pago, -- Campo nuevo
                    monto_equivalente_usd, 
                    metodo_pago, 
                    referencia
                ) VALUES (
                    :cliente_id, 
                    :fecha_pago, 
                    :monto_recibido, 
                    :moneda_pago, 
                    :tasa_cambio_pago, -- Placeholder nuevo
                    :monto_equivalente_usd, 
                    :metodo_pago, 
                    :referencia
                )";

        $stmt = $pdo->prepare($sql);

        // 2. AÑADIMOS el bindParam para el nuevo campo
        $stmt->bindParam(":cliente_id", $datos["cliente_id"], PDO::PARAM_INT);
        $stmt->bindParam(":fecha_pago", $datos["fecha_pago"], PDO::PARAM_STR);
        $stmt->bindParam(":monto_recibido", $datos["monto_recibido"], PDO::PARAM_STR);
        $stmt->bindParam(":moneda_pago", $datos["moneda_pago"], PDO::PARAM_STR);
        $stmt->bindParam(":tasa_cambio_pago", $datos["tasa_cambio_pago"], PDO::PARAM_STR); // Puede ser NULL, STR es seguro
        $stmt->bindParam(":monto_equivalente_usd", $datos["monto_equivalente_usd"], PDO::PARAM_STR);
        $stmt->bindParam(":metodo_pago", $datos["metodo_pago"], PDO::PARAM_STR);
        $stmt->bindParam(":referencia", $datos["referencia"], PDO::PARAM_STR);

        // El resto del método sigue igual
        if ($stmt->execute()) {
            return $pdo->lastInsertId();
        } else {
            throw new Exception("Error al ejecutar la inserción del pago: " . implode(" - ", $stmt->errorInfo()));
        }
    }

    /*=============================================
  ACTUALIZAR SALDO PENDIENTE Y ESTADO DE UN COBRO
  =============================================*/
    static public function mdlActualizarSaldoYEstadoCobro($pdo, $tabla, $datos)
    {

        // La consulta SQL para actualizar el registro en la tabla de cobros
        $sql = "UPDATE $tabla 
                SET saldo_pendiente_usd = :saldo_pendiente_usd, estado = :estado 
                WHERE id_venta = :id_venta";

        $stmt = $pdo->prepare($sql);

        // Vinculamos los valores del array $datos a los placeholders
        $stmt->bindParam(":saldo_pendiente_usd", $datos["saldo_pendiente_usd"], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
        $stmt->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_INT);

        // Ejecutamos la consulta
        if ($stmt->execute()) {
            // Si tiene éxito, devolvemos true
            return true;
        } else {
            // Si falla, lanzamos una excepción para que la transacción haga rollback
            throw new Exception("Error al actualizar el saldo y estado de la factura: " . implode(" - ", $stmt->errorInfo()));
        }
    }

    /*=============================================
    APLICAR UN PAGO A UNA FACTURA (CREAR REGISTRO EN TABLA DE CRUCE)
    =============================================*/
    static public function mdlAplicarPagoFactura($pdo, $tabla, $datos)
    {

        // La consulta SQL para insertar en la tabla de cruce
        $sql = "INSERT INTO $tabla (pago_id, factura_id, monto_aplicado_usd) 
                VALUES (:pago_id, :factura_id, :monto_aplicado_usd)";

        $stmt = $pdo->prepare($sql);

        // Vinculamos los valores del array $datos a los placeholders de la consulta
        $stmt->bindParam(":pago_id", $datos["pago_id"], PDO::PARAM_INT);
        $stmt->bindParam(":factura_id", $datos["factura_id"], PDO::PARAM_INT);
        $stmt->bindParam(":monto_aplicado_usd", $datos["monto_aplicado_usd"], PDO::PARAM_STR);

        // Ejecutamos la consulta
        if ($stmt->execute()) {
            // Si tiene éxito, devolvemos true
            return true;
        } else {
            // Si falla, lanzamos una excepción para que la transacción principal haga rollback
            throw new Exception("Error al aplicar el pago a la factura: " . implode(" - ", $stmt->errorInfo()));
        }
    }

    // Método para obtener el estado actual de un cobro
    static public function mdlObtenerEstadoCobro($tabla, $item, $valor)
    {
        $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
        $stmt->bindParam(":" . $item, $valor, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }


}