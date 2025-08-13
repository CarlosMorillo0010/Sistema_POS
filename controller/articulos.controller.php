<?php

class ControllerArticulos
{

    /**=====================================
     * MOSTRAR ARTICULOS
     * ======================================**/
    static public function ctrMostrarArticulos($items, $valor)
    {
        $tabla = "articulos";
        $respuesta = ModelArticulos::mdlMostrarArticulos($tabla, $items, $valor);
        return $respuesta;
    }

    /**=====================================
     * CREAR ARTICULO
     * ======================================**/
    static public function ctrCrearArticulo()
    {
        if (isset($_POST["nuevoArticulo"])) {
            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoArticulo"]) &&
                preg_match('/^[0-9]+$/', $_POST["nuevaUnidades"]) &&
                preg_match('/^[0-9.]+$/', $_POST["nuevoPrecioCompra"]) &&
                preg_match('/^[0-9.]+$/', $_POST["nuevoPrecioVenta"])) {

                $ruta = "view/img/articulos/default/anonymous.png";
                if (isset($_FILES["imagenArticulo"]["tmp_name"])) {
                    list($ancho, $alto) = getimagesize($_FILES["imagenArticulo"]["tmp_name"]);
                    $nuevoAncho = 500;
                    $nuevoAlto = 500;

                    /**=============================================
                    CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL ARTICULO
                    =============================================**/
                    $directorio = "view/img/articulos/" . $_POST["articulo"];
                    mkdir($directorio, 0755);

                    /**=============================================
                    DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
                    =============================================**/
                    if ($_FILES["imagenArticulo"]["type"] == "image/jpeg") {

                        /**=============================================
                        GUARDAMOS LA IMAGEN EN EL DIRECTORIO
                        =============================================**/
                        $aleatorio = mt_rand(100, 999);
                        $ruta = "view/img/articulos/" . $_POST["articulo"] . "/" . $aleatorio . ".jpg";
                        $origen = imagecreatefromjpeg($_FILES["imagenArticulo"]["tmp_name"]);
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagejpeg($destino, $ruta);
                    }

                    if ($_FILES["imagenArticulo"]["type"] == "image/png") {

                        /**=============================================
                        GUARDAMOS LA IMAGEN EN EL DIRECTORIO
                        =============================================**/

                        $aleatorio = mt_rand(100, 999);
                        $ruta = "view/img/articulos/" . $_POST["articulo"] . "/" . $aleatorio . ".png";
                        $origen = imagecreatefrompng($_FILES["imagenArticulo"]["tmp_name"]);
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagepng($destino, $ruta);
                    }
                }

                $tabla = "articulos";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "usuario" => $_SESSION["id_usuario"],
                    "id" => $_POST["nuevaMarca"],
                    "articulo" => $_POST["nuevoArticulo"],
                    "unidades" => $_POST["nuevaUnidades"],
                    "precio_compra" => $_POST["nuevoPrecioCompra"],
                    "precio_venta" => $_POST["nuevoPrecioVenta"],
                    "fecha" => $fecha,
                    "imagen" => $ruta
                );
                $respuesta = ModelArticulos::mdlIngresarArticulo($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>
                    swal({
                        type: "success",
                        title: "¡El articulo ha sido guardado correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "articulos";
                            }
                        });
                    </script>';
                }
            } else {
                echo '<script>
                    swal({
                        type: "error",
                        title: "¡El articulo no puede ir con los campos vacios o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "articulos";
                            }
                        });
                </script>';
            }
        }
    }

    /**=============================================
        EDITAR ARTICULO
    =============================================**/
    static public function ctrEditarArticulo()
    {

        if (isset($_POST["editarArticulo"])) {
            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarArticulo"]) &&
                preg_match('/^[0-9]+$/', $_POST["editarUnidades"]) &&
                preg_match('/^[0-9.]+$/', $_POST["editarPrecioCompra"]) &&
                preg_match('/^[0-9.]+$/', $_POST["editarPrecioVenta"])) {

                /*=============================================
                    VALIDAR IMAGEN
                =============================================*/
                $ruta = $_POST["imagenActual"];
                if (isset($_FILES["editArticulo"]["tmp_name"]) && !empty($_FILES["editArticulo"]["tmp_name"])) {
                    list($ancho, $alto) = getimagesize($_FILES["editArticulo"]["tmp_name"]);

                    $nuevoAncho = 500;
                    $nuevoAlto = 500;

                    /*=============================================
                    CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
                    =============================================*/
                    $directorio = "view/img/articulos/" . $_POST["editarArticulo"];

                    /*=============================================
                    PRIMERO PREGUNTAMOS SI EXISTE OTRA IMAGEN EN LA BD
                    =============================================*/
                    if (!empty($_POST["imagenActual"]) && $_POST["imagenActual"] != "view/img/articulos/default/anonymous.png") {
                        unlink($_POST["imagenActual"]);
                    } else {
                        mkdir($directorio, 0755);
                    }

                    /*=============================================
                    DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
                    =============================================*/
                    if ($_FILES["editArticulo"]["type"] == "image/jpeg") {

                        /*=============================================
                        GUARDAMOS LA IMAGEN EN EL DIRECTORIO
                        =============================================*/
                        $aleatorio = mt_rand(100, 999);
                        $ruta = "view/img/articulos/" . $_POST["editarArticulo"] . "/" . $aleatorio . ".jpg";
                        $origen = imagecreatefromjpeg($_FILES["editArticulo"]["tmp_name"]);
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagejpeg($destino, $ruta);
                    }

                    if ($_FILES["editArticulo"]["type"] == "image/png") {
                        /*=============================================
                        GUARDAMOS LA IMAGEN EN EL DIRECTORIO
                        =============================================*/
                        $aleatorio = mt_rand(100, 999);
                        $ruta = "view/img/articulos/" . $_POST["editarArticulo"] . "/" . $aleatorio . ".png";
                        $origen = imagecreatefrompng($_FILES["editArticulo"]["tmp_name"]);
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagepng($destino, $ruta);
                    }
                }

                $tabla = "articulos";
                $datos = array(
                    "id" => $_POST["editarMarca"],
                    "articulo" => $_POST["editarArticulo"],
                    "unidades" => $_POST["editarUnidades"],
                    "precio_compra" => $_POST["editarPrecioCompra"],
                    "precio_venta" => $_POST["editarPrecioVenta"],
                    "imagen" => $ruta
                );
                $respuesta = ModelArticulos::mdlEditarArticulo($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
						swal({
							  type: "success",
							  title: "El articulo ha sido editado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {
										window.location = "articulos";
										}
									})
						</script>';
                } else {
                    echo '<script>
					swal({
						  type: "error",
						  title: "¡El articulo no puede ir con los campos vacíos o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "articulos";
							}
						})
			  	</script>';
                }
            }
        }
    }

    /*=============================================
        ELIMINAR ARTICULO
    =============================================*/
    static public function ctrEliminarArticulo(){
        if(isset($_GET["idArticulo"])){
            $tabla ="articulos";
            $datos = $_GET["idArticulo"];
            if($_GET["imagen"] != "" && $_GET["imagen"] != "view/img/articulos/default/anonymous.png"){
                unlink($_GET["imagen"]);
                rmdir('view/img/articulos/'.$_GET["articulo"]);
            }
            $respuesta = ModelArticulos::mdlEliminarArticulo($tabla, $datos);
            if($respuesta == "ok"){
                echo'<script>

				swal({
					  type: "success",
					  title: "El articulo ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {
								window.location = "articulos";
								}
							})
				</script>';
            }
        }
    }
}