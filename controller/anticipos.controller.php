<?php


class ControllerAnticipos{

	/*=============================================
	CREAR ANTICIPO
	=============================================*/

	static public function ctrCrearAnticipo(){

		if(isset($_POST["nuevoCodigo"])){

			if(preg_match('/^[0-9]+$/', $_POST["nuevoCodigo"]) &&
			   preg_match('/^[.\0-9]+$/', $_POST["nuevoMonto"]) &&
			   preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoEstatus"])){

			   	$tabla = "anticipos";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
			   	$datos = array(
					"usuario" => $_SESSION["id_usuario"],
					"codigo"=>$_POST["nuevoCodigo"],
					"proveedor" => $_POST["nuevoProveedor"],
					"monto"=>$_POST["nuevoMonto"],
					"saldo"=>$_POST["nuevoSaldo"],
					"fecha"=>$_POST["nuevaFecha"],
					"descripcion"=>$_POST["nuevaDescripcion"],
					"estatus"=>$_POST["nuevoEstatus"],
                    "fecha" => $fecha
				);

			   	$respuesta = ModelAnticipos::mdlIngresarAnticipo($tabla, $datos);

			   	if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "El anticipo ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "anticipos";

									}
								})

					</script>';

				}

			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El anticipo no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "anticipos";

							}
						})

			  	</script>';



			}

		}

	}

	/*=============================================
	MOSTRAR ANTICIPOS
	=============================================*/

	static public function ctrMostrarAnticipos($item, $valor){

		$tabla = "anticipos";

		$respuesta = ModelAnticipos::mdlMostrarAnticipos($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	EDITAR ANTICIPO
	=============================================*/

	static public function ctrEditarAnticipo(){

		if(isset($_POST["editarCodigo"])){

			if(preg_match('/^[0-9]+$/', $_POST["editarCodigo"]) &&
			   preg_match('/^[.\0-9]+$/', $_POST["editarMonto"]) &&
			   preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarEstatus"])){

			   	$tabla = "anticipos";

			   	$datos = array("id_anticipo"=>$_POST["idAnticipo"],
			   				   "codigo"=>$_POST["editarCodigo"],
					           "monto"=>$_POST["editarMonto"],
					           "saldo"=>$_POST["editarSaldo"],
			   				   "fecha"=>$_POST["editarFecha"],
							   "descripcion"=>$_POST["editarDescripcion"],
					           "estatus"=>$_POST["editarEstatus"]);

			   	$respuesta = ModelAnticipos::mdlEditarAnticipo($tabla, $datos);

			   	if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "El anticipo ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "anticipos";

									}
								})

					</script>';

				}

			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El anticipo no puede ir vací o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "anticipos";

							}
						})

			  	</script>';

			}

		}

	}

  /**=====================================
  ELIMINAR ANTICIPO
  ======================================**/

  static public function ctrEliminarAnticipo(){

  	if (isset($_GET["idAnticipo"])){

	  		$tabla = "anticipos";
	  		$datos = $_GET["idAnticipo"];

	  		$respuesta = ModelAnticipos::mdlEliminarAnticipo($tabla, $datos);

	  		if($respuesta == "ok"){

	  			echo'<script>

						swal({
							  type: "success",
							  title: "El anticipo ha sido borrado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar",
							  }).then(function(result){
										if (result.value) {

										window.location = "anticipos";

										}
									})
						</script>';
			}

  		}

  	}

}
