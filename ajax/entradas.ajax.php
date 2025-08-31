<?php

session_start();

require_once "../controller/entradas.controller.php";
require_once "../model/entradas.model.php";
require_once "../controller/ordenes-compras.controller.php";
require_once "../model/ordenes-compras.model.php";
require_once "../controller/proveedores.controller.php";
require_once "../model/proveedores.model.php";

class AjaxEntradas{

    /*=============================================
    TRAER DETALLES DE LA ORDEN PARA LA RECEPCIÓN
    =============================================*/
    public $idOrdenCompra;

    public function ajaxTraerOrdenParaRecepcion(){

        $item = "id_orden_compra";
        $valor = $this->idOrdenCompra;

        $orden = ControllerOrdenesCompras::ctrMostrarOrdenCompra($item, $valor);
        $detalle = ControllerOrdenesCompras::ctrMostrarOrdenCompraDetalle($item, $valor);

        $respuesta = array(
            "orden" => $orden,
            "detalle" => $detalle
        );

        echo json_encode($respuesta);

    }

}

/*=============================================
OBJETOS DE LA CLASE
=============================================*/

// Objeto para traer los detalles de la orden
if(isset($_POST["idOrdenCompra"]) && !isset($_POST["action"])){

    $traerOrden = new AjaxEntradas();
    $traerOrden -> idOrdenCompra = $_POST["idOrdenCompra"];
    $traerOrden -> ajaxTraerOrdenParaRecepcion();

}

// Objeto para crear la entrada/recepción
if(isset($_POST["action"]) && $_POST["action"] == "crearEntrada"){

    $crearEntrada = new ControllerEntradas();
    $crearEntrada -> ctrCrearEntrada();

}
