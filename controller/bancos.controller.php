<?php

class ControllerBancos{

	/*=============================================
	CREAR BANCOS
	=============================================*/
	static public function ctrCrearBanco(){
		if(isset($_POST["nuevaBanco"])){
			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ$%!@. ]+$/', $_POST["nuevaBanco"])){
				$tabla = "bancos";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
				$datos = array(
				    "id_usuario" => $_SESSION["id_usuario"],
                    "nombreBanco" => $_POST["nuevaBanco"],
                    "fecha" => $fecha
                );
				$respuesta = ModelBancos::mdlIngresarBanco($tabla, $datos);
				if($respuesta == "ok"){
					echo'<script>
					swal({
						  type: "success",
						  title: "El banco ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "bancos";
									}
								})
					</script>';
				}
			}else{
				echo'<script>
					swal({
						  type: "error",
						  title: "¡El banco no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "bancos";
							}
						})
			  	</script>';
			}
        }
    }
    
	/*=============================================
	MOSTRAR BANCOS
	=============================================*/
	static public function ctrMostrarBanco($item, $valor){
        $tabla = "bancos";
        $respuesta = ModelBancos::mdlMostrarBanco($tabla, $item, $valor);
        return $respuesta;
    }

	/*=============================================
	EDITAR BANCO
	=============================================*/
	static public function ctrEditarBanco(){
		if(isset($_POST["editarBanco"])){
			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ$%!@. ]+$/', $_POST["editarBanco"])){
				$tabla = "bancos";
				$datos = array(
				    "banco" => $_POST["editarBanco"],
                    "id_banco" => $_POST["idBanco"]
                );
				$respuesta = ModelBancos::mdlEditarBanco($tabla, $datos);
				if($respuesta == "ok"){
					echo'<script>
					swal({
						type: "success",
						title: "El banco ha sido cambiado correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
						    if (result.value) {
								window.location = "bancos";

								}
							})
					</script>';
				}
			}else{
				echo'<script>
					swal({
						  type: "error",
						  title: "¡El banco no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "bancos";
							}
						})
			  	</script>';
			}
        }
    }

  /**=====================================
  BORRAR BANCOS
  ======================================**/
  static public function ctrBorrarBanco(){
  	if (isset($_GET["idBanco"])){
	  		$tabla = "bancos";
	  		$datos = $_GET["idBanco"];
	  		$respuesta = ModelBancos::mdlBorrarBanco($tabla, $datos);
	  		if($respuesta == "ok"){
	  			echo'<script>
						swal({
							  type: "success",
							  title: "El banco ha sido borrado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {
										window.location = "bancos";
										}
									})
						</script>';
  			}
  		}
  	}
}