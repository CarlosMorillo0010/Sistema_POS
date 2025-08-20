<?php
require_once "../controller/ventas-controller.php";
require_once "../model/ventas-model.php";
require_once "../model/connection.php";

ControllerVentas::ctrActualizarEstadoVenta();

if (isset($_POST["idVentaDetalle"])) {

    $item = "id_venta"; // Nombre de la columna en la tabla de ventas
    $valor = $_POST["idVentaDetalle"];

    // Llamamos a un nuevo método en el controlador
    $venta = ControllerVentas::ctrMostrarVentaDetallada($item, $valor);
    
    // Devolvemos la respuesta en formato JSON
    echo json_encode($venta);
}