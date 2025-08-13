<?php

class ControllerProducts
{
    static public function ctrMostrarProductos($item, $valor, $orden)
    {
        $tabla = "productos";
        $respuesta = ModelProducts::mdlMostrarProductos($tabla, $item, $valor, $orden);
        return $respuesta;
    }

    static public function ctrMostrarProductosCaja($item, $valor, $orden)
    {
        $tabla = "productos";
        $respuesta = ModelProducts::mdlMostrarProductosCaja($tabla, $item, $valor, $orden);
        return $respuesta;
    }

    static public function ctrCrearProducto()
    {
        if (isset($_POST["nuevoCodigo"])) {

            if (empty(trim($_POST["nuevoCodigo"])) || empty(trim($_POST["nuevaDescripcion"])) || !isset($_POST["nuevoPrecioCosto"]) || !isset($_POST["nuevoPorcentajeGanancia"])) {
                echo '<script> swal({ type: "error", title: "¡Error!", text: "Los campos con (*) son obligatorios."}); </script>';
                return;
            }

            $ruta = "view/img/products/default/anonymous.png";
            if (isset($_FILES["imagenProducto"]["tmp_name"]) && !empty($_FILES["imagenProducto"]["tmp_name"])) {
                list($ancho, $alto) = getimagesize($_FILES["imagenProducto"]["tmp_name"]);
                $nuevoAncho = 500; $nuevoAlto = 500;
                $directorio = "view/img/products/" . htmlspecialchars($_POST["nuevoCodigo"]);
                if (!is_dir($directorio)) mkdir($directorio, 0755, true);
                $aleatorio = mt_rand(100, 999);
                if ($_FILES["imagenProducto"]["type"] == "image/jpeg") {
                    $ruta = $directorio . "/" . $aleatorio . ".jpg";
                    $origen = imagecreatefromjpeg($_FILES["imagenProducto"]["tmp_name"]);
                    $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                    imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagejpeg($destino, $ruta);
                } else if ($_FILES["imagenProducto"]["type"] == "image/png") {
                    $ruta = $directorio . "/" . $aleatorio . ".png";
                    $origen = imagecreatefrompng($_FILES["imagenProducto"]["tmp_name"]);
                    $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                    imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagepng($destino, $ruta);
                }
            }

            $tasaBCV = (float) ($_SESSION['config']['tasa_bcv'] ?? 1);
            $ivaPorcentajeGeneral = (float) ($_SESSION['config']['iva_porcentaje'] ?? 16);

            $costoEntrada = (float) str_replace([".", ","], ["", "."], $_POST["nuevoPrecioCosto"]);
            $porcentajeGanancia = (float) str_replace([".", ","], ["", "."], $_POST["nuevoPorcentajeGanancia"]);
            $monedaEntrada = $_POST["monedaEntrada"];
            
            $costoEnVes = ($monedaEntrada === 'USD' && $tasaBCV > 0) ? $costoEntrada * $tasaBCV : $costoEntrada;
            $costoEnUsd = ($monedaEntrada === 'VES' && $tasaBCV > 0) ? $costoEntrada / $tasaBCV : (($monedaEntrada === 'USD') ? $costoEntrada : 0);

            $precioVentaBase = $costoEnVes * (1 + ($porcentajeGanancia / 100));
            
            $esGravado = ($_POST["nuevoTipoIva"] === 'gravado');
            $idImpuesto = $esGravado ? 1 : 0; // Asume 1 = IVA General, 0 = Exento
            $ivaAplicable = $esGravado ? $ivaPorcentajeGeneral : 0;
            $montoIva = $precioVentaBase * ($ivaAplicable / 100);

            $pvpEnVes = $precioVentaBase + $montoIva;
            $pvpEnUsd = ($tasaBCV > 0) ? $pvpEnVes / $tasaBCV : 0;
            
            $tabla = "productos";
            $datos = array(
                "id_usuario" => $_SESSION["id_usuario"],
                "id_categoria" => (int)$_POST["nuevaCategoria"],
                "codigo" => htmlspecialchars(trim($_POST["nuevoCodigo"])),
                "marca" => htmlspecialchars(trim($_POST["nuevaMarca"])),
                "modelo" => htmlspecialchars(trim($_POST["nuevoModelo"])),
                "ano" => htmlspecialchars(trim($_POST["nuevoAno"])),
                "descripcion" => htmlspecialchars(trim($_POST["nuevaDescripcion"])),
                "stock" => (int)$_POST["nuevoStock"],
                "ubicacion" => htmlspecialchars(trim($_POST["nuevaUbicacion"])),
                "imagen" => $ruta,
                "moneda_costo" => $monedaEntrada,
                "precio_costo" => $costoEnVes,
                "costo_referencia" => $costoEnUsd,
                "porcentaje_ganancia" => $porcentajeGanancia,
                "precio_venta_base" => $precioVentaBase,
                "id_impuesto" => $idImpuesto,
                "monto_iva" => $montoIva,
                "pvp" => $pvpEnVes,
                "pvp_referencia" => $pvpEnUsd,
                "feregistro" => date("Y-m-d H:i:s")
            );

            $respuesta = ModelProducts::mdlIngresarProducto($tabla, $datos);

            if ($respuesta == "ok") {
                echo '<script> swal({ type: "success", title: "¡Producto guardado!", showConfirmButton: true, confirmButtonText: "Cerrar" }).then(r => { if(r.value){ window.location = "productos"; } }); </script>';
            } else {
                 echo '<script> swal({ type: "error", title: "¡Error al guardar!", text: "' . addslashes($respuesta) . '" }); </script>';
            }
        }
    }

    /**
     * ===================================================================
     * EDITAR PRODUCTO (VERSIÓN CORREGIDA Y ROBUSTA)
     * ===================================================================
     */
    static public function ctrEditarProducto()
    {
        // El punto de entrada es el ID del producto que viene del formulario de edición
        if (isset($_POST["idProducto"])) {

            // 1. VALIDACIÓN DE CAMPOS OBLIGATORIOS DEL FORMULARIO DE EDICIÓN
            if (empty(trim($_POST["editarDescripcion"])) || 
                !isset($_POST["editarPrecioCosto"]) ||
                !isset($_POST["editarPorcentajeGanancia"])) {
                
                echo '<script> swal({ type: "error", title: "¡Error!", text: "Los campos de descripción y precios son obligatorios."}); </script>';
                return;
            }

            // 2. MANEJO DE LA IMAGEN (Idéntico a la función de crear, pero con "editarImagen")
            $ruta = $_POST["imagenActual"];
            if (isset($_FILES["editarImagen"]["tmp_name"]) && !empty($_FILES["editarImagen"]["tmp_name"])) {
                list($ancho, $alto) = getimagesize($_FILES["editarImagen"]["tmp_name"]);
                $nuevoAncho = 500; $nuevoAlto = 500;
                $directorio = "view/img/products/" . htmlspecialchars($_POST["editarCodigo"]);

                if (!empty($_POST["imagenActual"]) && $_POST["imagenActual"] != "view/img/products/default/anonymous.png") {
                    if(file_exists($_POST["imagenActual"])) unlink($_POST["imagenActual"]);
                }
                if(!is_dir($directorio)) mkdir($directorio, 0755, true);

                $aleatorio = mt_rand(100, 999);
                if ($_FILES["editarImagen"]["type"] == "image/jpeg") {
                    $ruta = $directorio . "/" . $aleatorio . ".jpg";
                    $origen = imagecreatefromjpeg($_FILES["editarImagen"]["tmp_name"]);
                    $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                    imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagejpeg($destino, $ruta);
                } else if ($_FILES["editarImagen"]["type"] == "image/png") {
                    $ruta = $directorio . "/" . $aleatorio . ".png";
                    $origen = imagecreatefrompng($_FILES["editarImagen"]["tmp_name"]);
                    $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                    imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagepng($destino, $ruta);
                }
            }

            // 3. CÁLCULOS SEGUROS EN EL BACKEND (LA PARTE MÁS IMPORTANTE)
            // Leemos las variables de sesión y los datos que vienen del formulario con prefijo "editar"
            $tasaBCV = (float) ($_SESSION['config']['tasa_bcv'] ?? 1);
            $ivaPorcentajeGeneral = (float) ($_SESSION['config']['iva_porcentaje'] ?? 16);

            // <-- CORRECCIÓN CLAVE: Usamos los campos del formulario de EDICIÓN
            $costoEntrada = (float) str_replace([".", ","], ["", "."], $_POST["editarPrecioCosto"]);
            $porcentajeGanancia = (float) str_replace([".", ","], ["", "."], $_POST["editarPorcentajeGanancia"]);
            $monedaEntrada = $_POST["monedaEntrada"]; // Este viene del input hidden que maneja el JS

            // Los cálculos son idénticos a los de "crear", pero con las variables de "editar"
            $costoEnVes = ($monedaEntrada === 'USD' && $tasaBCV > 0) ? $costoEntrada * $tasaBCV : $costoEntrada;
            $costoEnUsd = ($monedaEntrada === 'VES' && $tasaBCV > 0) ? $costoEntrada / $tasaBCV : (($monedaEntrada === 'USD') ? $costoEntrada : 0);
            
            $precioVentaBase = $costoEnVes * (1 + ($porcentajeGanancia / 100));
            
            $esGravado = ($_POST["editarTipoIva"] === 'gravado');
            $idImpuesto = $esGravado ? 1 : 0;
            $ivaAplicable = $esGravado ? $ivaPorcentajeGeneral : 0;
            $montoIva = $precioVentaBase * ($ivaAplicable / 100);
            
            $pvpEnVes = $precioVentaBase + $montoIva;
            $pvpEnUsd = ($tasaBCV > 0) ? $pvpEnVes / $tasaBCV : 0;

            // 4. PREPARAR EL ARRAY DE DATOS PARA ENVIAR AL MODELO
            $tabla = "productos";
            $datos = array(
                "id_producto" => (int)$_POST["idProducto"], // El ID para el WHERE de la consulta
                "id_categoria" => (int)$_POST["editarCategoria"],
                "codigo" => htmlspecialchars(trim($_POST["editarCodigo"])),
                "descripcion" => htmlspecialchars(trim($_POST["editarDescripcion"])),
                "marca" => htmlspecialchars(trim($_POST["editarMarca"])),
                "modelo" => htmlspecialchars(trim($_POST["editarModelo"])),
                "ano" => htmlspecialchars(trim($_POST["editarAno"])),
                "stock" => (int)$_POST["editarStock"],
                "ubicacion" => htmlspecialchars(trim($_POST["editarUbicacion"])),
                "imagen" => $ruta,
                // --- Aquí van los datos de precios recién calculados ---
                "moneda_costo" => $monedaEntrada,
                "precio_costo" => $costoEnVes,
                "costo_referencia" => $costoEnUsd,
                "porcentaje_ganancia" => $porcentajeGanancia,
                "precio_venta_base" => $precioVentaBase,
                "id_impuesto" => $idImpuesto,
                "monto_iva" => $montoIva,
                "pvp" => $pvpEnVes,
                "pvp_referencia" => $pvpEnUsd
            );

            // 5. ENVIAR A LA BASE DE DATOS
            $respuesta = ModelProducts::mdlEditarProducto($tabla, $datos);

            if ($respuesta == "ok") {
                echo '<script> swal({ type: "success", title: "¡Producto modificado!", showConfirmButton: true, confirmButtonText: "Cerrar" }).then(r => { if(r.value){ window.location = "productos"; } }); </script>';
            } else {
                echo '<script> swal({ type: "error", title: "¡Error al actualizar!", text: "No se pudo modificar el producto. Detalles: ' . addslashes($respuesta) . '" }); </script>';
            }
        }
    }


    static public function ctrEliminarProducto()
    {
        if (isset($_GET["idProducto"])) {
            $tabla = "productos";
            $datos = $_GET["idProducto"];

            if (isset($_GET["imagen"]) && $_GET["imagen"] != "" && $_GET["imagen"] != "view/img/products/default/anonymous.png") {
                if(file_exists($_GET["imagen"])) unlink($_GET["imagen"]);
                $directorio = 'view/img/products/' . $_GET["codigo"];
                if (is_dir($directorio) && count(scandir($directorio)) == 2) rmdir($directorio);
            }

            $respuesta = ModelProducts::mdlEliminarProducto($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script> swal({ type: "success", title: "¡Producto borrado!", showConfirmButton: true, confirmButtonText: "Cerrar" }).then(r => { if (r.value) { window.location = "productos"; } }); </script>';
            }
        }
    }
}