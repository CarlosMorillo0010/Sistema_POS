<?php

require_once "../controller/ventas.controller.php";
require_once "../model/ventas.model.php";

class AjaxVentas{

    /*======================================
     TRAER VENTA
    ======================================**/
    public $idVenta;

    public function ajaxTraerVenta(){

        $item = "id_venta";
        $valor = $this->idVenta;

        $venta = ControllerVentas::ctrMostrarVenta($item, $valor);

        echo json_encode($venta);

    }
}

/*======================================
 OBJETO
======================================**/
if(isset($_POST["idVenta"])){

    $traerVenta = new AjaxVentas();
    $traerVenta -> idVenta = $_POST["idVenta"];
    $traerVenta -> ajaxTraerVenta();

}
