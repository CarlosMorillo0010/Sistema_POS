<?php

require_once "../controller/bancos.controller.php";
require_once "../model/bancos.model.php";

class ajaxBancos{

	 /**=====================================
	 EDITAR BANCO
	 ======================================**/
	 public $idBanco;
	 public function ajaxEditarBanco(){
	 	$item = "id_banco";
	 	$valor = $this->idBanco;
	 	$respuesta = ControllerBancos::ctrMostrarBanco($item,$valor);
	 	echo json_encode($respuesta);
	 }

	 /**=====================================
        NO REPETIR BANCO
    ======================================**/
    public $validarBanco;
    public function ajaxValidarBanco(){
        $item = "banco";
        $valor = $this->validarBanco;
        $respuesta = ControllerBancos::ctrMostrarBanco($item, $valor);
        echo json_encode($respuesta);
    }
} 

/**=====================================
 EDITAR BANCO
======================================**/
	if (isset($_POST["idBanco"])){
	$bancos = new AjaxBancos();
	$bancos -> idBanco = $_POST["idBanco"];
	$bancos -> ajaxEditarBanco();
}

/**=====================================
    NO REPETIR BANCO
======================================**/
if (isset($_POST["validarBanco"])){
    $valBanco = new AjaxBancos();
    $valBanco -> validarBanco = $_POST["validarBanco"];
    $valBanco -> ajaxValidarBanco();
}
	 


