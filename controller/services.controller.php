<?php

class ControllerServices
{
    /**=====================================
     * CREAR SERVICIOS
     * ======================================**/
    static public function ctrCrearServicio()
    {
        if (isset($_POST["nuevoServicio"])) {
            if (preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoServicio"])) {
                /**=============================================
                VALIDAR IMAGEN CATEGORIA
                =============================================**/
                $ruta = "view/img/services/default/anonymous.png";

                if (isset($_FILES["nuevaImagen"]["tmp_name"])) {
                    list($ancho, $alto) = getimagesize($_FILES["nuevaImagen"]["tmp_name"]);
                    $nuevoAncho = 500;
                    $nuevoAlto = 500;

                    /**=============================================
                    CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DE LA CATEGORIA
                    =============================================**/
                    $directory = "view/img/services/".$_POST["nuevoServicio"];
                    mkdir($directory, 0755);

                    /**=============================================
                    DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
                    =============================================**/
                    if ($_FILES["nuevaImagen"]["type"] == "image/jpeg") {

                        /**=============================================
                        GUARDAMOS LA IMAGEN EN EL DIRECTORIO
                        =============================================**/
                        $aleatory = mt_rand(100, 999);
                        $ruta = "view/img/services/".$_POST["nuevoServicio"]."/". $aleatory.".jpg";
                        $origen = imagecreatefromjpeg($_FILES["nuevaImagen"]["tmp_name"]);
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagejpeg($destino, $ruta);
                    }

                    if ($_FILES["nuevaImagen"]["type"] == "image/png") {

                        /**=============================================
                        GUARDAMOS LA IMAGEN EN EL DIRECTORIO
                        =============================================**/

                        $aleatory = mt_rand(100, 999);
                        $ruta = "view/img/services/".$_POST["nuevoServicio"]."/". $aleatory.".jpg";
                        $origen = imagecreatefrompng($_FILES["nuevaImagen"]["tmp_name"]);
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagepng($destino, $ruta);
                    }
                }
                $tabla = "servicios";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "servicio" => $_POST["nuevoServicio"],
                    "imagen" => $ruta,
                    "fecha" => $fecha
                );
                $respuesta = ModelServicies::mdlIngresarServicio($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
					swal({
						  type: "success",
						  title: "El servicio ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "servicios";
									}
								})
					</script>';
                }
            } else {
                echo '<script>
					swal({
						  type: "error",
						  title: "¡El servicio no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "servicios";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
	MOSTRAR SERVICIOS
	=============================================*/
    static public function ctrMostrarServicio($item, $valor)
    {
        $tabla = "servicios";
        $respuesta = ModelServicies::mdlMostrarServicio($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    EDITAR SERVICIOS
    =============================================*/
    static public function ctrEditarServicio()
    {
        if (isset($_POST["editarServicio"])) {
            if (preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarServicio"])) {
                $tabla = "servicios";
                $datos = array(
                    "servicio" => $_POST["editarServicio"],
                    "id_servicio" => $_POST["idServicio"]);

                $respuesta = ModelServicies::mdlEditarServicio($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
					swal({
						  type: "success",
						  title: "El servicio ha sido cambiado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "servicios";
									}
								})
					</script>';
                }
            } else {
                echo '<script>
					swal({
						  type: "error",
						  title: "¡El servicio no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "servicios";
							}
						})
			  	</script>';
            }
        }
    }

    /**=====================================
     * BORRAR SERVICIOS
     * ======================================**/
    static public function ctrBorrarServicio()
    {
        if (isset($_GET["idServicio"])) {
            $tabla = "servicios";
            $datos = $_GET["idServicio"];

            $respuesta = ModelServicies::mdlBorrarServicio($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>
						swal({
							  type: "success",
							  title: "El servicio ha sido borrado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {
										window.location = "servicios";
										}
									})
						</script>';
            }
        }
    }
}