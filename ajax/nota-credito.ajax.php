<?php
require_once "../controller/nota-creditos.controller.php";
require_once "../model/nota-creditos.model.php";

class AjaxNotaCredito{
    /*======================================
        EDITAR NOTA CREDITO VENTA
    =======================================*/
    public $idNotaCredito;
    public function ajaxEditarNotaCredito(){
        $item = "id_nota_credito";
        $valor = $this->idNotaCredito;
        $respuesta = ControllerNotaCreditos::ctrMostrarNotaCredito($item, $valor);
        echo json_encode($respuesta);
    }
}

/*======================================
    EDITAR NOTA CREDITO VENTA
=======================================*/
if (isset($_POST["idNotaCredito"])){
    $editarNotaCredito = new AjaxNotaCredito();
    $editarNotaCredito -> idNotaCredito = $_POST["idNotaCredito"];
    $editarNotaCredito -> ajaxEditarNotaCredito();
}