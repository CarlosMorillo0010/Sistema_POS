<?php

class ControllerEntradas
{

    /*=============================================
     MOSTRAR ENTRADAS
    =============================================*/
    static public function ctrMostrarEntradas($item, $valor)
    {
        // Lógica para mostrar las entradas de almacén
    }

    /*=============================================
     CREAR ENTRADA DE ALMACÉN
    =============================================*/
    static public function ctrCrearEntrada()
    {
        if (isset($_POST["idOrdenCompra"])) {

            // 1. Recolectar y organizar los datos
            $idOrdenCompra = $_POST["idOrdenCompra"];
            $productosRecibidos = array();
            $montoTotalRecibido = 0;

            if(isset($_POST["cantidad_recibida"])){
                foreach($_POST["cantidad_recibida"] as $id_producto => $cantidad){
                    if($cantidad > 0){
                        $precio_compra = $_POST["precio_compra"][$id_producto];
                        $subtotal = $cantidad * $precio_compra;
                        $productosRecibidos[] = array(
                            "id_producto" => $id_producto,
                            "descripcion" => $_POST["descripcion_producto"][$id_producto],
                            "cantidad" => $cantidad,
                            "costo" => $precio_compra, // Clave corregida de 'precio_compra' a 'costo'
                            "subtotal" => $subtotal
                        );
                        $montoTotalRecibido += $subtotal;
                    }
                }
            }

            if(empty($productosRecibidos)){
                echo "Error: No se recibieron productos.";
                return;
            }

            // 2. Obtener datos adicionales de la orden de compra original
            $ordenOriginal = ControllerOrdenesCompras::ctrMostrarOrdenCompra("id_orden_compra", $idOrdenCompra);
            $idProveedor = $ordenOriginal["id_proveedor"];

            // 3. Preparar el array de datos para el modelo
            $datos = array(
                "id_orden_compra" => $idOrdenCompra,
                "id_proveedor" => $idProveedor,
                "id_usuario" => $_SESSION["id_usuario"],
                "productos" => json_encode($productosRecibidos),
                "monto_total" => $montoTotalRecibido,
                "fecha" => date('Y-m-d h:i:s a')
            );

            // 4. Llamar al modelo para que ejecute la lógica de negocio
            $respuesta = ModelEntradas::mdlIngresarEntrada("entradas_almacen", "entradas_almacen_detalle", $datos);

            // 5. Devolver respuesta al AJAX
            echo $respuesta;
        }
    }

    /*=============================================
     EDITAR ENTRADA
    =============================================*/
    static public function ctrEditarEntrada()
    {
        // Lógica para editar una entrada/recepción ya registrada
    }

    /*=============================================
     ANULAR ENTRADA
    =============================================*/
    static public function ctrAnularEntrada()
    {
        // Lógica para anular una entrada y revertir el stock
    }

}
