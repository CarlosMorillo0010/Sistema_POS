<?php

require_once "../controller/clients.controller.php";
require_once "../model/clients.model.php";

class AjaxClients{
	 /*======================================
	 EDITAR CLIENTES
	 ======================================**/
	 public $idCliente;
	 public $traerClientes;
	 public $nombreCliente;

	 public function ajaxEditarCliente(){
         if ($this->traerClientes == "OK"){
             $item = null;
             $valor = null;
             $respuesta = ControllerClients::ctrMostrarClientes($item, $valor);
             echo json_encode($respuesta);
         }else if($this->nombreCliente != ""){
             $item = "nombre";
             $valor = $this->nombreCliente;
             $respuesta = ControllerClients::ctrMostrarClientes($item, $valor);
             echo json_encode($respuesta);
         }else{
             $item = "id";
             $valor = $this->idCliente;
             $respuesta = ControllerClients::ctrMostrarClientes($item, $valor);
             echo json_encode($respuesta);
         }
	 }
} 

/*======================================
EDITAR CLIENTES
======================================**/
if (isset($_POST["idCliente"])){
$clientes = new AjaxClients();
$clientes -> idCliente = $_POST["idCliente"];
$clientes -> ajaxEditarCliente();
}

/*======================================
TRAER CLIENTES
======================================**/
if (isset($_POST["traerClientes"])){
    $traerClientes = new AjaxClients();
    $traerClientes -> traerClientes = $_POST["traerClientes"];
    $traerClientes -> ajaxEditarCliente();
}

/*======================================
TRAER CLIENTES
======================================**/
if (isset($_POST["nombreCliente"])){
    $traerClientes = new AjaxClients();
    $traerClientes -> nombreCliente = $_POST["nombreCliente"];
    $traerClientes -> ajaxEditarCliente();
}