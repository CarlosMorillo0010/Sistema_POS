<?php

require_once "../controller/pagos.controller.php";
require_once "../model/pagos.model.php";

class ajaxPagos{
	/**=====================================
	EDITAR PAGO
	======================================**/
	public $idPago;
	public function ajaxEditarPago(){
	$item = "id_forma_pagos";
	$valor = $this->idPago;
	$respuesta = ControllerPagos::ctrMostrarPago($item,$valor);
	echo json_encode($respuesta);
 }
 
	/**=====================================
	NO REPETIR PAGO
	======================================**/
	public $validarPago;
	public function ajaxValidarPago(){
	$item = "forma_pago";
	$valor = $this->validarPago;
	$respuesta = ControllerPagos::ctrMostrarPago($item, $valor);
	echo json_encode($respuesta);
	}
} 

/**=====================================
EDITAR PAGO
======================================**/
if (isset($_POST["idPago"])){
$pagos = new AjaxPagos();
$pagos -> idPago = $_POST["idPago"];
$pagos -> ajaxEditarPago();
}

/**=====================================
NO REPETIR PAGO
======================================**/
if (isset($_POST["validarPago"])){
$valPago = new AjaxPagos();
$valPago -> validarPago = $_POST["validarPago"];
$valPago -> ajaxValidarPago();
}