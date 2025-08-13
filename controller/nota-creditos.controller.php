<?php

class ControllerNotaCreditos
{
    /*=============================================
    MOSTRAR NOTAS DE CREDITO
    =============================================*/
    static public function ctrMostrarNotaCredito($item, $valor)
    {
        $tabla = "notas_credito_ventas";
        $respuesta = ModelNotaCreditos::mdlMostrarNotaCredito($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
     CREAR NOTAS DE CREDITO
    =============================================*/
    static public function ctrCrearNotaCredito()
    {
        if (isset($_POST["nuevoCodigoNotaCredito"])) {
            date_default_timezone_set('America/Caracas');
            $tabla = "notas_credito_ventas";
            $fecha = date('Y-m-d h:i:s a');
            $datos = array(
                "id_usuario" => $_SESSION["id_usuario"],
                "id_cliente" => $_POST["idCliente"],
                "id_vendedor" => $_POST["idVendedor"],
                "codigo" => $_POST["nuevoCodigoNotaCredito"],
                "vendedor" => $_POST['vendedorNotaCredito'],
                "fecha_registro" => $_POST["fechaRegistro"],
                "fecha_vencimiento" => $_POST["fechaRegistro"],
                "cliente" => $_POST["listaCliente"],
                "productos" => $_POST["listarProductos"],
                "total_nota_credito" => $_POST["total__notaCredito"],
                "feregistro" => $fecha
            );

            $respuesta = ModelNotaCreditos::mdlIngresarNotaCredito($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡La Nota de Credito se ha Generadado Correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "nota-credito";
                            }
                        });
                </script>';
            }
        }
    }
}