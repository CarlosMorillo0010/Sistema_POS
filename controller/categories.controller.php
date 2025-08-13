<?php

class ControllerCategories{
	/**=============================================
	CREAR CATEGORIAS
	=============================================**/

	static public function ctrCrearCategoria(){
		if(isset($_POST["nuevaCategoria"])){
			if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ. ]+$/', $_POST["nuevaCategoria"])){
                /**=============================================
                VALIDAR IMAGEN CATEGORIA
                =============================================**/
                $ruta = "view/img/categories/default/anonymous.png";

                if (isset($_FILES["nuevaImagen"]["tmp_name"])) {
                    list($ancho, $alto) = getimagesize($_FILES["nuevaImagen"]["tmp_name"]);
                    $nuevoAncho = 500;
                    $nuevoAlto = 500;

                    /**=============================================
                    CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DE LA CATEGORIA
                    =============================================**/
                    $directory = "view/img/categories/".$_POST["nuevaCategoria"];
                    mkdir($directory, 0755);

                    /**=============================================
                    DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
                    =============================================**/
                    if ($_FILES["nuevaImagen"]["type"] == "image/jpeg") {

                        /**=============================================
                        GUARDAMOS LA IMAGEN EN EL DIRECTORIO
                        =============================================**/
                        $aleatory = mt_rand(100, 999);
                        $ruta = "view/img/categories/".$_POST["nuevaCategoria"]."/". $aleatory.".jpg";
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
                        $ruta = "view/img/categories/".$_POST["nuevaCategoria"]."/". $aleatory.".jpg";
                        $origen = imagecreatefrompng($_FILES["nuevaImagen"]["tmp_name"]);
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagepng($destino, $ruta);
                    }
                }

				$tabla = "categorias";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
				$datos = array(
				    "id_usuario" => $_SESSION["id_usuario"],
                    "categoria" => $_POST["nuevaCategoria"],
                    "imagen" => $ruta,
                    "fecha" => $fecha
                );
				$respuesta = ModelCategories::mdlIngresarCategoria($tabla, $datos);
				if($respuesta == "ok"){
					echo'<script>
					swal({
						  type: "success",
						  title: "La categoría ha sido guardada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "categorias";
									}
								})
					</script>';
				}
			}else{
				echo'<script>
					swal({
						  type: "error",
						  title: "¡La categoría no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "categorias";
							}
						})
			  	</script>';
			}
        }
    }
    
	/**=============================================
	MOSTRAR CATEGORIAS
	=============================================**/

	static public function ctrMostrarCategoria($item, $valor){
        $tabla = "categorias";
        $respuesta = ModelCategories::mdlMostrarCategoria($tabla, $item, $valor);
        return $respuesta;
    }

	/**=============================================
	EDITAR CATEGORIA
	=============================================**/

	static public function ctrEditarCategoria(){
		if(isset($_POST["editarCategoria"])){
			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarCategoria"])){
				$tabla = "categorias";
				$datos = array(
					"categoria" => $_POST["editarCategoria"],
					"id_categoria" => $_POST["idCategoria"]);

				$respuesta = ModelCategories::mdlEditarCategoria($tabla, $datos);
				if($respuesta == "ok"){
					echo'<script>
					swal({
						  type: "success",
						  title: "La categoría ha sido cambiada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "categorias";
									}
								})
					</script>';
				}
			}else{
				echo'<script>
					swal({
						  type: "error",
						  title: "¡La categoría no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "categorias";
							}
						})
			  	</script>';
			}
        }  
    }

  /**=====================================
  BORRAR CATEGORIAS
  ======================================**/

  static public function ctrBorrarCategoria(){
  	if (isset($_GET["idCategoria"])){
	  		$tabla = "categorias";
	  		$datos = $_GET["idCategoria"];

	  		$respuesta = ModelCategories::mdlBorrarCategoria($tabla, $datos);
	  		if($respuesta == "ok"){
	  			echo'<script>
						swal({
							  type: "success",
							  title: "La categoría ha sido borrada correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {
										window.location = "categorias";
										}
									})
						</script>';
  			}
  		}
  	}

	/**
     * ===================================================================
     * CONTROLADOR PARA MOSTRAR CATEGORÍAS CON PRODUCTOS
     * ===================================================================
     */
    static public function ctrMostrarCategoriasConProductos()
    {
        $respuesta = ModelCategories::mdlMostrarCategoriasConProductos();
        return $respuesta;
    }
}