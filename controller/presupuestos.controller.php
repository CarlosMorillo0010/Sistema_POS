<?php

class ControllerPresupuestos
{
    /*=============================================
    MOSTRAR PRESUPUESTO
    =============================================*/
    static public function ctrMostrarPresupuesto($item, $valor)
    {
        $tabla = "presupuesto";
        $respuesta = ModelPresupuestos::mdlMostrarPresupuesto($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
     CREAR PRESUPUESTO
    =============================================*/
    static public function ctrCrearPresupuesto()
    {
        if (isset($_POST["nuevoCodigoPresupuesto"])) {
            date_default_timezone_set('America/Caracas');
            $tabla = "presupuesto";
            $fecha = date('Y-m-d h:i:s a');
            $datos = array(
                "id_usuario" => $_SESSION["id_usuario"],
                "id_cliente" => $_POST["idCliente"],
                "id_vendedor" => $_POST["idVendedor"],
                "codigo" => $_POST["nuevoCodigoPresupuesto"],
                "vendedor" => $_POST['vendedorPresupuesto'],
                "fecha_registro" => $_POST["fechaRegistro"],
                "fecha_vencimiento" => $_POST["nuevaFechaVencimiento"],
                "cliente" => $_POST["listaCliente"],
                "productos" => $_POST["listarProductos"],
                "total_presupuesto" => $_POST["total__Presupuesto"],
                "feregistro" => $fecha
            );

            $respuesta = ModelPresupuestos::mdlIngresarPresupuesto($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡El presupuesto se ha Generadado Correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "presupuesto";
                            }
                        });
                </script>';
            }
        }
    }

    /*======================================
     EDITAR PRESUPUESTO
    =======================================*/
    static public function ctrEditarPresupuesto()
    {
        
    }

    /*======================================
     BORRAR PRESUPUESTO
    =======================================*/
    static public function ctrBorrarPresupuesto()
    {
        if (isset($_GET["idPresupuesto"])) {
            $tabla = "presupuesto";
            $datos = $_GET["idPresupuesto"];

            $respuesta = ModelPresupuestos::mdlBorrarPresupuesto($tabla, $datos);

            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡El presupuesto ha sido borrado correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "presupuesto";
                            }
                        });
                </script>';
            }
        }
    }
}