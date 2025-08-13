<?php
require_once "../controller/ordenes-compras.controller.php";
require_once "../model/ordenes-compras.model.php";

class AjaxOrdenesCompras{
    /*======================================
        EDITAR ORDEN DE COMPRA
    =======================================*/
    public $idOrden;
    public function ajaxEditarOrdenCompra(){
        $item = "id_orden_compra";
        $valor = $this->idOrden;
        $respuesta = ControllerOrdenesCompras::ctrMostrarOrdenCompra($item, $valor);
        echo json_encode($respuesta);
    }
}

/*======================================
    EDITAR ORDEN DE COMPRA
=======================================*/
if (isset($_POST["idOrden"])){
    $editarOrdenCompra = new AjaxOrdenesCompras();
    $editarOrdenCompra -> idOrden = $_POST["idOrden"];
    $editarOrdenCompra -> ajaxEditarOrdenCompra();
}