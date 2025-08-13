<?php

require_once "../controller/modelos.controller.php";
require_once "../model/modelos.model.php";

class AjaxModelos{
    /**=====================================
    EDITAR MODELO
    ======================================**/

    public $idModelo;
    public function ajaxEditarModelo(){
        $item = "id_modelo";
        $valor = $this->idModelo;
        $respuesta = ControllerModelos::ctrMostrarModelos($item,$valor);
        echo json_encode($respuesta);
    }

    /**=====================================
        NO REPETIR MODELO
    ======================================**/
    public $validarModelo;
    public function ajaxValidarModelo(){
        $item = "modelo";
        $valor = $this->validarModelo;
        $respuesta = ControllerModelos::ctrMostrarModelos($item, $valor);
        echo json_encode($respuesta);
    }
}

/**=====================================
EDITAR MODELOS
======================================**/

if (isset($_POST["idModelo"])){
    $modelo = new AjaxModelos();
    $modelo -> idModelo = $_POST["idModelo"];
    $modelo -> ajaxEditarModelo();
}


/**=====================================
    NO REPETIR MODELO
======================================**/
if (isset($_POST["validarModelo"])){
    $valModelo = new AjaxModelos();
    $valModelo -> validarModelo = $_POST["validarModelo"];
    $valModelo -> ajaxValidarModelo();
}

