<?php

require_once "../controller/categories.controller.php";
require_once "../model/categories.model.php";

class ajaxCategories{
	 /**=====================================
	 EDITAR CATEGORIAS
	 ======================================**/

	 public $idCategoria;
	 public function ajaxEditarCategoria(){
	 	$item = "id_categoria";
	 	$valor = $this->idCategoria;
	 	$respuesta = ControllerCategories::ctrMostrarCategoria($item,$valor);
	 	echo json_encode($respuesta);
	 }
	 /**=====================================
        NO REPETIR CATEGORIA
    ======================================**/
    public $validarCategori;
    public function ajaxValidarCategori(){
        $item = "categoria";
        $valor = $this->validarCategori;
        $respuesta = ControllerCategories::ctrMostrarCategoria($item, $valor);
        echo json_encode($respuesta);
    }
} 

 /**=====================================
 EDITAR CATEGORIAS
 ======================================**/

 if (isset($_POST["idCategoria"])){
 	$categorias = new AjaxCategories();
 	$categorias -> idCategoria = $_POST["idCategoria"];
 	$categorias -> ajaxEditarCategoria();
 }

/**=====================================
    NO REPETIR CATEGORIA
======================================**/
if (isset($_POST["validarCategori"])){
    $valCategori = new AjaxCategories();
    $valCategori -> validarCategori = $_POST["validarCategori"];
    $valCategori -> ajaxValidarCategori();
}
