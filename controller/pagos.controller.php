<?php

class ControllerPagos
{
    /*=============================================
    CREAR PAGOS
    =============================================*/
    static public function ctrCrearPago()
    {
        if (isset($_POST["nuevaPago"])) {
            if (preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaPago"])) {
                $tabla = "formas_pagos";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "formaPago" => $_POST["nuevaPago"],
                    "fecha" => $fecha
                );
                $respuesta = ModelPagos::mdlIngresarPago($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
					swal({
						  type: "success",
						  title: "El pago ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "formas-pago";
									}
								})
					</script>';
                }
            } else {
                echo '<script>
					swal({
						  type: "error",
						  title: "¡El pago no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "formas-pago";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
    MOSTRAR PAGOS
    =============================================*/
    static public function ctrMostrarPago($item, $valor)
    {
        $tabla = "formas_pagos";
        $respuesta = ModelPagos::mdlMostrarPago($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    EDITAR PAGOS
    =============================================*/
    static public function ctrEditarPago()
    {
        if (isset($_POST["editarPago"])) {
            if (preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarPago"])) {
                $tabla = "formas_pagos";
                $datos = array(
                    "forma_pago" => $_POST["editarPago"],
                    "id_forma_pagos" => $_POST["idPago"]
                );
                $respuesta = ModelPagos::mdlEditarPago($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
					swal({
						  type: "success",
						  title: "La forma de pago ha sido cambiada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "formas-pago";
									}
								})
					</script>';
                }
            } else {
                echo '<script>
					swal({
						  type: "error",
						  title: "¡La forma de pago no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "formas-pago";
							}
						})
			  	</script>';
            }
        }
    }

    /*======================================
     * BORRAR PAGOS
     * ======================================**/
    static public function ctrBorrarPago()
    {
        if (isset($_GET["idPago"])) {
            $tabla = "formas_pagos";
            $datos = $_GET["idPago"];
            $respuesta = ModelPagos::mdlBorrarPago($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>
					swal({
						type: "success",
						title: "La forma de pago ha sido borrada correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
							if (result.value) {
								window.location = "formas-pago";
								}
							})
				</script>';
            }
        }
    }
}