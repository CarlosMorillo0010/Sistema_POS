<?php

class ControllerNotasEntregas
{
    /*=============================================
    MOSTRAR NOTAS DE ENTREGA
    =============================================*/
    static public function ctrMostrarNotaEntrega($item, $valor)
    {
        $tabla = "notas_entrega_compras";
        $respuesta = ModelNotasEntregas::mdlMostrarNotaEntrega($tabla, $item, $valor);
        return $respuesta;
    }

    /*======================================
     CREAR NOTAS DE ENTREGA
    ======================================**/
    static public function ctrCrearNotaEntrega()
    {
        if (isset($_POST["nuevoNumeroRecepcion"])) {
            if (preg_match('/^[0-9]+$/', $_POST["nuevoNumeroRecepcion"]) &&
                preg_match('/^[0-9]+$/', $_POST["nuevoNumeroNotaEntrega"])
            )
            {
                $tabla = "notas_entrega_compras";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "id_proveedor" => $_POST["idProveedor"],
                    "numero_recepcion" => $_POST["nuevoNumeroRecepcion"],
                    "numero_nota" => $_POST["nuevoNumeroNotaEntrega"],
                    "fecha_nota" => $_POST["nuevaFechaNotaEntrega"],
                    "feregistro_nota" => $_POST["nuevaFechaRegistroNota"],
                    "proveedor" => $_POST["listProveedor"],
                    "listaProductos" => $_POST["listProductos"],
                    "total_compra" => $_POST["subTotalNotaEntregaCompra"],
                    "feregistro" => $fecha
                );
                $respuesta = ModelNotasEntregas::mdlIngresarNotaEntrega($tabla, $datos);

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
                                window.location = "notas-entrega";
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
                                window.location = "notas-entrega";
                            }
                        });
                </script>';
            }
        }
    }

    /*======================================
     BORRAR ORDEN DE COMPRA
    =======================================*/
    static public function ctrBorrarNotaEntrega()
    {
        if (isset($_GET["idNota"])) {
            $tabla = "notas_entrega_compras";
            $datos = $_GET["idNota"];

            $respuesta = ModelNotasEntregas::mdlBorrarNotaEntrega($tabla, $datos);

            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡La Nota de Entrega ha sido borrada correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "notas-entrega";
                            }
                        });
                </script>';
            }
        }
    }
}