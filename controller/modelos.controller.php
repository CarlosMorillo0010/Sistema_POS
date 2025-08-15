<?php

class ControllerModelos{
    /*=============================================
    CREAR MODELO
    =============================================*/

    static public function ctrCrearModelo(){
        if(isset($_POST["nuevoModelo"])){
            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoModelo"])){
                $tabla = "modelos";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "modelo" => $_POST["nuevoModelo"],
                    "fecha" => $fecha
                );
                $respuesta = ModelModelos::mdlIngresarModelo($tabla, $datos);
                if($respuesta == "ok"){
                    echo'<script>
					Swal.fire({
						  icon: "success",
						  title: "El modelo ha sido guardada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "modelos";
									}
								})
					</script>';
                }
            }else{
                echo'<script>
					Swal.fire({
						  icon: "error",
						  title: "¡El modelo no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "modelos";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
    MOSTRAR MODELOS
    =============================================*/

    static public function ctrMostrarModelos($item, $valor){
        $tabla = "modelos";
        $respuesta = ModelModelos::mdlMostrarModelos($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    EDITAR MODELOS
    =============================================*/

    static public function ctrEditarModelo(){
        if(isset($_POST["editarModelo"])){
            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarModelo"])){
                $tabla = "modelos";
                $datos = array(
                    "modelo" => $_POST["editarModelo"],
                    "id_modelo" => $_POST["idModelo"]);

                $respuesta = ModelModelos::mdlEditarModelo($tabla, $datos);
                if($respuesta == "ok"){
                    echo'<script>
					Swal.fire({
						  icon: "success",
						  title: "El modelo ha sido cambiada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "modelos";
									}
								})
					</script>';
                }
            }else{
                echo'<script>
					Swal.fire({
						  icon: "error",
						  title: "¡El modelo no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "modelos";
							}
						})
			  	</script>';
            }
        }
    }

    /**=====================================
    BORRAR MODELOS
    ======================================**/

    static public function ctrBorrarModelo(){
        if (isset($_GET["idModelo"])){
            $tabla = "modelos";
            $datos = $_GET["idModelo"];

            $respuesta = ModelModelos::mdlBorrarModelo($tabla, $datos);
            if($respuesta == "ok"){
                echo'<script>
						Swal.fire({
							  icon: "success",
							  title: "El modelo ha sido borrada correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {
										window.location = "modelos";
										}
									})
						</script>';
            }
        }
    }
}