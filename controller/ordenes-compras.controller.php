<?php

class ControllerOrdenesCompras
{
    /*=============================================
     MOSTRAR ORDEN DE COMPRA
    =============================================*/
    static public function ctrMostrarOrdenCompra($item, $valor)
    {
        $tabla = "orden_compra";
        $respuesta = ModelOrdenesCompras::mdlMostrarOrdenCompra($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
     MOSTRAR DETALLE DE ORDEN DE COMPRA
    =============================================*/
    static public function ctrMostrarOrdenCompraDetalle($item, $valor)
    {
        $tabla = "orden_compra_detalle";
        $respuesta = ModelOrdenesCompras::mdlMostrarOrdenCompraDetalle($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
     MOSTRAR MULTIPLES ORDENES POR FILTRO
    =============================================*/
    static public function ctrMostrarMultiplesOrdenes($item, $valor)
    {
        $tabla = "orden_compra";
        $respuesta = ModelOrdenesCompras::mdlMostrarMultiplesOrdenes($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
     CREAR UNA ORDEN DE COMPRA (NUEVA LÓGICA)
    =============================================*/
    static public function ctrCrearOrdenCompra()
    {
        if (isset($_POST["action"]) && isset($_POST["idOrden"]) == false) {

            if (empty($_POST["idProveedor"]) || empty($_POST["listaProductosOrden"])) {
                echo '<script>
                    swal({
                        type: "error",
                        title: "¡Error en el formulario!",
                        text: "El proveedor y al menos un producto son obligatorios.",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    });
                </script>';
                return;
            }

            // Determinar el estado
            $estado = ($_POST["action"] == "guardar_borrador") ? "Borrador" : "Enviada";

            // Generar un código único para la orden
            $item = null;
            $valor = null;
            $ordenes = self::ctrMostrarOrdenCompra($item, $valor);
            $codigo = (count($ordenes) > 0) ? end($ordenes)["codigo"] + 1 : 1;

            date_default_timezone_set('America/Caracas');
            $fecha = date('Y-m-d H:i:s');

            $datos = array(
                "id_usuario" => $_SESSION["id_usuario"],
                "id_proveedor" => $_POST["idProveedor"],
                "codigo" => $codigo,
                "fecha" => $fecha,
                "subtotal" => $_POST["subtotalOrden"],
                "impuestos" => $_POST["totalImpuestos"],
                "descuento" => 0,
                "costo_envio" => 0,
                "total" => $_POST["totalOrden"],
                "terminos_pago" => $_POST["terminosPago"],
                "notas" => $_POST["notasOrden"],
                "estado" => $estado,
                "feregistro" => $fecha,
                "productos" => $_POST["listaProductosOrden"]
            );

            $respuesta = ModelOrdenesCompras::mdlIngresarOrdenCompra("orden_compra", "orden_compra_detalle", $datos);

            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡La Orden de Compra se ha guardado correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                    }).then((result) => {
                        if(result.value){
                            window.location = "orden-compra";
                        }
                    });
                </script>';
            } else {
                 echo '<script>
                    swal({
                        type: "error",
                        title: "¡Error al guardar!",
                        text: "Ocurrió un error al guardar la orden. ".json_encode($respuesta),
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    });
                </script>';
            }
        }
    }

    /*======================================
     EDITAR ORDEN DE COMPRA
    =======================================*/
    static public function ctrEditarOrdenCompra()
    {
        if (isset($_POST["idOrden"])) {

            if (empty($_POST["idProveedor"]) || empty($_POST["listaProductosOrden"])) {
                echo '<script>
                    swal({
                        type: "error",
                        title: "¡Error en el formulario!",
                        text: "El proveedor y al menos un producto son obligatorios.",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    });
                </script>';
                return;
            }

            // Determinar el estado
            $estado = ($_POST["action"] == "guardar_borrador") ? "Borrador" : "Enviada";

            $datos = array(
                "id_orden_compra" => $_POST["idOrden"],
                "id_proveedor" => $_POST["idProveedor"],
                "subtotal" => $_POST["subtotalOrden"],
                "impuestos" => $_POST["totalImpuestos"],
                "descuento" => 0,
                "costo_envio" => 0,
                "total" => $_POST["totalOrden"],
                "terminos_pago" => $_POST["terminosPago"],
                "notas" => $_POST["notasOrden"],
                "estado" => $estado,
                "productos" => $_POST["listaProductosOrden"]
            );

            $respuesta = ModelOrdenesCompras::mdlEditarOrdenCompra("orden_compra", "orden_compra_detalle", $datos);

            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡La Orden de Compra se ha actualizado correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then((result) => {
                        if(result.value){
                            window.location = "orden-compra";
                        }
                    });
                </script>';
            } else {
                 echo '<script>
                    swal({
                        type: "error",
                        title: "¡Error al actualizar!",
                        text: "Ocurrió un error al actualizar la orden. ".json_encode($respuesta),
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    });
                </script>';
            }
        }
    }

    /*======================================
     CAMBIAR ESTADO DE ORDEN DE COMPRA
    =======================================*/
    static public function ctrCambiarEstadoOrden()
    {
        if (isset($_POST["idOrdenEstado"])) {
            $tabla = "orden_compra";
            $datos = array(
                "id_orden_compra" => $_POST["idOrdenEstado"],
                "estado" => $_POST["nuevoEstado"]
            );

            $respuesta = ModelOrdenesCompras::mdlActualizarOrden($tabla, "estado", $datos["estado"], "id_orden_compra", $datos["id_orden_compra"]);

            if ($respuesta == "ok") {
                echo "ok";
            }
        }
    }

    /*======================================
     BORRAR ORDEN DE COMPRA
    =======================================*/
    static public function ctrBorrarOrdenCompra()
    {
        if (isset($_GET["idOrden"])) {
            $tabla = "orden_compra";
            $idOrden = $_GET["idOrden"];

            // Verificar que la orden esté en estado "Borrador"
            $orden = self::ctrMostrarOrdenCompra("id_orden_compra", $idOrden);

            if ($orden["estado"] == "Borrador") {
                $respuesta = ModelOrdenesCompras::mdlBorrarOrdenCompra($tabla, $idOrden);
                if ($respuesta == "ok") {
                    echo '<script>
                        swal({
                            type: "success",
                            title: "¡La orden de compra ha sido borrada correctamente!",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                        }).then((result) => {
                            if(result.value){
                                window.location = "orden-compra";
                            }
                        });
                    </script>';
                }
            } else {
                echo '<script>
                    swal({
                        type: "error",
                        title: "¡Acción no permitida!",
                        text: "Solo se pueden eliminar órdenes en estado Borrador.",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then((result) => {
                        if(result.value){
                            window.location = "orden-compra";
                        }
                    });
                </script>';
            }
        }
    }
}
