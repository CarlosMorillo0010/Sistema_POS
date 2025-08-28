<?php

class ControllerCompras
{

    /*=============================================
    CREAR COMPRA
    =============================================*/

    static public function ctrCrearCompra()
    {

        if (isset($_POST["seleccionarOrdenCompra"])) {

            if (isset($_POST["listaProductosCompra"]) && !empty($_POST["listaProductosCompra"])) {

                $tabla = "compras";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s a');

                $datos = array("id_orden_compra" => $_POST["seleccionarOrdenCompra"],
                               "id_proveedor" => $_POST["idProveedor"],
                               "fecha_compra" => $fecha,
                               "monto_total" => $_POST["montoTotalCompra"],
                               "productos" => $_POST["listaProductosCompra"]);

                $respuesta = ModelCompras::mdlIngresarCompra($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>

                    Swal.fire({
                        icon: "success",
                        title: "La compra ha sido guardada correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "compra";
                        }
                    })

                    </script>';
                } else {
                    echo '<script>

                    Swal.fire({
                        icon: "error",
                        title: "Error al guardar la compra: ". $respuesta,
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    })

                    </script>';
                }

            } else {
                echo '<script>

                Swal.fire({
                    icon: "error",
                    title: "No se han agregado productos a la compra",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                })

                </script>';
            }
        }

    }

    /*=============================================
    MOSTRAR COMPRAS
    =============================================*/
    static public function ctrMostrarCompras($item, $valor)
    {
        $tabla = "compras";
        $respuesta = ModelCompras::mdlMostrarCompras($tabla, $item, $valor);
        return $respuesta;
    }

}
