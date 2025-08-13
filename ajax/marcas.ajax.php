<?php

require_once "../controller/marcas.controller.php";
require_once "../model/marcas.model.php";

class AjaxMarcas{
    /**=====================================
    EDITAR MARCAS
    ======================================**/

    public $idMarca;
    public function ajaxEditarMarca(){
        $item = "id_marca";
        $valor = $this->idMarca;
        $respuesta = ControllerMarcas::ctrMostrarMarca($item,$valor);
        echo json_encode($respuesta);
    }

    /**=====================================
        NO REPETIR MARCA
    ======================================**/
    public $validarMarca;
    public function ajaxValidarMarca(){
        $item = "marca";
        $valor = $this->validarMarca;
        $respuesta = ControllerMarcas::ctrMostrarMarca($item, $valor);
        echo json_encode($respuesta);
    }
}

/**=====================================
EDITAR MARCAS
======================================**/

if (isset($_POST["idMarca"])){
    $marca = new AjaxMarcas();
    $marca -> idMarca = $_POST["idMarca"];
    $marca -> ajaxEditarMarca();
}


/**=====================================
    NO REPETIR MARCAS
======================================**/
if (isset($_POST["validarMarca"])){
    $valMarca = new AjaxMarcas();
    $valMarca -> validarMarca = $_POST["validarMarca"];
    $valMarca -> ajaxValidarMarca();
}

