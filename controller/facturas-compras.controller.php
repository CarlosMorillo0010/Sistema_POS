<?php

class ControllerFacturasCompras
{
    /*=============================================
    MOSTRAR FACTURA COMPRA
    =============================================*/
    static public function ctrMostrarFacturaCompra($item, $valor)
    {
        $tabla = "factura_compra";
        $respuesta = ModelFacturasCompras::mdlMostrarFacturaCompra($tabla, $item, $valor);
        return $respuesta;
    }

    /*======================================
     CREAR FACTURA COMPRA
    ======================================**/
    static public function ctrCrearFacturaCompra()
    {
        if (isset($_POST["nuevoCodigoFacturaCompra"])) {
            if (preg_match('/^[0-9]+$/', $_POST["nuevoCodigoFacturaCompra"]) &&
                preg_match('/^[0-9]+$/', $_POST["numeroPedido"])
            )
            {
                $tabla = " factura_compra";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "id_proveedor" => $_POST["idProveedor"],
                    "codigo" => $_POST["nuevoCodigoFacturaCompra"],
                    "numero_pedido" => $_POST["numeroPedido"],
                    "fecha_emision" => $_POST["fechaRegistro"],
                    "fecha_vencimiento" => $_POST["fechaVencimiento"],
                    "proveedor" => $_POST["proveedorFC"],
                    "listaProductos" => $_POST["productosFC"],
                    "feregistro" => $fecha
                );
                $respuesta = ModelFacturasCompras::mdlIngresarFacturaCompra($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>
                    swal({
                        type: "success",
                        title: "¡La Factura de Compra ha sido guardada correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "facturas-compra";
                            }
                        });
                    </script>';
                }
            } else {
                echo '<script>
                    swal({
                        type: "error",
                        title: "¡La Factura de Compra no puede ir con los campos vacios o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "facturas-compra";
                            }
                        });
                </script>';
            }
        }
    }

    /*======================================
     EDITAR FACTURA DE COMPRA
    =======================================*/
    static public function ctrEditarFacturaCompra()
    {
        if (isset($_POST["editarUsuario"])) {
            if (preg_match('/[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"])) {
                $tabla = "usuarios";
                if ($_POST["editarPassword"] != "") {
                    if (preg_match('/[a-zA-Z0-9]+$/', $_POST["editarPassword"])) {
                        $encriptar = crypt($_POST["editarPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
                    } else {
                        echo '
                            <script>
                                swal({
                                    type: "error",
                                    title: "¡La contraseña no puede ir vacia!",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    closeOnConfirm: false
                                    }).then((result) => {
                                        if(result.value){
                                            window.location = "usuarios";
                                        }
                                    });
                            </script>';
                        return;
                    }
                } else {
                    $encriptar = $_POST["passwordActual"];
                }
                $datos = array(
                    "nombre" => $_POST["editarNombre"],
                    "usuario" => $_POST["editarUsuario"],
                    "password" => $encriptar,
                    "telefono" => $_POST["editarTelefono"],
                    "correo" => $_POST["editarEmail"],
                    "genero" => $_POST["editarGenero"],
                    "perfil" => $_POST["editarPerfil"]
                );
                $respuesta = ModelFacturasCompras::mdlEditarFacturaCompra($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '
                        <script>
                            swal({
                                type: "success",
                                title: "¡El Usuario ha sido editado correctamete!",
                                showConfirmButton: true,
                                confirmButtonText: "Cerrar",
                                closeOnConfirm: false
                                    }).then((result) => {
                                        if(result.value){
                                            window.location = "usuarios";
                                        }
                                    });
                        </script>
                    ';
                }
            } else {
                echo '
                <script>
                    swal({
                        type: "error",
                        title: "¡El nombre no puede ir vacio o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "usuarios";
                            }
                        });
                </script>';
            }
        }
    }

    /*======================================
     BORRAR FACTURA DE COMPRA
    =======================================*/
    static public function ctrBorrarFacturaCompra()
    {
        if (isset($_GET["idFacturaCompra"])) {
            $tabla = "factura_compra";
            $datos = $_GET["idFacturaCompra"];

            $respuesta = ModelFacturasCompras::mdlBorrarFacturaCompra($tabla, $datos);

            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡La Factura de Compra ha sido borrada correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "facturas-compra";
                            }
                        });
                </script>';
            }
        }
    }
}