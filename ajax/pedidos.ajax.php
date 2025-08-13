<?php
require_once "../controller/pedidos.controller.php";
require_once "../model/pedidos.model.php";

class AjaxPedidos{
    /*======================================
        EDITAR PEDIDOS
    =======================================*/
    public $idPedido;
    public function ajaxEditarPedido(){
        $item = "id_pedido";
        $valor = $this->idPedido;
        $respuesta = ControllerPedidos::ctrMostrarPedidos($item, $valor);
        echo json_encode($respuesta);
    }
}

/*======================================
    EDITAR PEDIDOS
=======================================*/
if (isset($_POST["idPedido"])){
    $editarPedidos = new AjaxPedidos();
    $editarPedidos -> idPedido = $_POST["idPedido"];
    $editarPedidos -> ajaxEditarPedido();
}