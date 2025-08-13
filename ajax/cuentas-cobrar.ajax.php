<?php

require_once "../controller/cuentas-cobrar.controller.php";
require_once "../model/cuentas-cobrar.model.php";

class AjaxCuentasCobrar{

	 /*======================================
	 EDITAR CUENTAS POR COBRAR
	 ======================================**/
	 public $idCuentaCobrar;

	 public function ajaxEditarCuentaCobrar(){
	 	$item = "id_cuentas_cobrar";
	 	$valor = $this->idCuentaCobrar;
	 	$respuesta = ControllerCuentasCobrar::ctrMostrarCuentasCobrar($item, $valor);
	 	echo json_encode($respuesta);
	 }
}

 /*======================================
 EDITAR CUENTAS POR COBRAR
 ======================================**/
 if (isset($_POST["idCuentaCobrar"])){
 	$cuentas_cobrar = new AjaxCuentasCobrar();
 	$cuentas_cobrar -> idCuentaCobrar = $_POST["idCuentaCobrar"];
 	$cuentas_cobrar -> ajaxEditarCuentaCobrar();
 }

