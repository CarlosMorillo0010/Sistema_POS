<?php

// Requerimientos corregidos y aumentados para asegurar que todas las dependencias existan.
require_once "../model/connection.php";
require_once "../controller/ventas-controller.php";
require_once "../model/ventas-model.php";
require_once "../controller/clients.controller.php";
require_once "../model/clients.model.php";

/*============================================
 ACCIONES DE ESTADO (DESDE LIBRO DE VENTAS)
============================================*/
if (isset($_POST["accion"])) {

    switch ($_POST["accion"]) {

        case "marcar_pendiente":
            // Esta función ya se encarga de todo el proceso y de imprimir "ok" o un error.
            ControllerVentas::ctrMarcarVentaComoPendiente();
            break;

        case "actualizar_estado":
            // Esta función actualiza el estado y también imprime la respuesta.
            ControllerVentas::ctrActualizarEstadoVenta();
            break;
    }

    // Detenemos el script aquí para que no continúe con los otros bloques if.
    return; 
}


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
// Se añade !isset($_POST["accion"]) para evitar que se ejecute durante una actualización de estado.
if(isset($_POST["idVenta"]) && !isset($_POST["accion"])){

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
