<?php

require_once "../controller/compras.controller.php";
require_once "../model/compras.model.php";

class AjaxCompras{

    /*======================================
     TRAER COMPRA
    ======================================**/
    public $idCompra;

    public function ajaxTraerCompra(){

        $item = "id_compra";
        $valor = $this->idCompra;

        $compra = ControllerCompras::ctrMostrarCompras($item, $valor);

        $itemDetalle = "id_compra";
        $valorDetalle = $this->idCompra;
        $detalle = ControllerCompras::ctrMostrarDetalleCompra($itemDetalle, $valorDetalle);

        $respuesta = array(
            "compra" => $compra,
            "detalle" => $detalle
        );

        echo json_encode($respuesta);

    }
}

/*======================================
 OBJETO
======================================**/
if(isset($_POST["idCompra"])){

    $traerCompra = new AjaxCompras();
    $traerCompra -> idCompra = $_POST["idCompra"];
    $traerCompra -> ajaxTraerCompra();

}