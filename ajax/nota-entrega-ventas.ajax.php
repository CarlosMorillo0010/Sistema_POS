<?php
require_once "../controller/nota-entrega-ventas.controller.php";
require_once "../model/nota-entrega-ventas.model.php";

class AjaxNotaEntregaVentas{
    /*======================================
        EDITAR NOTA DE ENTREGA VENTA
    =======================================*/
    public $idNotaVentas;
    public function ajaxEditarNotaEntregaVenta(){
        $item = "id_nota_entrega";
        $valor = $this->idNotaVentas;
        $respuesta = ControllerNotasEntregaVentas::ctrMostrarNotaEntregaVenta($item, $valor);
        echo json_encode($respuesta);
    }
}

/*======================================
    EDITAR NOTA DE ENTREGA VENTA
=======================================*/
if (isset($_POST["idNotaVentas"])){
    $editarNotaEntregaVentas = new AjaxNotaEntregaVentas();
    $editarNotaEntregaVentas -> idNotaVentas = $_POST["idNotaVentas"];
    $editarNotaEntregaVentas -> ajaxEditarNotaEntregaVenta();
}