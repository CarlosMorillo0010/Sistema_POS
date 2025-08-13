<?php
require_once "../controller/config-divisas.controller.php";
require_once "../model/config-divisas.model.php";

class AjaxConfigurarValorDivisas
{
    /*======================================
     EDITAR PERFILES
    ======================================**/
    public $idValor;

    public function ajaxEditarValorDivisa()
    {
        $item = "id_valor";
        $valor = $this->idValor;
        $respuesta = ControllerConfiguracionesDivisas::ctrMostrarConfiguracionDivisa($item, $valor);
        echo json_encode($respuesta);
    }
}

/*======================================
 EDITAR PERFILES
======================================**/
if(isset($_POST["idValor"])){
    $valor = new AjaxConfigurarValorDivisas();
    $valor -> idValor = $_POST["idValor"];
    $valor->ajaxEditarValorDivisa();
}