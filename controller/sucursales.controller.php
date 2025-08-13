<?php

class ControllerSucursales
{
    /*=============================================
    CREAR SUCURSALES
    =============================================*/
    static public function ctrCrearSucursal()
    {
        if (isset($_POST["nuevoNombre"])) {
            if (preg_match('/^[0-9 ]+$/', $_POST["nuevoCodigo"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ$€ ]+$/', $_POST["nuevoNombre"])) {
                $tabla = "sucursales";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "codigo" => $_POST["nuevoCodigo"],
                    "sucursal" => $_POST["nuevoNombre"],
                    "fecha" => $fecha
                );
                $respuesta = ModelSucursales::mdlIngresarSucursal($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
					swal({
						  type: "success",
						  title: "La sucursal ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "sucursales";
									}
								})
					</script>';
                }
            } else {
                echo '<script>
					swal({
						  type: "error",
						  title: "¡La sucursal no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "sucursales";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
    MOSTRAR SUCURSALES
    =============================================*/
    static public function ctrMostrarSucursal($item, $valor)
    {
        $tabla = "sucursales";
        $respuesta = ModelSucursales::mdlMostrarSucursal($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    EDITAR SUCURSALES
    =============================================*/
    static public function ctrEditarSucursal()
    {
        if (isset($_POST["editarNombre"])) {
            if (preg_match('/^[0-9 ]+$/', $_POST["editarCodigo"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"])) {
                $tabla = "sucursales";
                $datos = array(
                    "codigo" => $_POST["editarCodigo"],
                    "sucursal" => $_POST["editarNombre"],
                    "id_sucursal" => $_POST["idSucursal"]
                );
                $respuesta = ModelSucursales::mdlEditarSucursal($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
					swal({
						  type: "success",
						  title: "La sucursal ha sido cambiada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "sucursales";
									}
								})
					</script>';
                }
            } else {
                echo '<script>
					swal({
						  type: "error",
						  title: "¡La sucursal no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "sucursales";
							}
						})
			  	</script>';
            }
        }
    }

    /**=====================================
     * BORRAR SUCURSALES
     * ======================================**/
    static public function ctrBorrarSucursal()
    {
        if (isset($_GET["idSucursal"])) {
            $tabla = "sucursales";
            $datos = $_GET["idSucursal"];
            $respuesta = ModelSucursales::mdlBorrarSucursal($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>
						swal({
							  type: "success",
							  title: "La sucursal ha sido borrada correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {
										window.location = "sucursales";
										}
									})
						</script>';
            }
        }
    }
}