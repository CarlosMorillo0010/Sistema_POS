<?php

class ControllerMarcas{
    /*=============================================
    CREAR MARCAS
    =============================================*/

    static public function ctrCrearMarca(){
        if(isset($_POST["nuevaMarca"])){
            if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ& ]+$/', $_POST["nuevaMarca"])){
                $tabla = "marcas";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "marca" => $_POST["nuevaMarca"],
                    "fecha" => $fecha
                );
                $respuesta = ModelMarcas::mdlIngresarMarca($tabla, $datos);
                if($respuesta == "ok"){
                    echo'<script>
					Swal.fire({
						  icon: "success",
						  title: "La marca ha sido guardada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "marcas";
									}
								})
					</script>';
                }
            }else{
                echo'<script>
					Swal.fire({
						  icon: "error",
						  title: "¡La marca no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "marcas";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
    MOSTRAR MARCAS
    =============================================*/

    static public function ctrMostrarMarca($item, $valor){
        $tabla = "marcas";
        $respuesta = ModelMarcas::mdlMostrarMarca($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    EDITAR MARCAS
    =============================================*/

    static public function ctrEditarMarca(){
        if(isset($_POST["editarMarca"])){
            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarMarca"])){
                $tabla = "marcas";
                $datos = array(
                    "marca" => $_POST["editarMarca"],
                    "id_marca" => $_POST["idMarca"]);

                $respuesta = ModelMarcas::mdlEditarMarca($tabla, $datos);
                if($respuesta == "ok"){
                    echo'<script>
					Swal.fire({
						  icon: "success",
						  title: "La marca ha sido cambiada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "marcas";
									}
								})
					</script>';
                }
            }else{
                echo'<script>
					Swal.fire({
						  icon: "error",
						  title: "¡La marca no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "marcas";
							}
						})
			  	</script>';
            }
        }
    }

    /**=====================================
    BORRAR MARCAS
    ======================================**/

    static public function ctrBorrarMarca(){
        if (isset($_GET["idMarca"])){
            $tabla = "marcas";
            $datos = $_GET["idMarca"];

            $respuesta = ModelMarcas::mdlBorrarMarca($tabla, $datos);
            if($respuesta == "ok"){
                echo'<script>
						Swal.fire.fire({
							  icon: "success",
							  title: "La marca ha sido borrada correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {
										window.location = "marcas";
										}
									})
						</script>';
            }
        }
    }
}