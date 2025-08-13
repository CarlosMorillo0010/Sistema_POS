<?php

class ControllerImpuestos{

    /*=============================================
	CREAR IMPUESTOS
	=============================================*/
    static public function ctrCrearImpuesto(){
        if(isset($_POST["nuevoImpuesto"])){
            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoImpuesto"] &&
                preg_match('/^[0-9.]+$/', $_POST["nuevoPorcentaje"]))){
                $tabla = "impuestos";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "impuesto" => $_POST["nuevoImpuesto"],
                    "tasa" => $_POST["nuevoPorcentaje"],
                    "fecha" => $fecha
                );
                $respuesta = ModelImpuestos::mdlIngresarImpuesto($tabla, $datos);
                if($respuesta == "ok"){
                    echo'<script>
					swal({
						  type: "success",
						  title: "El impuesto ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "impuestos";
									}
								})
					</script>';
                }
            }else{
                echo'<script>
					swal({
						  type: "error",
						  title: "¡El impuesto no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "impuestos";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
    MOSTRAR IMPUESTOS
    =============================================*/

    static public function ctrMostrarImpuesto($item, $valor){
        $tabla = "impuestos";
        $respuesta = ModelImpuestos::mdlMostrarImpuesto($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    EDITAR IMPUESTO
    =============================================*/
    static public function ctrEditarImpuesto(){
        if(isset($_POST["editarImpuesto"])){
            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarImpuesto"] &&
                preg_match('/^[0-9.]+$/', $_POST["editarPorcentaje"]))){
                $tabla = "impuestos";
                $fecha = date('Y-m-d');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "impuesto" => $_POST["editarImpuesto"],
                    "tasa" => $_POST["editarPorcentaje"],
                    "id_impuesto" => $_POST["idImpuesto"],
                    "fecha" => $fecha
                );
                $respuesta = ModelImpuestos::mdlEditarImpuesto($tabla, $datos);
                if($respuesta == "ok"){
                    echo'<script>
					swal({
						  type: "success",
						  title: "El impuesto ha sido cambiado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "impuestos";
									}
								})
					</script>';
                }
            }else{
                echo'<script>
					swal({
						  type: "error",
						  title: "¡El impuesto no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "impuestos";
							}
						})
			  	</script>';
            }
        }
    }

    /**=====================================
    BORRAR IMPUESTOS
    ======================================**/
    static public function ctrBorrarImpuesto(){
        if (isset($_GET["idImpuesto"])){
            $tabla = "impuestos";
            $datos = $_GET["idImpuesto"];

            $respuesta = ModelImpuestos::mdlBorrarImpuesto($tabla, $datos);

            if($respuesta == "ok"){
                echo'<script>
					swal({
					    type: "success",
						title: "El impuesto ha sido borrado correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
						    if (result.value) {
							window.location = "impuestos";
						}
					})
				</script>';
            }
        }
    }
}