<?php

class ControllerLibroCompras
{
    /*=============================================
    CREAR LIBRO COMPRAS
    =============================================*/
    static public function ctrCrearLibroCompra()
    {
        if (isset($_POST["nuevaDescripcion"])) {
            if (preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaDescripcion"]) &&
                preg_match('/^[0-9.,]+$/', $_POST["nuevoMonto"]) &&
                preg_match('/^[0-9.,]+$/', $_POST["nuevoIva"]) &&
                preg_match('/^[0-9.,]+$/', $_POST["nuevoTotal"])) {
                $tabla = "libro_compras";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario"    => $_SESSION["id_usuario"],
                    "numfactura"    => $_POST["nuevoNumFactura"],
                    "descripcion"   => $_POST["nuevaDescripcion"],
                    "proveedor"     => $_POST["nuevoProveedor"],
                    "dias_credito"  => $_POST["diasCredito"],
                    "rif"           => $_POST["nuevoRif"],
                    "numcontrol"    => $_POST["nuevoNumControl"],
                    "tipodoc"       => $_POST["nuevoTipoDoc"],
                    "monto"         => $_POST["nuevoMonto"],
                    "iva"           => $_POST["nuevoIva"],
                    "total"         => $_POST["nuevoTotal"],
                    "metodo"        => $_POST["nuevoMetodo"],
                    "fecha"         => $_POST["nuevaFecha"],
                    "estado"        => $_POST["nuevoEstado"],
                    "observacion"   => $_POST["nuevaObservacion"],
                    "feregistro"    => $fecha
                );
                $respuesta = ModelLibroCompras::mdlIngresarLibroCompra($tabla, $datos);

                if ($respuesta == "ok" && $_POST["nuevoEstado"] == "Pendiente") {

                // Obtener la fecha de emisión y los días de crédito del formulario
                $fechaEmisionStr = $_POST["nuevaFecha"];
                $diasCredito = isset($_POST["diasCredito"]) ? intval($_POST["diasCredito"]) : 0;
                
                // Variable para la fecha de vencimiento
                $fechaVencimiento = null;

                // 2. Calcular la fecha de vencimiento solo si hay días de crédito
                if ($diasCredito > 0) {
                    // Usamos la clase DateTime que es más segura y robusta para manipular fechas
                    $fechaEmisionObj = new DateTime($fechaEmisionStr);
                    
                    // Añadimos el intervalo de días de crédito
                    $fechaEmisionObj->add(new DateInterval("P{$diasCredito}D"));
                    
                    // 3. Formateamos la fecha para guardarla en la base de datos (YYYY-MM-DD)
                    $fechaVencimiento = $fechaEmisionObj->format('Y-m-d');
                } else {
                    // Si no hay días de crédito, la fecha de vencimiento es la misma que la de emisión
                    $fechaVencimiento = $fechaEmisionStr;
                }

                    // Obtener el último ID insertado del libro de compras
                    $idLibroCompra = ModelLibroCompras::mdlObtenerUltimoId($tabla);

                    // Datos para cuentas por pagar
                    $datosCuenta = array(
                        "id_usuario"    => $_SESSION["id_usuario"],
                        "id_libro_compra"   => $idLibroCompra,
                        "proveedor"         => $_POST["nuevoProveedor"],
                        "factura"           => $_POST["nuevoNumFactura"],
                        "monto"             => $_POST["nuevoMonto"],
                        "iva"               => $_POST["nuevoIva"],
                        "total"             => $_POST["nuevoTotal"],
                        "fecha_emision"     => $_POST["nuevaFecha"],
                        "fecha_vencimiento" => $fechaVencimiento,
                        "estado"            => "Pendiente",
                        "metodo_pago"       => $_POST["nuevoMetodo"],
                        "observacion"       => $_POST["nuevaObservacion"],
                        "feregistro"    => $fecha

                    );
                    ModelCuentasPagar::mdlIngresarCuentaPagar("cuentas_pagar", $datosCuenta);
                }

                if ($respuesta == "ok") {
                    echo '<script>
                    Swal.fire({
                          icon: "success",
                          title: "El registro ha sido guardado correctamente",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                          }).then(function(result){
                                    if (result.value) {
                                    window.location = "libro-compras";
                                    }
                                })
                    </script>';
                }
            } else {
                echo '<script>
                    Swal.fire({
                          icon: "error",
                          title: "¡El registro no puede ir vacía o llevar caracteres especiales!",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                          }).then(function(result){
                            if (result.value) {
                            window.location = "libro-compras";
                            }
                        })
              	</script>';
            }
        }
    }

    /*=============================================
    MOSTRAR LIBRO COMPRAS
    =============================================*/
    static public function ctrMostrarLibroCompras($item, $valor)
    {
        $tabla = "libro_compras";
        $respuesta = ModelLibroCompras::mdlMostrarLibroCompra($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    EDITAR LIBRO COMPRAS
    =============================================*/
    static public function ctrEditarLibroCompra()
    {
        if (isset($_POST["editarDescripcion"])) {

            if (preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarDescripcion"]) &&
                preg_match('/^[0-9.,]+$/', $_POST["editarMonto"]) &&
                preg_match('/^[0-9.,]+$/', $_POST["editarIva"]) &&
                preg_match('/^[0-9.,]+$/', $_POST["editarTotal"])) {

                $tabla = "libro_compras";

                $datos = array(
                    "id"            => $_POST["idLibroCompra"],
                    "numfactura"    => $_POST["editarNumFactura"],
                    "descripcion"   => $_POST["editarDescripcion"],
                    "proveedor"     => $_POST["editarProveedor"],
                    "dias_credito"  => $_POST["editarDiasCredito"],
                    "rif"           => $_POST["editarRif"],
                    "numcontrol"    => $_POST["editarNumControl"],
                    "tipodoc"       => $_POST["editarTipoDoc"],
                    "monto"         => $_POST["editarMonto"],
                    "iva"           => $_POST["editarIva"],
                    "total"         => $_POST["editarTotal"],
                    "metodo"        => $_POST["editarMetodo"],
                    "fecha"         => $_POST["editarFecha"],
                    "estado"        => $_POST["editarEstado"],
                    "observacion"   => $_POST["editarObservacion"]
                );

                $respuesta = ModelLibroCompras::mdlEditarLibroCompra($tabla, $datos);

                if ($respuesta == "ok") {

                    // Obtener la fecha de emisión y los días de crédito del formulario
                    $fechaEmisionStr = $_POST["editarFecha"];
                    $diasCredito = isset($_POST["editarDiasCredito"]) ? intval($_POST["editarDiasCredito"]) : 0;
                    
                    // Variable para la fecha de vencimiento
                    $fechaVencimiento = null;

                    // 2. Calcular la fecha de vencimiento solo si hay días de crédito
                    if ($diasCredito > 0) {
                        // Usamos la clase DateTime que es más segura y robusta para manipular fechas
                        $fechaEmisionObj = new DateTime($fechaEmisionStr);
                        
                        // Añadimos el intervalo de días de crédito
                        $fechaEmisionObj->add(new DateInterval("P{$diasCredito}D"));
                        
                        // 3. Formateamos la fecha para guardarla en la base de datos (YYYY-MM-DD)
                        $fechaVencimiento = $fechaEmisionObj->format('Y-m-d');
                    } else {
                        // Si no hay días de crédito, la fecha de vencimiento es la misma que la de emisión
                        $fechaVencimiento = $fechaEmisionStr;
                    }
                    
                    // Actualizar la cuenta por pagar asociada
                    $datosCuenta = array(
                        "id_libro_compra"   => $_POST["idLibroCompra"],
                        "proveedor"         => $_POST["editarProveedor"],
                        "factura"           => $_POST["editarNumFactura"],
                        "monto"             => $_POST["editarMonto"],
                        "iva"               => $_POST["editarIva"],
                        "total"             => $_POST["editarTotal"],
                        "fecha_emision"     => $_POST["editarFecha"],
                        "fecha_vencimiento" => $fechaVencimiento, 
                        "estado"            => $_POST["editarEstado"],
                        "metodo_pago"       => $_POST["editarMetodo"],
                        "observacion"       => $_POST["editarObservacion"]
                    );
                    ModelCuentasPagar::mdlEditarCuentaPagar("cuentas_pagar", $datosCuenta);
                }

                if ($respuesta == "ok") {
                    echo '<script>
                    Swal.fire({
                          icon: "success",
                          title: "El registro ha sido editado correctamente",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                          }).then(function(result){
                                    if (result.value) {
                                    window.location = "libro-compras";
                                    }
                                })
                    </script>';
                }
            } else {
                echo '<script>
                    Swal.fire({
                          icon: "error",
                          title: "¡El registro no puede ir vacía o llevar caracteres especiales!",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                          }).then(function(result){
                            if (result.value) {
                            window.location = "libro-compras";
                            }
                        })
              	</script>';
            }
        }
    }

    /*=============================================
    BORRAR REGISTRO (USANDO EL MÉTODO TRANSACCIONAL)
    =============================================*/
    static public function ctrBorrarLibroCompra()
    {
        if (isset($_GET["idLibroCompra"])) {
            
            $idLibroCompra = $_GET["idLibroCompra"];
            
            // Llamamos a nuestra nueva función del modelo que maneja toda la lógica compleja.
            $respuesta = ModelLibroCompras::mdlBorrarLibroCompra($idLibroCompra);

            if ($respuesta == "ok") {
                echo '<script>
                        Swal.fire({
                              icon: "success",
                              title: "El registro y su cuenta por pagar asociada han sido borrados correctamente",
                              showConfirmButton: true,
                              confirmButtonText: "Cerrar"
                              }).then(function(result){
                                        if (result.value) {
                                            window.location = "libro-compras";
                                        }
                                    })
                        </script>';
            } else {
                 echo '<script>
                        Swal.fire({
                              icon: "error",
                              title: "Ocurrió un error al intentar borrar el registro",
                              text: "Por favor, inténtelo de nuevo. Si el problema persiste, contacte al administrador.",
                              showConfirmButton: true,
                              confirmButtonText: "Cerrar"
                              }).then(function(result){
                                        if (result.value) {
                                            window.location = "libro-compras";
                                        }
                                    })
                        </script>';
            }
        }
    }
}