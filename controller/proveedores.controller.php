<?php

class ControllerProveedores
{
    /*=============================================
    MOSTRAR PROVEEDORES
    =============================================*/
    static public function ctrMostrarProveedores($item, $valor)
    {
        $tabla = "proveedores";
        $respuesta = ModelProveedores::mdlMostrarProveedores($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    CREAR PROVEEDORES
    =============================================*/
    static public function ctrCrearProveedor()
    {
        if (isset($_POST["nuevoProveedor"])) {

            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ,.;: ]+$/', $_POST["nuevoProveedor"]) &&
                preg_match('/^[0-9]+$/', $_POST["nuevoCodigo"]) &&
                preg_match('/^[a-zA-Z]+$/', $_POST["tipoPersona"]) &&
                preg_match('/^[a-zA-Z]+$/', $_POST["tipoDocumento"]) &&
                preg_match('/^[0-9-]+$/', $_POST["numeroDocumento"])) // Se permite guión para el RIF
                {

                $tabla = "proveedores";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "codigo" => $_POST["nuevoCodigo"],
                    "tipo_persona" => $_POST["tipoPersona"],
                    "tipo_documento" => $_POST["tipoDocumento"],
                    "documento" => $_POST["numeroDocumento"],
                    "nombre" => $_POST["nuevoProveedor"],
                    "telefono" => $_POST["nuevoTelefono"],
                    "dias_credito" => $_POST["nuevoDiasCredito"],
                    "email" => $_POST["nuevoEmail"],
                    "estado" => $_POST["nuevoEstado"],
                    "ciudad" => $_POST["nuevaCiudad"],
                    "direccion" => $_POST["nuevaDireccion"],
                    "nota" => $_POST["nuevaNota"],
                    "fecha" => $fecha
                );

                $respuesta = ModelProveedores::mdlIngresarProveedor($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>

					Swal.fire({
						  icon: "success",
						  title: "El proveedor ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "proveedores";

									}
								})

					</script>';
                }
            } else {
                echo '<script>
					Swal.fire({
						  icon: "error",
						  title: "¡El proveedor no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "proveedores";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
    EDITAR PROVEEDORES
    =============================================*/
    static public function ctrEditarProveedor()
    {
        if (isset($_POST["editarProveedor"])) {
            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ,.;: ]+$/', $_POST["editarProveedor"]) &&
                preg_match('/^[0-9]+$/', $_POST["editarCodigo"]) &&
                preg_match('/^[a-zA-Z]+$/', $_POST["editarTipoPersona"]) &&
                preg_match('/^[a-zA-Z]+$/', $_POST["editarTipoDocumento"]) &&
                preg_match('/^[0-9-]+$/', $_POST["editarNumeroDocumento"])) // Se permite guión para el RIF
                {

                $tabla = "proveedores";
                $datos = array(

                    "id_proveedor" => $_POST["idProveedor"],
                    "tipo_persona" => $_POST["editarTipoPersona"],
                    "tipo_documento" => $_POST["editarTipoDocumento"],
                    "documento" => $_POST["editarNumeroDocumento"],
                    "nombre" => $_POST["editarProveedor"],
                    "telefono" => $_POST["editarTelefono"],
                    "dias_credito" => $_POST["editarDiasCredito"],
                    "email" => $_POST["editarEmail"],
                    "estado" => $_POST["editarEstado"],
                    "ciudad" => $_POST["editarCiudad"],
                    "direccion" => $_POST["editarDireccion"],
                    "nota" => $_POST["editarNota"],

                );
                $respuesta = ModelProveedores::mdlEditarProveedor($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
					Swal.fire({
						  icon: "success",
						  title: "El proveedor ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "proveedores";
									}
								})
					</script>';
                }
            } else {
                echo '<script>
					Swal.fire({
						  icon: "error",
						  title: "¡El proveedor no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "proveedores";
							}
						})
			  	</script>';
            }
        }
    }

    /*======================================
    ELIMINAR PROVEEDOR
    ======================================**/
    static public function ctrEliminarProveedor()
    {
        if (isset($_GET["idProveedor"])) {
            $tabla = "proveedores";
            $datos = $_GET["idProveedor"];
            $respuesta = ModelProveedores::mdlEliminarProveedor($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>
						Swal.fire({
							  icon: "success",
							  title: "El proveedor ha sido borrado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar",
							  }).then(function(result){
										if (result.value) {
										window.location = "proveedores";
										}
									})
						</script>';
            }
        }
    }
}
