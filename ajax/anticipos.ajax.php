<?php

require_once "../controller/anticipos.controller.php";
require_once "../model/anticipos.model.php";

class AjaxAnticipos{

	 /**=====================================
	 EDITAR ANTICIPO
	 ======================================**/

	 public $idAnticipo;

	 public function ajaxEditarAnticipo(){

	 	$item = "id_anticipo";

	 	$valor = $this->idAnticipo;

	 	$respuesta = ControllerAnticipos::ctrMostrarAnticipos($item, $valor);

	 	echo json_encode($respuesta);

	 }


}

 /**=====================================
 EDITAR ANTICIPO
 ======================================**/

 if (isset($_POST["idAnticipo"])){

 	$anticipos = new AjaxAnticipos();
 	$anticipos -> idAnticipo = $_POST["idAnticipo"];
 	$anticipos -> ajaxEditarAnticipo();

 }

