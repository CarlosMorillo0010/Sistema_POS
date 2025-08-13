<?php

class ControllerClients
{
    /*=============================================
    MOSTRAR CLIENTES
    =============================================*/
    static public function ctrMostrarClientes($item, $valor)
    {
        $tabla = "clientes";
        $respuesta = ModelClients::mdlMostrarClientes($tabla, $item, $valor);
        return $respuesta;
    }

   /*=============================================
    CREAR CLIENTES
    =============================================*/
    static public function ctrCrearClientes(){

        if (isset($_POST["nuevoNombre"])) {

            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"]) &&
                preg_match('/^[0-9]+$/', $_POST["codigoCliente"]) &&
                preg_match('/^[a-zA-Z]+$/', $_POST["nuevaNacionalidad"]) &&
                preg_match('/^[0-9]+$/', $_POST["nuevoDocumento"]))
                {
                    
                $tabla = "clientes";

                date_default_timezone_set('America/Caracas');

                $fecha = date('Y-m-d h:i:s');

                $datos = array(

                    "id_usuario" => $_SESSION["id_usuario"],
                    "codigo" => $_POST["codigoCliente"],
                    "tipo_documento" => $_POST["nuevaNacionalidad"],
                    "documento" => $_POST["nuevoDocumento"],
                    "nombre" => $_POST["nuevoNombre"],
                    "email" => $_POST["nuevoEmail"],
                    "telefono" => $_POST["nuevoTelefono"],
                    "direccion" => $_POST["nuevaDireccion"],
                    "feregistro" => $fecha
                );

                $respuesta = ModelClients::mdlIngresarClientes($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>
					swal({
						  type: "success",
						  title: "El cliente ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "clientes";
									}
								})
					</script>';
                }
            } else {
                echo '<script>
					swal({
						  type: "error",
						  title: "¡El cliente no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "clientes";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
    EDITAR CLIENTES
    =============================================*/
    static public function ctrEditarCliente()
    {
        if (isset($_POST["editarNombre"])) {
            
            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"]) &&
                preg_match('/^[0-9]+$/', $_POST["editarCodigo"]) &&
                preg_match('/^[a-zA-Z]+$/', $_POST["editarNacionalidad"]) &&
                preg_match('/^[0-9]+$/', $_POST["editarDocumento"]))
                {
                $tabla = "clientes";

                $datos = array(

                    "id" => $_POST["idCliente"],
                    "codigo" => $_POST["editarCodigo"],
                    "tipo_documento" => $_POST["editarNacionalidad"],
                    "documento" => $_POST["editarDocumento"],
                    "nombre" => $_POST["editarNombre"],
                    "email" => $_POST["editarEmail"],
                    "telefono" => $_POST["editarTelefono"],
                    "direccion" => $_POST["editarDireccion"],

                );
                $respuesta = ModelClients::mdlEditarCliente($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
					swal({
						  type: "success",
						  title: "El cliente ha sido editado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "clientes";
									}
								})
					</script>';
                }
            } else {
                echo '<script>
					swal({
						  type: "error",
						  title: "¡El cliente no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "clientes";
							}
						})
			  	</script>';
            }
        }
    }

    /*======================================
    BORRAR CLIENTES
    ======================================**/
    static public function ctrEliminarCliente()
    {
        if (isset($_GET["idCliente"])) {
            $tabla = "clientes";
            $datos = $_GET["idCliente"];
            $respuesta = ModelClients::mdlEliminarCliente($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>
						swal({
							  type: "success",
							  title: "El cliente ha sido borrado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar",
							  }).then(function(result){
										if (result.value) {
										window.location = "clientes";
										}
									})
						</script>';
            }
        }
    }
}
