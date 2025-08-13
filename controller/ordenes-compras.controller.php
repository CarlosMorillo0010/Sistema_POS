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
     CREAR UNA ORDEN DE COMPRA
    =============================================*/
    static public function ctrCrearOrdenCompra()
    {
        if (isset($_POST["nuevoCodigo__OrdenCompra"])) {
            date_default_timezone_set('America/Caracas');
            $tabla = "orden_compra";
            $fecha = date('Y-m-d h:i:s a');
            $datos = array(
                "id_usuario" => $_SESSION["id_usuario"],
                "id_proveedor" => $_POST["idProveedor"],
                "codigo" => $_POST["nuevoCodigo__OrdenCompra"],
                "fecha_emision" => $fecha,
                "proveedor" => $_POST["listaProveedor"],
                "productos" => $_POST["listarProductos_OrdenCompra"],
                "feregistro" => $fecha
            );

            $respuesta = ModelOrdenesCompras::mdlIngresarOrdenCompra($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡La Orden de Compra se ha Generadado Correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "orden-compra";
                            }
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
        if (isset($_POST["editarCodigo__OrdenCompra"])) {
            date_default_timezone_set('America/Caracas');
            $tabla = "orden_compra";
            $item = "codigo";
            $valor = $_POST["editarCodigo__OrdenCompra"];
            $traerOrdenCompra = ModelOrdenesCompras::mdlMostrarOrdenCompra($tabla, $item, $valor);
            /* var_dump($traerOrdenCompra); */

            /*======================================
                VALIDAR SI VIENEN ID PROVEEDOR EDITADOS
            =======================================*/
            if($_POST["editar_idProveedor"] == ""){
                $idProveedor = $traerOrdenCompra["id_proveedor"];
            }else{
                $idProveedor = $_POST["editar_idProveedor"];
            }

            /*======================================
                VALIDAR SI VIENEN PROVEEDORES EDITADOS
            =======================================*/
            if($_POST["editarListaProveedor"] == ""){
                $listaProveedor = $traerOrdenCompra["proveedor"];
            }else{
                $listaProveedor = $_POST["editarListaProveedor"];
            }

            /*======================================
                VALIDAR SI VIENEN PRODUCTOS EDITADOS
            =======================================*/
            if($_POST["editarListaProductos"] == ""){
                $listaProductos = $traerOrdenCompra["productos"];
            }else{
                $listaProductos = $_POST["editarListaProductos"];
            }

            $datos = array(
                "id_usuario" => $_SESSION["id_usuario"],
                "id_proveedor" => $idProveedor,
                "codigo" => $_POST["editarCodigo__OrdenCompra"],
                "proveedor" => $listaProveedor,
                "productos" => $listaProductos
            );

            $respuesta = ModelOrdenesCompras::mdlEditarOrdenCompra($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡La Orden de Compra se ha Editado Correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "orden-compra";
                            }
                        });
                </script>';
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
            $datos = $_GET["idOrden"];

            $respuesta = ModelOrdenesCompras::mdlBorrarOrdenCompra($tabla, $datos);

            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡La orden de compra ha sido borrada correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
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