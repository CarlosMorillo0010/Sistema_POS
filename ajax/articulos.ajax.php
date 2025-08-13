<?php
require_once "../controller/articulos.controller.php";
require_once "../model/articulos.model.php";

class AjaxArticulos{

    /**=====================================
        EDITAR ARTICULO
    ======================================**/
    public $idArticulo;
    public function ajaxEditarArticulo(){
        $item = "id_articulo";
        $valor = $this->idArticulo;
        $respuesta = ControllerArticulos::ctrMostrarArticulos($item, $valor);
        echo json_encode($respuesta);
    }
}

/**=====================================
EDITAR ARTICULOS
======================================**/
if(isset($_POST["idArticulo"])){
    $editarArticulo = new AjaxArticulos();
    $editarArticulo -> idArticulo = $_POST["idArticulo"];
    $editarArticulo -> ajaxEditarArticulo();
}
