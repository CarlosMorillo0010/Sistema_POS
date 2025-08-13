<?php

require_once "../controller/impuestos.controller.php";
require_once "../model/impuestos.model.php";

class AjaxImpuestos
{
    /**=====================================
     * EDITAR IMPUESTOS
     * ======================================**/
    public $idImpuesto;
    public function ajaxEditarImpuesto()
    {
        $item = "id_impuesto";
        $valor = $this->idImpuesto;
        $respuesta = ControllerImpuestos::ctrMostrarImpuesto($item, $valor);
        echo json_encode($respuesta);
    }
}

/**=====================================
 * EDITAR IMPUESTOS
 * ======================================**/
if (isset($_POST["idImpuesto"])) {
    $editarImpuesto = new AjaxImpuestos();
    $editarImpuesto->idImpuesto = $_POST["idImpuesto"];
    $editarImpuesto->ajaxEditarImpuesto();
}

class ajaxPorcentajes
{
    /**=====================================
     * EDITAR PORCENTAJE
     * ======================================**/
    public $idPorcentaje;
    public function ajaxEditarPorcentaje()
    {
        $item = "id_impuesto";
        $valor = $this->idImpuesto;
        $respuesta = ControllerImpuestos::ctrMostrarImpuesto($item, $valor);
        echo json_encode($respuesta);
    }
}
/**=====================================
 * EDITAR PORCENTAJE
 * ======================================**/
if (isset($_POST["idPorcentaje"])) {
    $porcentaje = new AjaxPorcentajes();
    $porcentaje->idPorcentaje = $_POST["idPorcentaje"];
    $porcentaje->ajaxEditarPorcentaje();
}