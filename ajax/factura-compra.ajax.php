<?php
require_once "../controller/facturas-compras.controller.php";
require_once "../model/facturas-compras.model.php";

class AjaxFacturaCompra{
    /*======================================
        EDITAR FACTURA DE COMPRA
    =======================================*/
    public $idFacturaCompra;
    public function ajaxEditarFacturaCompra(){
        $item = "id_factura_compra";
        $valor = $this->idFacturaCompra;
        $respuesta = ControllerFacturasCompras::ctrMostrarFacturaCompra($item, $valor);
        echo json_encode($respuesta);
    }
}

/*======================================
    EDITAR FACTURA DE COMPRA
=======================================*/
if (isset($_POST["idFacturaCompra"])){
    $editarFacturaCompra = new AjaxFacturaCompra();
    $editarFacturaCompra -> idFacturaCompra = $_POST["idFacturaCompra"];
    $editarFacturaCompra -> ajaxEditarFacturaCompra();
}