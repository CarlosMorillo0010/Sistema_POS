<?php

class ControllerNotasEntregaVentas
{
    /*=============================================
    MOSTRAR NOTAS DE ENTREGA
    =============================================*/
    static public function ctrMostrarNotaEntregaVenta($item, $valor)
    {
        $tabla = "notas_entrega_ventas";
        $respuesta = ModelNotasEntregaVentas::mdlMostrarNotaEntregaVenta($tabla, $item, $valor);
        return $respuesta;
    }

    /*======================================
     CREAR NOTAS DE ENTREGA
    ======================================**/
    static public function ctrCrearNotaEntregaVenta()
    {
        if (isset($_POST["nuevoNumeroRecepcionVenta"])) {
            if (preg_match('/^[0-9]+$/', $_POST["nuevoNumeroRecepcionVenta"]) &&
                preg_match('/^[0-9]+$/', $_POST["nuevoNumeroNotaEntrega"])
            )
            {
                $tabla = "notas_entrega_ventas";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "id_cliente" => $_POST["idCliente"],
                    "id_vendedor" => $_POST["idVendedor"],
                    "numero_recepcion" => $_POST["nuevoNumeroRecepcionVenta"],
                    "numero_nota" => $_POST["nuevoNumeroNotaEntrega"],
                    "fecha_nota" => $_POST["nuevaFechaNota"],
                    "feregistro_nota" => $_POST["nuevaFechaRegistro"],
                    "cliente" => $_POST["listaCliente"],
                    "listarProductos" => $_POST["listarProductos"],
                    "total_nota_entrega" => $_POST["total__notaEntregaVenta"],
                    "feregistro" => $fecha
                );

                $respuesta = ModelNotasEntregaVentas::mdlIngresarNotaEntregaVenta($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>
                    swal({
                        type: "success",
                        title: "¡La Nota de Entrega ha sido guardada correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "notas-entrega-venta";
                            }
                        });
                    </script>';
                }
            } else {
                echo '<script>
                    swal({
                        type: "error",
                        title: "¡La Nota de Entrega no puede ir con los campos vacios o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "notas-entrega-venta";
                            }
                        });
                </script>';
            }
        }
    }

    /*======================================
     EDITAR NOTA ENTREGA VENTA
    =======================================*/
    static public function ctrEditarNotaEntregaVenta()
    {
        
    }

    /*======================================
     BORRAR NOTA ENTREGA VENTA
    =======================================*/
    static public function ctrBorrarNotaEntregaVenta()
    {
        if (isset($_GET["idNota"])) {
            $tabla = "notas_entrega_ventas";
            $datos = $_GET["idNota"];

            $respuesta = ModelNotasEntregaVentas::mdlBorrarNotaEntregaVenta($tabla, $datos);

            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡La nota de entrega ha sido borrada correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "notas-entrega-venta";
                            }
                        });
                </script>';
            }
        }
    }
}