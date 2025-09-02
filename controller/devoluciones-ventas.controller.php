<?php

class ControllerDevolucionesVentas
{
    /*=============================================
    MOSTRAR DEVOLUCION VENTA
    =============================================*/
    static public function ctrMostrarDevolucionVenta($item, $valor)
    {
        $tabla = "devoluciones_ventas";
        $respuesta = ModelDevolucionesVentas::mdlMostrarDevolucionVenta($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    CREAR DEVOLUCION VENTA
    =============================================*/
    static public function ctrCrearDevolucionVenta()
    {
        if (isset($_POST["idVenta"])) {
            if (isset($_POST["listaProductosDevolucion"]) && !empty($_POST["listaProductosDevolucion"])) {
                $tabla = "devoluciones_ventas";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d H:i:s');

                $datos = array("id_venta" => $_POST["idVenta"],
                               "id_cliente" => $_POST["idCliente"],
                               "fecha_devolucion" => $fecha,
                               "monto_total" => $_POST["montoTotalDevolucion"],
                               "productos" => $_POST["listaProductosDevolucion"]);

                $respuesta = ModelDevolucionesVentas::mdlIngresarDevolucionVenta($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>

                    Swal.fire({
                        icon: "success",
                        title: "La devolución ha sido guardada correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "devoluciones-ventas";
                        }
                    })

                    </script>';
                } else {
                    echo '<script>

                    Swal.fire({
                        icon: "error",
                        title: "Error al guardar la devolución",
                        text: "'.addslashes($respuesta).'",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    })

                    </script>';
                }
            } else {
                echo '<script>

                Swal.fire({
                    icon: "error",
                    title: "No se han agregado productos a la devolución",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                })

                </script>';
            }
        }
    }
}