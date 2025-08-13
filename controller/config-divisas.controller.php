<?php

class ControllerConfiguracionesDivisas
{
    /*======================================
     CREAR CONFIGURACIÓN DIVISAS
    ======================================**/
    static public function ctrCrearConfiguracionDivisa()
    {
        if (isset($_POST["nuevoValorMoneda"])) {
            if (preg_match('/^[0-9,.]+$/', $_POST["nuevoValorMoneda"])) {
                date_default_timezone_set('America/Caracas');
                $tabla = "divisas_valor";
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "divisa" => $_POST["nuevaMoneda"],
                    "simbolo" => $_POST["nuevoSimbolo"],
                    "valor_inventario" => $_POST["nuevoValorMoneda"],
                    "valor_venta" => $_POST["nuevoValorMoneda"],
                    "fecha" => $fecha
                );

                $respuesta = ModelConfiguracionesDivisas::mdlIngresarConfiguracionDivisa($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>
                    swal({
                        type: "success",
                        title: "¡El valor de la divisa ha sido guardado correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "config-divisas";
                            }
                        });
                    </script>';
                }
            } else {
                echo '<script>
                    swal({
                        type: "error",
                        title: "¡El valor de la divisa no puede ir con los campos vacios o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "config-divisas";
                            }
                        });
                </script>';
            }
        }
    }

    /*======================================
     MOSTRAR DIVISAS
    ======================================**/
    static public function ctrMostrarConfiguracionDivisa($item, $valor)
    {
        $tabla = "divisas_valor";
        $respuesta = ModelConfiguracionesDivisas::mdlMostrarConfiguracionDivisa($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
	EDITAR PERFILES
	=============================================*/
    static public function ctrEditarConfiguracionDivisa(){
        if (isset($_POST["editarValorMoneda"])){
            if (preg_match('/^[0-9,.]+$/', $_POST["editarValorMoneda"])){
                $tabla = "divisas_valor";
                $datos = array(
                    "valor_inventario"=>$_POST["editarValorMoneda"],
                    "valor_venta"=>$_POST["editarValorMoneda"],
                    "id_valor"=>$_POST["idValor"]
                );
                $respuesta = ModelConfiguracionesDivisas::mdlEditarConfiguracionDivisa($tabla, $datos);

                if ($respuesta == "ok"){
                    echo'<script>
					swal({
						  type: "success",
						  title: "El valor de la divisa ha sido cambiado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "config-divisas";
									}
								})
					</script>';
                }
            }else{
                echo'<script>
					swal({
						  type: "error",
						  title: "¡El valor de la divisa no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "config-divisas";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
	 BORRAR VALOR DIVISA
	=============================================*/
    static public function ctrBorrarConfiguracionDivisa(){
        if(isset($_GET["idValor"])){
            $tabla = "divisas_valor";
            $datos = $_GET["idValor"];

            $respuesta = ModelConfiguracionesDivisas::mdlBorrarConfiguracionDivisa($tabla, $datos);

            if ($respuesta == "ok"){
                echo'<script>
					swal({
						  type: "success",
						  title: "El valor de la divisa ha sido borrado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "config-divisas";
									}
								})
					</script>';
            }
        }
    }
}