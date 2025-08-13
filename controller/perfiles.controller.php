<?php

class ControllerPerfiles{

    /*=============================================
	CREAR PERFILES
	=============================================*/
    static public function ctrCrearPerfil(){
        if(isset($_POST["nuevoPerfil"])){
            if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoPerfil"])){
                $tabla = "perfiles";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "perfil" => $_POST["nuevoPerfil"],
                    "fecha" => $fecha
                );
                $respuesta = ModelPerfiles::mdlIngresarPerfil($tabla, $datos);
                if($respuesta == "ok"){
                    echo'<script>
					swal({
						  type: "success",
						  title: "El perfil ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "config-perfil";
									}
								})
					</script>';
                }
            }else{
                echo'<script>
					swal({
						  type: "error",
						  title: "¡El perfil no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "config-perfil";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
	MOSTRAR PERFILES
	=============================================*/
    static public function ctrMostrarPerfil($item, $valor){
        $tabla = "perfiles";
        $respuesta = ModelPerfiles::mdlMostrarPerfil($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
	EDITAR PERFILES
	=============================================*/
    static public function ctrEditarPerfil(){
        if (isset($_POST["editarPerfil"])){
            if (preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarPerfil"])){
                $tabla = "perfiles";
                $datos = array(
                    "perfil"=>$_POST["editarPerfil"],
                    "id_perfil"=>$_POST["idPerfil"]
                );
                $respuesta = ModelPerfiles::mdlEditarPerfil($tabla, $datos);

                if ($respuesta == "ok"){
                    echo'<script>
					swal({
						  type: "success",
						  title: "El perfil ha sido cambiado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "config-perfil";
									}
								})
					</script>';
                }
            }else{
                echo'<script>
					swal({
						  type: "error",
						  title: "¡El perfil no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "config-perfil";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
	BORRAR PERFILES
	=============================================*/
    static public function ctrBorrarPerfil(){
        if(isset($_GET["idPerfil"])){
            $tabla = "perfiles";
            $datos = $_GET["idPerfil"];

            $respuesta = ModelPerfiles::mdlBorrarPerfil($tabla, $datos);

            if ($respuesta == "ok"){
                echo'<script>
					swal({
						  type: "success",
						  title: "El perfil ha sido borrado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "config-perfil";
									}
								})
					</script>';
            }
        }
    }
}
