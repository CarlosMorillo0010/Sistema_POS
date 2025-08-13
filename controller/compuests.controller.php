<?php

class ControllerCompuestos
{

    /*======================================
     MOSTRAR PRODUCTOS COMPUESTOS
    ======================================**/
    static public function ctrMostrarCompuesto($items, $valor)
    {
        $tabla = "productos_compuestos";
        $respuesta = ModelCompuestos::mdlMostrarCompuesto($tabla, $items, $valor);
        return $respuesta;
    }

    /*======================================
     CREAR PRODUCTOS COMPUESTOS
    ======================================**/
    static public function ctrCrearCompuesto()
    {
        if (isset($_POST["nuevaDescripcion"])) {
            if (preg_match('/^[0-9]+$/', $_POST["nuevoCodigo"]) &&
                preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ.+ ]+$/', $_POST["nuevaDescripcion"]) &&
                preg_match('/^[0-9]+$/', $_POST["nuevoStock"]) &&
                preg_match('/^[0-9.,]+$/', $_POST["nuevoPrecioUnitario"]) &&
                preg_match('/^[0-9.,]+$/', $_POST["nuevoPrecioOferta"])) {

                $ruta = "view/img/products-compuests/default/anonymous.png";
                if (isset($_FILES["imagenCompuesto"]["tmp_name"])) {
                    list($ancho, $alto) = getimagesize($_FILES["imagenCompuesto"]["tmp_name"]);
                    $nuevoAncho = 500;
                    $nuevoAlto = 500;
                    /*==============================================
                     CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL PRODUCTO
                    =============================================**/
                    $directorio = "view/img/products/" . $_POST["nuevoCodigo"];
                    mkdir($directorio, 0755);
                    /*==============================================
                     DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
                    =============================================**/
                    if ($_FILES["imagenCompuesto"]["type"] == "image/jpeg") {
                        /*==============================================
                         GUARDAMOS LA IMAGEN EN EL DIRECTORIO
                        =============================================**/
                        $aleatorio = mt_rand(100, 999);
                        $ruta = "view/img/products/" . $_POST["nuevoCodigo"] . "/" . $aleatorio . ".jpg";
                        $origen = imagecreatefromjpeg($_FILES["imagenCompuesto"]["tmp_name"]);
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagejpeg($destino, $ruta);
                    }

                    if ($_FILES["imagenCompuesto"]["type"] == "image/png") {
                        /*==============================================
                         GUARDAMOS LA IMAGEN EN EL DIRECTORIO
                        =============================================**/
                        $aleatorio = mt_rand(100, 999);
                        $ruta = "view/img/products/" . $_POST["nuevoCodigo"] . "/" . $aleatorio . ".png";
                        $origen = imagecreatefrompng($_FILES["imagenCompuesto"]["tmp_name"]);
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagepng($destino, $ruta);
                    }
                }

                if ($_POST["listaCompuestos"] == "") {
                    echo '<script>
                            swal({
                                  type: "error",
                                  title: "No se puede guardar si no hay productos en la tabla",
                                  showConfirmButton: true,
                                  confirmButtonText: "Cerrar"
                                  }).then(function(result){
                                            if (result.value) {
                                            window.location = "producto-compuesto";
                                            }
                                        })
                           </script>';
                    return;
                }

                $listaCompuestos = json_decode($_POST["listaCompuestos"], true);

                foreach ($listaCompuestos as $key => $value) {
                    $tablaProductos = "productos";
                    $item = "descripcion";
                    $valor = $value["descripcion"];
                    $orden = "codigo";
                    $traerProducto = ModelProducts::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);
                    $item1a = "productos_usados";
                    $valor1a = $value["cantidad"] + $traerProducto["productos_usados"];
                    $nuevoProducto = ModelProducts::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);
                    $item1b = "stock";
                    $valor1b = $value["stock"];
                    $nuevoStock = ModelProducts::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);
                }
                date_default_timezone_set('America/Caracas');
                $tabla = "productos_compuestos";
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "usuario" => $_SESSION["id_usuario"],
                    "id" => $_POST["nuevaCategoria"],
                    "codigo" => $_POST["nuevoCodigo"],
                    "servicio" => $_POST["nuevoServicio"],
                    "descripcion" => $_POST["nuevaDescripcion"],
                    "stock" => $_POST["nuevoStock"],
                    "unidad" => $_POST["nuevaUnidad"],
                    "precio_unitario" => $_POST["nuevoPrecioUnitario"],
                    "precio_unitario_total" => $_POST["nuevoPrecioVenta"],
                    "precio_oferta" => $_POST["nuevoPrecioOferta"],
                    "precio_oferta_total" => $_POST["nuevoPrecioVentaOferta"],
                    "productos" => $_POST["listaCompuestos"],
                    "imagen" => $ruta,
                    "fecha" => $fecha
                );
                $respuesta = ModelCompuestos::mdlIngresarCompuesto($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>
                    swal({
                        type: "success",
                        title: "¡El Producto Compuesto ha sido guardado correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "producto-compuesto";
                            }
                        });
                    </script>';
                }
            } else {
                echo '<script>
                    swal({
                        type: "error",
                        title: "¡El Producto no puede ir con los campos vacios o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "producto-compuesto";
                            }
                        });
                </script>';
            }
        }
    }
    /*==============================================
     EDITAR PRODUCTO COMPUESTO
    =============================================**/
    static public function ctrEditarProductoCompuesto()
    {
        if (isset($_POST["editarDescripcion"])) {
            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarDescripcion"]) &&
                preg_match('/^[0-9]+$/', $_POST["editarStock"]) &&
                preg_match('/^[0-9.,]+$/', $_POST["editarPrecioUnitario"]) &&
                preg_match('/^[0-9.,]+$/', $_POST["editarPrecioOferta"])) {

                /*=============================================
                 VALIDAR IMAGEN
                =============================================*/
                $ruta = $_POST["imagenActual"];
                if (isset($_FILES["editProducto"]["tmp_name"]) && !empty($_FILES["editProducto"]["tmp_name"])) {
                    list($ancho, $alto) = getimagesize($_FILES["editProducto"]["tmp_name"]);
                    $nuevoAncho = 500;
                    $nuevoAlto = 500;
                    /*=============================================
                     CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
                    =============================================*/
                    $directorio = "view/img/products/" . $_POST["editarCodigo"];
                    /*=============================================
                     PRIMERO PREGUNTAMOS SI EXISTE OTRA IMAGEN EN LA BD
                    =============================================*/
                    if (!empty($_POST["imagenActual"]) && $_POST["imagenActual"] != "view/img/products/default/anonymous.png") {
                        unlink($_POST["imagenActual"]);
                    } else {
                        mkdir($directorio, 0755);
                    }
                    /*=============================================
                     DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
                    =============================================*/
                    if ($_FILES["editProducto"]["type"] == "image/jpeg") {
                        /*=============================================
                         GUARDAMOS LA IMAGEN EN EL DIRECTORIO
                        =============================================*/
                        $aleatorio = mt_rand(100, 999);
                        $ruta = "view/img/products/" . $_POST["editarCodigo"] . "/" . $aleatorio . ".jpg";
                        $origen = imagecreatefromjpeg($_FILES["editProducto"]["tmp_name"]);
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagejpeg($destino, $ruta);
                    }
                    if ($_FILES["editProducto"]["type"] == "image/png") {
                        /*=============================================
                         GUARDAMOS LA IMAGEN EN EL DIRECTORIO
                        =============================================*/
                        $aleatorio = mt_rand(100, 999);
                        $ruta = "view/img/products/" . $_POST["editarCodigo"] . "/" . $aleatorio . ".png";
                        $origen = imagecreatefrompng($_FILES["editProducto"]["tmp_name"]);
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagepng($destino, $ruta);
                    }
                }

                $tabla = "productos_compuestos";
                $datos = array(
                    "id" => $_POST["editarCategoria"],
                    "codigo" => $_POST["editarCodigo"],
                    "servicio" => $_POST["editarServicio"],
                    "descripcion" => $_POST["editarDescripcion"],
                    "stock" => $_POST["editarStock"],
                    "unidad" => $_POST["editarUnidad"],
                    "precio_unitario" => $_POST["editarPrecioUnitario"],
                    "precio_unitario_total" => $_POST["editarPrecioVenta"],
                    "precio_oferta" => $_POST["editarPrecioOferta"],
                    "precio_oferta_total" => $_POST["editarPrecioVentaOferta"],
                    "imagen" => $ruta
                );
                $respuesta = ModelCompuestos::mdlEditarCompuesto($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
						swal({
							  type: "success",
							  title: "El producto Compuesto ha sido editado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {
										window.location = "producto-compuesto";
										}
									})
						</script>';
                } else {
                    echo '<script>
					swal({
						  type: "error",
						  title: "¡El producto Compuesto no puede ir con los campos vacíos o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "producto-compuesto";
							}
						})
			  	</script>';
                }
            }
        }
    }
    /*=============================================
     BORRAR PRODUCTO COMPUESTO
    =============================================*/
    static public function ctrEliminarProductoCompuesto()
    {
        if (isset($_GET["idCompuesto"])) {
            $tabla = "productos_compuestos";
            $datos = $_GET["idCompuesto"];
            if ($_GET["imagen"] != "" && $_GET["imagen"] != "view/img/products/default/anonymous.png") {
                unlink($_GET["imagen"]);
                rmdir('view/img/products/' . $_GET["codigo"]);
            }
            $respuesta = ModelCompuestos::mdlEliminarCompuesto($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>

				swal({
					  type: "success",
					  title: "El producto ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {
								window.location = "producto-compuesto";
								}
							})
				</script>';
            }
        }
    }
}