<?php

require_once "../controller/ordenes-compras.controller.php";
require_once "../model/ordenes-compras.model.php";

class AjaxCompras
{

    /*=============================================
    CARGAR PRODUCTOS DE ORDEN DE COMPRA
    =============================================*/

    public $idOrdenCompra;

    public function ajaxCargarProductos()
    {

        $item = "id_orden_compra";
        $valor = $this->idOrdenCompra;

        $orden = ControllerOrdenesCompras::ctrMostrarOrdenCompra($item, $valor);
        $detalle = ModelOrdenesCompras::mdlMostrarOrdenCompraDetalle("orden_compra_detalle", $item, $valor);

        $respuesta = array(
            "orden" => $orden,
            "detalle" => $detalle
        );

        echo json_encode($respuesta);

    }

}

/*=============================================
OBJETO
=============================================*/

if (isset($_POST["idOrdenCompra"])) {

    $cargarProductos = new AjaxCompras();
    $cargarProductos->idOrdenCompra = $_POST["idOrdenCompra"];
    $cargarProductos->ajaxCargarProductos();

}
