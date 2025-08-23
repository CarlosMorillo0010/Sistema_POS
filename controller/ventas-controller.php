<?php

class ControllerVentas
{

    /*=============================================
     MOSTRAR VENTAS
    =============================================*/
    static public function ctrMostrarVenta($item, $valor)
    {
        $tabla = "ventas";
        return ModelVentas::mdlMostrarVenta($tabla, $item, $valor);
    }

    /*=============================================
     CREAR VENTA (VERSIÓN ROBUSTA Y ADAPTADA)
    =============================================*/
    static public function ctrCrearVenta()
    {
        // Se activa cuando el formulario envía los datos necesarios
        if (isset($_POST["codigoVenta"], $_POST["listaProductosCaja"])) {

            // Obtener la conexión PDO para manejar la transacción
            $pdo = Connection::connect();

            try {
                // 1. --- INICIAR LA TRANSACCIÓN ---
                $pdo->beginTransaction();

                // 2. --- VALIDACIÓN DE DATOS ESENCIALES ---
                if (empty($_POST["seleccionarCliente"])) {
                    throw new Exception("Debe seleccionar un cliente.");
                }

                $listaProductos = json_decode($_POST["listaProductosCaja"], true);
                if (empty($listaProductos)) {
                    throw new Exception("La lista de productos no puede estar vacía.");
                }

                // 3. --- VALIDACIÓN DE TOTALES Y STOCK EN EL SERVIDOR (CRÍTICO) ---
                $subtotalCalculadoServidor = 0;
                foreach ($listaProductos as $key => $value) {
                    // Consultamos el producto en la BD para obtener el precio y stock reales
                    $productoDB = ModelProducts::mdlMostrarProductos("productos", "id_producto", $value["id"], "id_producto");

                    if (!$productoDB) {
                        throw new Exception("Producto no encontrado con ID: " . $value["id"]);
                    }

                    // Verificamos si hay stock suficiente
                    if ($productoDB["stock"] < $value["cantidad"]) {
                        throw new Exception('Stock insuficiente para "' . $productoDB["descripcion"] . '". Disponibles: ' . $productoDB["stock"]);
                    }

                    // Acumulamos el subtotal usando el precio de la BD para evitar manipulaciones
                    $subtotalCalculadoServidor += $value["cantidad"] * $productoDB["pvp_referencia"];
                }

                // Comparamos el subtotal enviado por el cliente con el calculado en el servidor
                $subtotalCliente = (float) ($_POST["subtotalUsd"] ?? 0);
                if (abs($subtotalCalculadoServidor - $subtotalCliente) > 0.01) { // Pequeño margen de tolerancia
                    throw new Exception("Inconsistencia en los totales. La operación fue cancelada por seguridad.");
                }

                // 4. --- PROCESAR PRODUCTOS: ACTUALIZAR STOCK Y VENTAS ---
                foreach ($listaProductos as $key => $value) {
                    $tablaProductos = "productos";
                    $itemProducto = "id_producto";
                    $valorProducto = $value["id"];

                    // Re-obtenemos el producto para usar sus datos actualizados
                    $traerProducto = ModelProducts::mdlMostrarProductos($tablaProductos, $itemProducto, $valorProducto, null);

                    // A. Actualizar las ventas del producto
                    $item1aVenta = "productos_vendidos";
                    $valor1aVenta = $value["cantidad"] + $traerProducto["productos_vendidos"];
                    ModelProducts::mdlActualizarProductoConexion($pdo, $tablaProductos, $item1aVenta, $valor1aVenta, $itemProducto, $valorProducto);

                    // B. Actualizar (reducir) el stock
                    $item1bVenta = "stock";
                    $valor1bVenta = $traerProducto["stock"] - $value["cantidad"];
                    ModelProducts::mdlActualizarProductoConexion($pdo, $tablaProductos, $item1bVenta, $valor1bVenta, $itemProducto, $valorProducto);
                }

                // 5. --- ACTUALIZAR COMPRAS DEL CLIENTE ---
                $tablaClientes = "clientes";
                $itemCliente = "id";
                $valorCliente = $_POST["seleccionarCliente"];
                $traerCliente = ModelClients::mdlMostrarClientes($tablaClientes, $itemCliente, $valorCliente);

                $cantidadTotalItems = array_reduce($listaProductos, function ($carry, $item) {
                    return $carry + $item['cantidad'];
                }, 0);

                $item1Cliente = "compras";
                $valor1Cliente = $cantidadTotalItems + $traerCliente["compras"];
                ModelClients::mdlActualizarClienteConexion($pdo, $tablaClientes, $item1Cliente, $valor1Cliente, $valorCliente);

                // 6. --- GUARDAR LA VENTA Y SU DETALLE ---
                date_default_timezone_set('America/Caracas');

                $datosVenta = array(
                    "id_usuario" => $_SESSION['id_usuario'],
                    "id_cliente" => $_POST["seleccionarCliente"],
                    "id_vendedor" => $_POST["idVendedor"],
                    "codigo_venta" => $_POST["codigoVenta"],
                    "vendedor" => $_POST["nombreVendedor"],
                    "productos" => $_POST["listaProductosCaja"], // El JSON original por compatibilidad
                    "metodo_pago" => $_POST["listaMetodoPago"],
                    "tasa_dia" => $_POST["tasaDelDia"],
                    "subtotal_usd" => $_POST["subtotalUsd"],
                    "subtotal_bs" => $_POST["subtotalBs"],
                    "iva_usd" => $_POST["ivaUsd"],
                    "iva_bs" => $_POST["ivaBs"],
                    "total_usd" => $_POST["totalUsd"],
                    "total_bs" => $_POST["totalBs"],
                    "fecha" => date('Y-m-d H:i:s')
                );

                ModelVentas::mdlIngresarVentaConexion($pdo, "ventas", "detalle_ventas", $datosVenta);

                // 7. --- SI TODO FUE BIEN, CONFIRMAR LA TRANSACCIÓN ---
                $pdo->commit();

                echo '<script>
                    Swal.fire({
                          icon: "success",
                          title: "La venta ha sido guardada correctamente",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "caja";
                        }
                    });
                </script>';

            } catch (Exception $e) {
                // 8. --- SI ALGO FALLÓ, REVERTIR LA TRANSACCIÓN ---
                $pdo->rollBack();
                error_log("Error al crear venta: " . $e->getMessage());

                echo '<script>
                    Swal.fire({
                          icon: "error",
                          title: "¡Error al procesar la venta!",
                          text: "' . addslashes($e->getMessage()) . '",
                          showConfirmButton: true,
                          confirmButtonText: "Entendido"
                    });
                </script>';
            }
        }
    }

    /*=============================================
     RANGO FECHAS
    =============================================*/
    static public function ctrRangoFechasVentas($fechaInicial, $fechaFinal)
    {
        $tabla = "ventas";
        $respuesta = ModelVentas::mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal);
        return $respuesta;

    }

    /*=============================================
     DESCARGAR EXCEL
    =============================================*/
    public function ctrDescargarReporte()
    {
        if (isset($_GET["reporte"])) {
            $tabla = "ventas";
            if (isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])) {
                $ventas = ModelVentas::mdlRangoFechasVentas($tabla, $_GET["fechaInicial"], $_GET["fechaFinal"]);
            } else {
                $item = null;
                $valor = null;
                $ventas = ModelVentas::mdlMostrarVenta($tabla, $item, $valor);
            }

            /*=============================================
             CREAMOS ARCHIVO DE EXCEL
            =============================================*/
            $Name = $_GET["reporte"] . '.xls';

            header('Expires: 0');
            header('Cache-control: private');
            header("Content-type: application/vnd.ms-excel"); // Archivo de Excel
            header("Cache-Control: cache, must-revalidate");
            header('Content-Description: File Transfer');
            header('Last-Modified: ' . date('D, d M Y H:i:s'));
            header("Pragma: public");
            header('Content-Disposition:; filename="' . $Name . '"');
            header("Content-Transfer-Encoding: binary");

            echo utf8_decode("
                    <table border='0'> 
                        <tr> 
                            <td style='font-weight:bold; border:1px solid #eee;'>CÓDIGO</td> 
                            <td style='font-weight:bold; border:1px solid #eee;'>CLIENTE</td>
                            <td style='font-weight:bold; border:1px solid #eee;'>VENDEDOR</td>
                            <td style='font-weight:bold; border:1px solid #eee;'>CANTIDAD</td>
                            <td style='font-weight:bold; border:1px solid #eee;'>PRODUCTOS</td>
                            <td style='font-weight:bold; border:1px solid #eee;'>IMPUESTO</td>
                            <td style='font-weight:bold; border:1px solid #eee;'>PRECIO NETO</td>		
                            <td style='font-weight:bold; border:1px solid #eee;'>TOTAL</td>		
                            <td style='font-weight:bold; border:1px solid #eee;'>METODO DE PAGO</td	
                            <td style='font-weight:bold; border:1px solid #eee;'>FECHA</td>		
                        </tr>");
            foreach ($ventas as $row => $item):
                $cliente = ControllerClients::ctrMostrarClientes("id", $item["id_cliente"]);
                $vendedor = ControllerUsers::ctrMostrarUsuario("id_usuario", $item["id_vendedor"]);

                echo utf8_decode("
                        <tr>
                            <td style='border:1px solid #eee;'>" . $item["codigo_venta"] . "</td>
                            <td style='border:1px solid #eee;'>" . $cliente["nombre"] . "</td>
			 			    <td style='border:1px solid #eee;'>" . $vendedor["nombre"] . "</td>
			 			    <td style='border:1px solid #eee;'>");
                $productos = json_decode($item["productos"], true);
                foreach ($productos as $key => $valueProductos) {
                    echo utf8_decode($valueProductos["cantidad"] . "<br>");
                }
                echo utf8_decode("</td><td style='border:1px solid #eee;'>");
                foreach ($productos as $key => $valueProductos) {
                    echo utf8_decode($valueProductos["descripcion"] . "<br>");
                }

                echo utf8_decode("</td>
					<td style='border:1px solid #eee;'>$ " . number_format($item["impuesto"], 2) . "</td>
					<td style='border:1px solid #eee;'>$ " . number_format($item["precio_neto"], 2) . "</td>	
					<td style='border:1px solid #eee;'>$ " . number_format($item["total"], 2) . "</td>
					<td style='border:1px solid #eee;'>" . $item["metodo_pago"] . "</td>
					<td style='border:1px solid #eee;'>" . substr($item["fecha"], 0, 10) . "</td>		
		 			</tr>");

            endforeach;
            echo "</table>";
        }
    }



    /*=============================================
    LIBRO DE VENTAS
    =============================================*/

    /*=============================================
    MOSTRAR VENTA DETALLADA
    =============================================*/
    public static function ctrMostrarVentaDetallada($item, $valor)
    {
        $tablaVentas = "ventas";
        $tablaClientes = "clientes";

        // Llamamos a un nuevo método en el modelo
        $respuesta = ModelVentas::mdlMostrarVentaDetallada($tablaVentas, $tablaClientes, $item, $valor);

        return $respuesta;
    }

    /*=============================================
    MARCAR UNA VENTA COMO PENDIENTE (CREAR REGISTRO DE COBRO)
    =============================================*/
    public static function ctrMarcarVentaComoPendiente()
    {

        // Verificamos que recibimos el ID de la venta desde el AJAX
        if (isset($_POST["idVenta"])) {

            $idVenta = $_POST["idVenta"];

            $pdo = Connection::connect();

            try {
                $pdo->beginTransaction();
                $venta = ModelVentas::mdlMostrarVenta("ventas", "id_venta", $idVenta);

                if (!$venta) {
                    throw new Exception("La venta con ID " . $idVenta . " no fue encontrada.");
                }

                $cliente = ModelClients::mdlMostrarClientes("clientes", "id", $venta["id_cliente"]);

                if (!$cliente) {

                    $diasCredito = 15;
                } else {

                    $diasCredito = $cliente["dias_credito"] ?? 0;
                }

                // 3. CALCULAR LA FECHA DE VENCIMIENTO
                $fechaVenta = new DateTime($venta["fecha"]);
                $fechaVenta->modify('+' . $diasCredito . ' days');
                $fechaVencimiento = $fechaVenta->format('Y-m-d');

                $datosCobro = array(
                    "id_venta" => $idVenta,
                    "fecha_vencimiento" => $fechaVencimiento,
                    "estado" => "Pendiente", // El estado inicial es siempre "Pendiente"
                    "saldo_pendiente_usd" => $venta["total_usd"] // El saldo inicial es el total de la factura
                );

                ModelVentas::mdlCrearEstadoCobro($pdo, "cobros_estado_factura", $datosCobro);

                $pdo->commit();

                echo "ok";

            } catch (Exception $e) {

                $pdo->rollBack();

                error_log("Error al marcar venta como pendiente: " . $e->getMessage());

                echo $e->getMessage();
            }

        } else {
            echo "error_no_id";
        }
    }

    /*=============================================
    MOSTRAR CUENTAS POR COBRAR -- LIBRO DE VENTAS
    =============================================*/
    public static function ctrObtenerCuentasPorCobrar()
    {
        $respuesta = ModelVentas::mdlObtenerCuentasPorCobrar();
        return $respuesta;
    }

    /*=============================================
    REGISTRAR PAGO DE CLIENTE
    =============================================*/
    // Este método se encarga de registrar un pago de cliente, actualizando el estado de la venta y el saldo pendiente.
    public static function ctrRegistrarPagoCliente()
    {
        if (isset($_POST["pagoIdVenta"])) {

            $pdo = Connection::connect();
            try {
                $pdo->beginTransaction();

                // OBTENER DATOS DE LA VENTA ORIGINAL
                $venta = ModelVentas::mdlMostrarVenta("ventas", "id_venta", $_POST["pagoIdVenta"]);
                if (!$venta) {
                    throw new Exception("Venta no encontrada.");
                }

                // --- Calcular monto equivalente y capturar datos del pago ---
                $montoRecibido = (float) $_POST["pagoMonto"];
                $monedaPago = $_POST["pagoMoneda"];
                $montoEquivalenteUsd = 0;
                $tasaCambio = null;

                if ($monedaPago == "USD") {
                    $montoEquivalenteUsd = $montoRecibido;
                } elseif ($monedaPago == "VES") {
                    if (empty($_POST["pagoTasaCambio"]) || (float) $_POST["pagoTasaCambio"] <= 0) {
                        throw new Exception("Debe proporcionar una tasa de cambio válida para pagos en Bolívares.");
                    }
                    $tasaCambio = (float) $_POST["pagoTasaCambio"];
                    $montoEquivalenteUsd = $montoRecibido / $tasaCambio;
                }

                $datosPago = array(
                    "cliente_id" => $venta["id_cliente"],
                    "fecha_pago" => $_POST["pagoFecha"],
                    "monto_recibido" => $montoRecibido,
                    "moneda_pago" => $monedaPago,
                    "tasa_cambio_pago" => $tasaCambio,
                    "monto_equivalente_usd" => $montoEquivalenteUsd,
                    "metodo_pago" => $_POST["pagoMetodo"],
                    "referencia" => $_POST["pagoReferencia"]
                );
                $idPago = ModelVentas::mdlIngresarPagoRecibido($pdo, "pagos_recibidos", $datosPago);
                if (!$idPago) {
                    throw new Exception("No se pudo registrar el pago.");
                }

                // 4. APLICAR EL PAGO A LA FACTURA
                $datosAplicacion = array(
                    "pago_id" => $idPago,
                    "factura_id" => $_POST["pagoIdVenta"],
                    "monto_aplicado_usd" => $montoEquivalenteUsd // Aplicamos el valor en USD
                );
                ModelVentas::mdlAplicarPagoFactura($pdo, "Aplicacion_Pagos_Facturas", $datosAplicacion);

                // 5. ACTUALIZAR EL SALDO Y ESTADO
                $estadoCobro = ModelVentas::mdlObtenerEstadoCobro("cobros_estado_factura", "id_venta", $_POST["pagoIdVenta"]);
                $nuevoSaldo = $estadoCobro["saldo_pendiente_usd"] - $montoEquivalenteUsd;

                $nuevoSaldo = round($nuevoSaldo, 2);
                $nuevoEstado = ($nuevoSaldo <= 0.01) ? "Pagada" : "Pagada Parcialmente"; // Añadimos un pequeño margen por redondeo

                $datosActualizacion = array(
                    "id_venta" => $_POST["pagoIdVenta"],
                    "saldo_pendiente_usd" => $nuevoSaldo,
                    "estado" => $nuevoEstado
                );
                ModelVentas::mdlActualizarSaldoYEstadoCobro($pdo, "cobros_estado_factura", $datosActualizacion);

                $pdo->commit();

                // Éxito
                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "¡Pago registrado correctamente!",
                    }).then(() => {
                        window.location = "cuentas-cobrar"; 
                    });
                </script>';

            } catch (Exception $e) {
                $pdo->rollBack();
                // Error
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error al registrar el pago",
                        text: "' . addslashes($e->getMessage()) . '",
                    });
                </script>';
            }
        }
    }

    /*=============================================
    ACTUALIZAR ESTADO DE UN COBRO EXISTENTE
    =============================================*/
    public static function ctrActualizarEstadoVenta()
    {

        // Verificamos los datos que envía el JS
        if (isset($_POST["idVenta"]) && isset($_POST["nuevoEstado"])) {

            $datos = array(
                "id_venta" => $_POST["idVenta"],
                "estado" => $_POST["nuevoEstado"]
            );

            $respuesta = ModelVentas::mdlActualizarEstadoCobro($datos);

            echo $respuesta; // Devuelve 'ok' o 'error'
        } else {
            echo "error_faltan_datos"; // Mensaje de error si faltan datos
        }
    }

}