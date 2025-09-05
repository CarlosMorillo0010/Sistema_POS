<?php

// Requerimientos corregidos y aumentados para asegurar que todas las dependencias existan.
require_once "../model/connection.php";
require_once "../controller/ventas-controller.php";
require_once "../model/ventas-model.php";
require_once "../controller/clients.controller.php";
require_once "../model/clients.model.php";


class AjaxVentas{

    /*======================================
     TRAER VENTA (Usado en otras partes del sistema)
    ======================================*/
    public $idVenta;

    public function ajaxTraerVenta(){

        $item = "id_venta";
        $valor = $this->idVenta;

        $venta = ControllerVentas::ctrMostrarVenta($item, $valor);

        echo json_encode($venta);

    }
}

/*======================================
 OBJETO PARA TRAER VENTA SIMPLE
======================================*/
if(isset($_POST["idVenta"])){

    $traerVenta = new AjaxVentas();
    $traerVenta -> idVenta = $_POST["idVenta"];
    $traerVenta -> ajaxTraerVenta();

}

/*======================================
 TRAER VENTA DETALLADA PARA MODAL
======================================*/
if(isset($_POST["idVentaDetalle"])){

    $item = "id_venta";
    $valor = $_POST["idVentaDetalle"];

    // Esta es la función correcta que trae todos los datos necesarios para el modal.
    $ventaDetallada = ControllerVentas::ctrMostrarVentaDetallada($item, $valor);

    // Devolvemos el resultado como un JSON válido.
    echo json_encode($ventaDetallada);

}