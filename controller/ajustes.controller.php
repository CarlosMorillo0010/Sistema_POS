<?php

class ControllerAjustes
{
    /**
     * ===================================================================
     * MOSTRAR AJUSTES (Para la tabla principal)
     * ===================================================================
     */
    static public function ctrMostrarAjustes($item, $valor)
    {
        $tabla = "ajustes_inventario";
        $respuesta = ModelAjustes::mdlMostrarAjustes($tabla, $item, $valor);
        return $respuesta;
    }

    /**
     * ===================================================================
     * CREAR AJUSTE DE INVENTARIO
     * ===================================================================
     */
    static public function ctrCrearAjusteInventario()
    {
        if (isset($_POST["nuevoAlmacen"])) {

            // 1. Validar que la lista de productos no esté vacía
            if (!isset($_POST["listaProductos"]) || empty($_POST["listaProductos"])) {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "¡Error!",
                        text: "Debe agregar al menos un producto al ajuste."
                    });
                </script>';
                return;
            }

            // 2. Decodificar la lista de productos que viene en formato JSON desde el formulario
            $listaProductos = json_decode($_POST["listaProductos"], true);
            
            // 3. Preparar datos para el encabezado del ajuste
            $tablaAjustes = "ajustes_inventario";
            $datosAjuste = array(
                "id_almacen" => $_POST["nuevoAlmacen"],
                "id_usuario" => $_SESSION["id_usuario"], // Asumiendo que el ID del usuario está en la sesión
                "fecha_ajuste" => $_POST["nuevaFecha"],
                "descripcion" => $_POST["nuevaDescripcion"]
            );

            // 4. Iniciar la transacción
            $pdo = Connection::connect();
            $pdo->beginTransaction();

            try {
                // 5. Guardar el encabezado y obtener el ID del nuevo ajuste
                $idAjuste = ModelAjustes::mdlIngresarAjuste($tablaAjustes, $datosAjuste, $pdo);

                if ($idAjuste > 0) {
                    // 6. Recorrer cada producto y guardar su detalle
                    $tablaDetalles = "ajustes_inventario_detalle";
                    $tablaProductos = "productos";

                    foreach ($listaProductos as $key => $value) {
                        
                        $datosDetalle = array(
                            "id_ajuste" => $idAjuste,
                            "id_producto" => $value["idProducto"],
                            "tipo_ajuste" => $value["tipoAjuste"],
                            "cantidad_anterior" => $value["stockActual"],
                            "cantidad_ajustada" => $value["cantidad"],
                            "cantidad_final" => $value["stockFinal"]
                        );

                        // Guardar el detalle del ajuste
                        ModelAjustes::mdlIngresarDetalleAjuste($tablaDetalles, $datosDetalle, $pdo);

                        // Actualizar el stock del producto
                        ModelProducts::mdlActualizarProductoConexion(
                            $pdo,
                            $tablaProductos,
                            "stock", // item1: columna a actualizar
                            $value["stockFinal"], // valor1: nuevo valor
                            "id_producto", // item2: columna para el WHERE
                            $value["idProducto"] // valor2: id del producto
                        );
                    }

                    // 7. Si todo salió bien, confirmar la transacción
                    $pdo->commit();

                    echo '<script>
                        Swal.fire({
                            icon: "success",
                            title: "¡El ajuste ha sido guardado correctamente!",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                        }).then((result) => {
                            if (result.value) {
                                window.location = "ajuste-inventario"; // Redirige a la página de ajustes
                            }
                        });
                    </script>';

                } else {
                    // Si no se pudo crear el encabezado, revertir
                    $pdo->rollBack();
                    throw new Exception("No se pudo crear el registro principal del ajuste.");
                }

            } catch (Exception $e) {
                // 8. Si algo falló, revertir todos los cambios
                $pdo->rollBack();
                
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "¡Error al guardar!",
                        text: "Ocurrió un error y el ajuste no pudo ser procesado. Detalles: ' . addslashes($e->getMessage()) . '"
                    });
                </script>';
            }
        }
    }

    /**
     * ===================================================================
     * MOSTRAR DETALLES DE UN AJUSTE ESPECÍFICO (Controlador)
     * ===================================================================
     */
    static public function ctrMostrarDetallesAjuste($item, $valor)
    {
        $tabla = "ajustes_inventario_detalle";
        $respuesta = ModelAjustes::mdlMostrarDetallesAjuste($tabla, $item, $valor);
        return $respuesta;
    }
}