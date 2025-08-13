<?php
require_once "../controller/almacenes.controller.php";
require_once "../model/almacenes.model.php";

class AjaxAlmacenes
{
    /**=====================================
     * EDITAR ALMACEN
     * ======================================**/
    public $idAlmacen;
    public function ajaxEditarAlmacen()
    {
        $item = "id_almacen";
        $valor = $this->idAlmacen;
        $respuesta = ControllerAlmacenes::ctrMostrarAlmacen($item, $valor);
        echo json_encode($respuesta);
    }

    /**=====================================
        NO REPETIR ALMACEN
    ======================================**/
    public $validarAlmacen;
    public function ajaxValidarAlmacen(){
        $item = "nombre";
        $valor = $this->validarAlmacen;
        $respuesta = ControllerAlmacenes::ctrMostrarAlmacen($item, $valor);
        echo json_encode($respuesta);
    }
}

/**=====================================
EDITAR ALMACEN
======================================**/
if(isset($_POST["idAlmacen"])){
    $almacen = new AjaxAlmacenes();
    $almacen -> idAlmacen = $_POST["idAlmacen"];
    $almacen->ajaxEditarAlmacen();
}

/**=====================================
    NO REPETIR ALMACEN
======================================**/
if (isset($_POST["validarAlmacen"])){
    $valAlmacen = new AjaxAlmacenes();
    $valAlmacen -> validarAlmacen = $_POST["validarAlmacen"];
    $valAlmacen -> ajaxValidarAlmacen();
}