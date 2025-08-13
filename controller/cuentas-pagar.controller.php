<?php


class ControllerCuentasPagar
{

    /*=============================================
    MOSTRAR RECORDATORIOS DE PAGO
    =============================================*/
    static public function ctrMostrarRecordatoriosDeuda()
    {
        $respuesta = ModelCuentasPagar::mdlMostrarRecordatoriosDeuda();
        return $respuesta;
    }

    /*=============================================
    CREAR CUENTA POR PAGAR
    =============================================*/
    static public function ctrCrearCuentaPagar()
    {
        if (isset($_POST["nuevoNombre"])) {

            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"]) &&
                preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoProveedor"]) &&
                preg_match('/^[a-zA-Z]+$/', $_POST["nuevoTipoDocumento"]) &&
                preg_match('/^[0-9]+$/', $_POST["nuevoDocumento"]) &&
                preg_match('/^[0-9.]+$/', $_POST["nuevoMonto"]) &&
                preg_match('/^[0-9.]+$/', $_POST["nuevoSaldo"]) &&
                preg_match('/^[a-zA-Z]+$/', $_POST["nuevoEstatus"])) {

                $tabla = "cuentas_pagar";

                date_default_timezone_set('America/Caracas');

                $fecha = date('Y-m-d h:i:s');

                $datos = array(

                    "id_usuario" => $_SESSION["id_usuario"],
                    "codigo" => $_POST["nuevoCodigo"],
                    "proveedor" => $_POST["nuevoProveedor"],
                    "fecha_cuenta" => $_POST["nuevaFechaCuenta"],
                    "fecha_ano" => $_POST["nuevaFechaAno"],
                    "fecha_factura" => $_POST["nuevaFechaFactura"],
                    "nombre" => $_POST["nuevoNombre"],
                    "tipo_documento" => $_POST["nuevoTipoDocumento"],
                    "documento" => $_POST["nuevoDocumento"],
                    "monto" => $_POST["nuevoMonto"],
                    "saldo" => $_POST["nuevoSaldo"],
                    "descripcion" => $_POST["nuevaDescripcion"],
                    "estatus" => $_POST["nuevoEstatus"],
                    "feregistro" => $fecha

                );

                $respuesta = ModelCuentasPagar::mdlIngresarCuentaPagar($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
					swal({
						  type: "success",
						  title: "La cuenta ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "cuentas-pagar";
									}
								})
					</script>';
                }
            } else {
                echo '<script>
					swal({
						  type: "error",
						  title: "¡La cuenta no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "cuentas-pagar";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
    MOSTRAR CUENTAS POR PAGAR
    =============================================*/
    static public function ctrMostrarCuentasPagar($item, $valor)
    {
        $tabla = "cuentas_pagar";
        $respuesta = ModelCuentasPagar::mdlMostrarCuentaPagar($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    EDITAR CUENTA POR PAGAR
    =============================================*/
    static public function ctrEditarCuentaPagar()
    {
        if (isset($_POST["editarNombre"])) {

            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"]) &&
                preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarProveedor"]) &&
                preg_match('/^[a-zA-Z]+$/', $_POST["editarTipoDocumento"]) &&
                preg_match('/^[0-9]+$/', $_POST["editarDocumento"]) &&
                preg_match('/^[0-9.]+$/', $_POST["editarMonto"]) &&
                preg_match('/^[0-9.]+$/', $_POST["editarSaldo"]) &&
                preg_match('/^[a-zA-Z]+$/', $_POST["editarEstatus"])) {

                $tabla = "cuentas_pagar";

                $datos = array(
                            
                            "id_cuentas_pagar" => $_POST["idCuentaPagar"],
                            "codigo" => $_POST["editarCodigo"],
                            "proveedor" => $_POST["editarProveedor"],
                            "fecha_cuenta" => $_POST["editarFechaCuenta"],
                            "fecha_ano" => $_POST["editarFechaAno"],
                            "fecha_factura" => $_POST["editarFechaFactura"],
                            "nombre" => $_POST["editarNombre"],
                            "tipo_documento" => $_POST["editarTipoDocumento"],
                            "documento" => $_POST["editarDocumento"],
                            "monto" => $_POST["editarMonto"],
                            "saldo" => $_POST["editarSaldo"],
                            "descripcion" => $_POST["editarDescripcion"],
                            "estatus" => $_POST["editarEstatus"],
                        
                        );

                            

                $respuesta = ModelCuentasPagar::mdlEditarCuentaPagar($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
					swal({
						  type: "success",
						  title: "La cuenta ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "cuentas-pagar";
									}
								})
					</script>';
                }
            } else {
                echo '<script>
					swal({
						  type: "error",
						  title: "¡La cuenta no puede ir vací o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "cuentas-pagar";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
    REGISTRAR PAGO DE CUENTA
    =============================================*/
    static public function ctrPagarCuenta()
    {
        if (isset($_POST["idCuentaPagar"])) {

            $datos = array(
                "id_libro_compra" => $_POST["idCuentaPagar"],
                "estado"          => "Pagada",
                "fecha_pago"      => $_POST["fechaPago"],
                "nota_pago"       => $_POST["notaPago"]
            );

            $respuesta = ModelCuentasPagar::mdlPagarCuenta($datos);

            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                          type: "success",
                          title: "¡El pago se registró correctamente!",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "cuentas-pagar";
                        }
                    });
                </script>';
            } else {
                 echo '<script>
                    swal({
                          type: "error",
                          title: "¡Error al procesar el pago!",
                          text: "Ocurrió un problema en la base de datos. Los cambios fueron revertidos.",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "cuentas-pagar";
                        }
                    });
                </script>';
            }
        }
    }
}
