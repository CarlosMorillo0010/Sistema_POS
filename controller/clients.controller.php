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
                preg_match('/^[a-zA-Z]+$/', $_POST["nuevaNacionalidad"]) &&
                preg_match('/^[0-9-]+$/', $_POST["nuevoDocumento"]))
                {
                    
                $tabla = "clientes";

                // Unir teléfonos
                $telefono = "";
                if(!empty($_POST["nuevoTelefono1"])){
                    $telefono .= "Cel: " . $_POST["nuevoTelefono1"];
                }
                if(!empty($_POST["nuevoTelefono2"])){
                    if(!empty($telefono)) $telefono .= " / ";
                    $telefono .= "Fijo: " . $_POST["nuevoTelefono2"];
                }

                // Unir dirección
                $direccion = "";
                if(!empty($_POST["nuevoEstado"])){
                    $direccion .= $_POST["nuevoEstado"];
                }
                if(!empty($_POST["nuevaCiudad"])){
                    if(!empty($direccion)) $direccion .= ", ";
                    $direccion .= $_POST["nuevaCiudad"];
                }
                if(!empty($_POST["nuevaDireccion"])){
                    if(!empty($direccion)) $direccion .= ", ";
                    $direccion .= $_POST["nuevaDireccion"];
                }

                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');

                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "tipo_documento" => $_POST["nuevaNacionalidad"],
                    "documento" => $_POST["nuevoDocumento"],
                    "nombre" => $_POST["nuevoNombre"],
                    "telefono" => $telefono,
                    "direccion" => $direccion,
                    "feregistro" => $fecha
                );

                $respuesta = ModelClients::mdlIngresarClientes($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>
					Swal.fire({
						  icon: "success",
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
					Swal.fire({
						  icon: "error",
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
                preg_match('/^[a-zA-Z]+$/', $_POST["editarNacionalidad"]) &&
                preg_match('/^[0-9-]+$/', $_POST["editarDocumento"]))
                {
                $tabla = "clientes";

                // Unir teléfonos
                $telefono = "";
                if(!empty($_POST["editarTelefono1"])){
                    $telefono .= "Cel: " . $_POST["editarTelefono1"];
                }
                if(!empty($_POST["editarTelefono2"])){
                    if(!empty($telefono)) $telefono .= " / ";
                    $telefono .= "Fijo: " . $_POST["editarTelefono2"];
                }

                // Unir dirección
                $direccion = "";
                if(!empty($_POST["editarEstado"])){
                    $direccion .= $_POST["editarEstado"];
                }
                if(!empty($_POST["editarCiudad"])){
                    if(!empty($direccion)) $direccion .= ", ";
                    $direccion .= $_POST["editarCiudad"];
                }
                if(!empty($_POST["editarDireccion"])){
                    if(!empty($direccion)) $direccion .= ", ";
                    $direccion .= $_POST["editarDireccion"];
                }

                $datos = array(
                    "id" => $_POST["idCliente"],
                    "tipo_documento" => $_POST["editarNacionalidad"],
                    "documento" => $_POST["editarDocumento"],
                    "nombre" => $_POST["editarNombre"],
                    "telefono" => $telefono,
                    "direccion" => $direccion,
                );
                $respuesta = ModelClients::mdlEditarCliente($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
					Swal.fire({
						  icon: "success",
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
					Swal.fire({
						  icon: "error",
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
    ======================================*/
    static public function ctrEliminarCliente()
    {
        if (isset($_GET["idCliente"])) {
            $tabla = "clientes";
            $datos = $_GET["idCliente"];
            $respuesta = ModelClients::mdlEliminarCliente($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>
						Swal.fire({
							  icon: "success",
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