<?php

require_once "../controller/cuentas-pagar.controller.php";
require_once "../model/cuentas-pagar.model.php";

class AjaxCuentasPagar{

	 /*======================================
	 EDITAR CUENTAS POR PAGAR
	 ======================================**/
	 public $idCuentaPagar;

	 public function ajaxEditarCuentaPagar(){
	 	$item = "id_libro_compra";
	 	$valor = $this->idCuentaPagar;
	 	$respuesta = ControllerCuentasPagar::ctrMostrarCuentasPagar($item, $valor);
	 	echo json_encode($respuesta);
	 }
}

 /*======================================
 EDITAR CUENTAS POR PAGAR
 ======================================**/
 if (isset($_POST["idCuentaPagar"])){
 	$cuentas_pagar = new AjaxCuentasPagar();
 	$cuentas_pagar -> idCuentaPagar = $_POST["idCuentaPagar"];
 	$cuentas_pagar -> ajaxEditarCuentaPagar();
 }

