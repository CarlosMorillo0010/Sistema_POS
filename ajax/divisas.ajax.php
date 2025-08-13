<?php

require_once "../controller/divisas.controller.php";
require_once "../model/divisas.model.php";

class ajaxDivisas
{
    /**=====================================
     * EDITAR DIVISA
     * ======================================**/
    public $idDivisa;
    public function ajaxEditarDivisa()
    {
        $item = "id_divisa";
        $valor = $this->idDivisa;
        $respuesta = ControllerDivisas::ctrMostrarDivisa($item, $valor);
        echo json_encode($respuesta);
    }
    /**=====================================
        NO REPETIR DIVISA
    ======================================**/
    public $validarDivisa;
    public function ajaxValidarDivisa(){
        $item = "divisa";
        $valor = $this->validarDivisa;
        $respuesta = ControllerDivisas::ctrMostrarDivisa($item, $valor);
        echo json_encode($respuesta);
    }
}

/**=====================================
 * EDITAR DIVISA
 * ======================================**/
if (isset($_POST["idDivisa"])) {
    $divisas = new AjaxDivisas();
    $divisas->idDivisa = $_POST["idDivisa"];
    $divisas->ajaxEditarDivisa();
}

class ajaxSimbolos
{
    /**=====================================
     * EDITAR SIMBOLO
     * ======================================**/
    public $idSimbolo;
    public function ajaxEditarSimbolo()
    {
        $item = "id_divisa";
        $valor = $this->idSimbolo;
        $respuesta = ControllerDivisas::ctrMostrarDivisa($item, $valor);
        echo json_encode($respuesta);
    }
}

/**=====================================
 * EDITAR SIMBOLO
 * ======================================**/
if (isset($_POST["idSimbolo"])) {
    $simbolo = new AjaxSimbolos();
    $simbolo->idSimbolo = $_POST["idSimbolo"];
    $simbolo->ajaxEditarSimbolo();
}

/**=====================================
    NO REPETIR DIVISAS
======================================**/
if (isset($_POST["validarDivisa"])){
    $valDivisa = new AjaxDivisas();
    $valDivisa -> validarDivisa = $_POST["validarDivisa"];
    $valDivisa -> ajaxValidarDivisa();
}
