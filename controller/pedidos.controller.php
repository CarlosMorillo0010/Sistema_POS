<?php

class ControllerPedidos{
    /*=============================================
     MOSTRAR PEDIDO
    =============================================*/
    static public function ctrMostrarPedidos($item, $valor){
        $tabla = "pedidos";
        $respuesta = ModelPedidos::mdlMostrarPedidos($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
     CREAR PEDIDO
    =============================================*/
    static public function ctrCrearPedido()
    {
        if (isset($_POST["nuevoCodigoPedido"])) 
        {
            date_default_timezone_set('America/Caracas');
            $tabla = "pedidos";
            $fecha = date('Y-m-d h:i:s a');
            $datos = array(
                "id_usuario" => $_SESSION["id_usuario"],
                "id_cliente" => $_POST["idCliente"],
                "id_vendedor" => $_POST["idVendedor"],
                "codigo" => $_POST["codigo"],
                "vendedor" => $_POST['vendedorPedido'],
                "fecha_registro" => $_POST["fechaRegistro"],
                "cliente" => $_POST["listaCliente"],
                "productos" => $_POST["listaProductosPedidos"],
                "total_pedido" => $_POST["totalPedido"],
                "feregistro" => $fecha
            );

            $respuesta = ModelPedidos::mdlIngresarPedidos($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡El Pedido se ha Generadado Correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "pedidos";
                            }
                        });
                </script>';
            }
        }
    }

    /*======================================
     EDITAR PEDIDO
    =======================================*/
    static public function ctrEditarPedido()
    {
        
    }

    /*======================================
     BORRAR PEDIDO
    =======================================*/
    static public function ctrBorrarPedido()
    {
        if (isset($_GET["idPedido"])) {
            $tabla = "pedidos";
            $datos = $_GET["idPedido"];

            $respuesta = ModelPedidos::mdlBorrarPedido($tabla, $datos);

            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡El Pedido ha sido borrado correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "pedidos";
                            }
                        });
                </script>';
            }
        }
    }
}