<?php
require_once "../controller/presupuestos.controller.php";
require_once "../model/presupuestos.model.php";

class AjaxPresupuesto{
    /*======================================
        EDITAR PRESUPUESTO
    =======================================*/
    public $idPresupuesto;
    public function ajaxEditarPresupuesto(){
        $item = "id_presupuesto";
        $valor = $this->idPresupuesto;
        $respuesta = ControllerPresupuestos::ctrMostrarPresupuesto($item, $valor);
        echo json_encode($respuesta);
    }
}

/*======================================
    EDITAR PRESUPUESTO
=======================================*/
if (isset($_POST["idPresupuesto"])){
    $editarPresupuestos = new AjaxPresupuesto();
    $editarPresupuestos -> idPresupuesto = $_POST["idPresupuesto"];
    $editarPresupuestos -> ajaxEditarPresupuesto();
}