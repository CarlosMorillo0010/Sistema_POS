<?php

class ControllerFacturaVentas{
    /*======================================
    MOSTRAR FACTURA DE VENTA
    ======================================**/
    static public function ctrMostrarFacturaVenta($item, $valor){
        $tabla = "factura_venta";
        $respuesta = ModelFacturaVentas::mdlMostrarFacturaVenta($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
     CREAR FACTURA DE VENTA
    =============================================*/
    static public function ctrCrearFacturaVenta()
    {
        if (isset($_POST["nuevoCodigo"])) {
            date_default_timezone_set('America/Caracas');
            $tabla = "factura_venta";
            $fecha = date('Y-m-d h:i:s a');
            $datos = array(
                "id_usuario" => $_SESSION["id_usuario"],
                "id_cliente" => $_POST["idCliente"],
                "id_vendedor" => $_POST["idVendedor"],
                "codigo" => $_POST["nuevoCodigo"],
                "vendedor" => $_POST['vendedorFactura'],
                "fecha_registro" => $_POST["fechaRegistro"],
                "cliente" => $_POST["listaCliente"],
                "productos" => $_POST["listaProductosFacturaVenta"],
                "total_factura" => $_POST["totalFacturaVenta"],
                "feregistro" => $fecha
            );

            $respuesta = ModelFacturaVentas::mdlIngresarFacturaVenta($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡La Factura se ha Generadado Correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "facturas";
                            }
                        });
                </script>';
            }
        }
    }

    /*======================================
     EDITAR FACTURA DE VENTA
    =======================================*/
    static public function ctrEditarFactura()
    {
        
    }

    /*======================================
     BORRAR FACTURA DE VENTA
    =======================================*/
    static public function ctrBorrarFactura()
    {
        if (isset($_GET["idFactura"])) {
            $tabla = "factura_venta";
            $datos = $_GET["idFactura"];

            $respuesta = ModelFacturaVentas::mdlBorrarFactura($tabla, $datos);

            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡La Factura ha sido borrado correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "facturas";
                            }
                        });
                </script>';
            }
        }
    }
}