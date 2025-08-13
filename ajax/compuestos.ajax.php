<?php
require_once "../controller/compuests.controller.php";
require_once "../model/compuests.model.php";

class AjaxCompuestos{
    /**=====================================
        GENERAR CODIGO A PARTIR DE ID CATEGORIA
    ======================================**/
    public $idCategoria;
    public function ajaxCrearCodigoCompuesto(){
        $item = "id";
        $valor = $this->idCategoria;
        $orden = "codigo";
        $respuesta = ControllerProducts::ctrMostrarProductos($item, $valor, $orden);
        echo json_encode($respuesta);
    }

    /**=====================================
        EDITAR PRODUCTO COMPUESTO
    ======================================**/
    public $idCompuesto;
    public function ajaxEditarCompuesto(){
        $item = "id_compuesto";
        $valor = $this->idCompuesto;
        $respuesta = ControllerCompuestos::ctrMostrarCompuesto($item, $valor);
        echo json_encode($respuesta);
    }
}

/**=====================================
GENERAR CODIGO A PARTIR DE ID CATEGORIA
======================================**/
if(isset($_POST["idCategoria"])){
    $codigoCompuesto = new AjaxCompuestos();
    $codigoCompuesto -> idCategoria = $_POST["idCategoria"];
    $codigoCompuesto -> ajaxCrearCodigoCompuesto();
}

/**=====================================
EDITAR PRODUCTO COMPUESTO
======================================**/
if(isset($_POST["idCompuesto"])){
    $editarCompuesto = new AjaxCompuestos();
    $editarCompuesto -> idCompuesto = $_POST["idCompuesto"];
    $editarCompuesto -> ajaxEditarCompuesto();
}
