<?php
require_once "../controller/ventas-controller.php";
require_once "../controller/clients.controller.php";
require_once "../model/ventas-model.php";
require_once "../model/clients.model.php";
require_once "../model/connection.php";

// PRIMERA CONDICIÓN: Verificamos si se envió 'idVentaDetalle'
if (isset($_POST["idVentaDetalle"])) {

    $item = "id_venta"; // Nombre de la columna en la tabla de ventas
    $valor = $_POST["idVentaDetalle"];

    // Llamamos a un nuevo método en el controlador
    $venta = ControllerVentas::ctrMostrarVentaDetallada($item, $valor);
    
    // Devolvemos la respuesta en formato JSON y terminamos el script
    echo json_encode($venta);

// SEGUNDA CONDICIÓN: Si no se envió 'idVentaDetalle', verificamos si se envió 'accion'
} else if (isset($_POST["accion"])) {

    $accion = $_POST["accion"];

    switch ($accion) {
        
        case 'marcar_pendiente':
            ControllerVentas::ctrMarcarVentaComoPendiente();
            break;

        case 'actualizar_estado':
            ControllerVentas::ctrActualizarEstadoVenta();
            break;
        
        default:
            echo "error_accion_desconocida";
            break;
    }

// SI NINGUNA DE LAS CONDICIONES ANTERIORES SE CUMPLIÓ
} else {
    // Si no se envía ni 'idVentaDetalle' ni 'accion', no se puede procesar
    echo json_encode(["error" => "No se proporcionó una acción o ID válido"]);
}