<?php
require_once "../controller/facturas-ventas.controller.php";
require_once "../model/facturas-ventas.model.php";

class AjaxFacturaVentas{
    /*======================================
        EDITAR FACTURA DE VENTAS
    =======================================*/
    public $idFactura;
    public function ajaxEditarFacturaVenta(){
        $item = "id_factura_venta";
        $valor = $this->idFactura;
        $respuesta = ControllerFacturaVentas::ctrMostrarFacturaVenta($item, $valor);
        echo json_encode($respuesta);
    }
}

/*======================================
    EDITAR FACTURA DE VENTAS
=======================================*/
if (isset($_POST["idFactura"])){
    $editarFacturaVentas = new AjaxFacturaVentas();
    $editarFacturaVentas -> idFactura = $_POST["idFactura"];
    $editarFacturaVentas -> ajaxEditarFacturaVenta();
}