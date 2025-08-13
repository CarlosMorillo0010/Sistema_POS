<?php
require_once "../controller/nota-entregas.controller.php";
require_once "../model/nota-entregas.model.php";

class AjaxNotaEntrega{
    /*======================================
        EDITAR NOTA ENTREGA
    =======================================*/
    public $idNotaCompra;
    public function ajaxEditarNotaEntrega(){
        $item = "id_nota_entrega_compra";
        $valor = $this->idNotaCompra;
        $respuesta = ControllerNotasEntregas::ctrMostrarNotaEntrega($item, $valor);
        echo json_encode($respuesta);
    }
}

/*======================================
    EDITAR NOTA ENTREGA
=======================================*/
if (isset($_POST["idNotaCompra"])){
    $editarNotaEntrega = new AjaxNotaEntrega();
    $editarNotaEntrega -> idNotaCompra = $_POST["idNotaCompra"];
    $editarNotaEntrega -> ajaxEditarNotaEntrega();
}