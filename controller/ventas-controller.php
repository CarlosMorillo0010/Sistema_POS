<?php

class ControllerVentas
{

    /*=============================================
     MOSTRAR VENTAS
    =============================================*/
    static public function ctrMostrarVenta($item, $valor)
    {
        $tabla = "ventas";
        $respuesta = ModelVentas::mdlMostrarVenta($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
     CREAR VENTA
    =============================================*/
    static public function ctrCrearVenta()
    {
        if (isset($_POST["codigoVenta"])) {

            // Obtener la conexión PDO para manejar la transacción manualmente
            $pdo = Connection::connect();

            try {
                // 1. INICIAR LA TRANSACCIÓN
                $pdo->beginTransaction();

                /*=============================================
                 PROCESAR PRODUCTOS: ACTUALIZAR STOCK Y VENTAS
                =============================================*/
                $listaProductos = json_decode($_POST["listaProductosCaja"], true);

                if (empty($listaProductos)) {
                    throw new Exception("La lista de productos no puede estar vacía.");
                }

                $totalProductosComprados = array();
                foreach ($listaProductos as $key => $value) {
                    array_push($totalProductosComprados, $value["cantidad"]);
                    
                    $tablaProductos = "productos";
                    // Búsqueda por ID para máxima fiabilidad
                    $itemProducto = "id_producto";
                    $valorProducto = $value["id"];

                    // Usamos un método del modelo que usa la conexión existente
                    $traerProducto = ModelProducts::mdlMostrarProductos($tablaProductos, $itemProducto, $valorProducto, null);

                    if (!$traerProducto) {
                        throw new Exception("Producto no encontrado con ID: " . $valorProducto);
                    }

                    // A. Actualizar las ventas del producto
                    $item1aVenta = "productos_vendidos";
                    $valor1aVenta = $value["cantidad"] + $traerProducto["productos_vendidos"];
                    ModelProducts::mdlActualizarProductoConexion($pdo, $tablaProductos, $item1aVenta, $valor1aVenta, $itemProducto, $valorProducto);

                    // B. Actualizar (reducir) el stock del producto (LÓGICA CORREGIDA)
                    $item1bVenta = "stock";
                    $valor1bVenta = $traerProducto["stock"] - $value["cantidad"];

                    if ($valor1bVenta < 0) {
                         throw new Exception("Stock insuficiente para el producto: " . $traerProducto['descripcion']);
                    }
                    ModelProducts::mdlActualizarProductoConexion($pdo, $tablaProductos, $item1bVenta, $valor1bVenta, $itemProducto, $valorProducto);
                }

                /*=============================================
                 ACTUALIZAR COMPRAS DEL CLIENTE
                =============================================*/
                $tablaClientes = "clientes";
                $itemCliente = "id";
                $valorCliente = $_POST["seleccionarCliente"];
                $traerCliente = ModelClients::mdlMostrarClientes($tablaClientes, $itemCliente, $valorCliente);

                $item1Cliente = "compras";
                $valor1Cliente = array_sum($totalProductosComprados) + $traerCliente["compras"];
                ModelClients::mdlActualizarClienteConexion($pdo, $tablaClientes, $item1Cliente, $valor1Cliente, $valorCliente);

                /*=============================================
                 GUARDAR LA VENTA
                =============================================*/
                date_default_timezone_set('America/Caracas');
                $tablaVentas = "ventas";
                $fecha = date('Y-m-d H:i:s');
                
                $datos = array(
                    "id_usuario" => $_SESSION['id_usuario'],
                    "id_cliente" => $_POST["seleccionarCliente"],
                    "id_vendedor" => $_POST["idVendedor"],
                    "codigo_venta" => $_POST["codigoVenta"],
                    "vendedor" => $_POST["nombreVendedor"],
                    "productos" => $_POST["listaProductosCaja"],
                    "total" => $_POST["totalVenta"],
                    "metodo_pago" => $_POST["listaMetodoPago"],
                    "fecha" => $fecha
                );
                
                ModelVentas::mdlIngresarVentaConexion($pdo, $tablaVentas, $datos);

                // 2. SI TODO FUE BIEN, CONFIRMAR LA TRANSACCIÓN
                $pdo->commit();

                echo '<script>
                    swal({
                          type: "success",
                          title: "La venta ha sido guardada correctamente",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                          }).then(function(result){
                                    if (result.value) {
                                    window.location = "caja";
                                    }
                                })
                    </script>';

            } catch (Exception $e) {
                // 3. SI ALGO FALLÓ, REVERTIR LA TRANSACCIÓN
                $pdo->rollBack();

                // Mostrar un mensaje de error detallado para el desarrollador (en un log en producción)
                // y uno genérico para el usuario.
                error_log("Error al crear venta: " . $e->getMessage());

                echo '<script>
                    swal({
                          type: "error",
                          title: "¡Error al procesar la venta!",
                          text: "Ocurrió un problema y la operación fue cancelada para proteger los datos. Por favor, intente de nuevo.",
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
            if (isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])){
                $ventas = ModelVentas::mdlRangoFechasVentas($tabla, $_GET["fechaInicial"], $_GET["fechaFinal"]);
            }else{
                $item = null;
                $valor = null;
                $ventas = ModelVentas::mdlMostrarVenta($tabla, $item, $valor);
            }

            /*=============================================
             CREAMOS ARCHIVO DE EXCEL
            =============================================*/
            $Name = $_GET["reporte"].'.xls';

            header('Expires: 0');
            header('Cache-control: private');
            header("Content-type: application/vnd.ms-excel"); // Archivo de Excel
            header("Cache-Control: cache, must-revalidate");
            header('Content-Description: File Transfer');
            header('Last-Modified: '.date('D, d M Y H:i:s'));
            header("Pragma: public");
            header('Content-Disposition:; filename="'.$Name.'"');
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
                            <td style='border:1px solid #eee;'>".$item["codigo_venta"]."</td>
                            <td style='border:1px solid #eee;'>".$cliente["nombre"]."</td>
			 			    <td style='border:1px solid #eee;'>".$vendedor["nombre"]."</td>
			 			    <td style='border:1px solid #eee;'>");
                $productos = json_decode($item["productos"], true);
                foreach ($productos as $key => $valueProductos){
                    echo utf8_decode($valueProductos["cantidad"]."<br>");
                }
                echo utf8_decode("</td><td style='border:1px solid #eee;'>");
                foreach ($productos as $key => $valueProductos){
                    echo utf8_decode($valueProductos["descripcion"]."<br>");
                }

                echo utf8_decode("</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["impuesto"],2)."</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["precio_neto"],2)."</td>	
					<td style='border:1px solid #eee;'>$ ".number_format($item["total"],2)."</td>
					<td style='border:1px solid #eee;'>".$item["metodo_pago"]."</td>
					<td style='border:1px solid #eee;'>".substr($item["fecha"],0,10)."</td>		
		 			</tr>");

            endforeach;
            echo "</table>";
        }
    }
}