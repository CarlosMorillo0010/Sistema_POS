<?php

require_once "../controller/proveedores.controller.php";
require_once "../model/proveedores.model.php";

class AjaxProveedores {
    
    /*======================================
    EDITAR PROVEEDORES
    ======================================**/
    public $idProveedor;
    public $traerProveedores;
    public $nombreProveedor;

    public function ajaxEditarProveedor(){

        if ($this->traerProveedores == "OK") {
            // Si se solicita traer todos los proveedores, no se filtra por ID ni nombre
            $item = null;
            $valor = null;
            $respuesta = ControllerProveedores::ctrMostrarProveedores($item, $valor);
            echo json_encode($respuesta);

        } else if($this->nombreProveedor != ""){
            // Si se recibe un nombre de proveedor, se busca por nombre
            $item = "nombre";
            $valor = $this->nombreProveedor;
            $respuesta = ControllerProveedores::ctrMostrarProveedores($item, $valor);
            echo json_encode($respuesta);

        }else {
            // Si se recibe un ID de proveedor, se busca por ID
            $item = "id_proveedor";
            $valor = $this->idProveedor;
            $respuesta = ControllerProveedores::ctrMostrarProveedores($item, $valor);
            echo json_encode($respuesta);

        }
    }
}

/*======================================
EDITAR PROVEEDOR
======================================**/
if (isset($_POST["idProveedor"])) {
    $proveedores = new AjaxProveedores();
    $proveedores->idProveedor = $_POST["idProveedor"];
    $proveedores->ajaxEditarProveedor();
}

/*======================================
TRAER PROVEEDOR
======================================**/
if (isset($_POST["traerProveedores"])){
    $traerProveedores = new AjaxProveedores();
    $traerProveedores -> traerProveedores = $_POST["traerProveedores"];
    $traerProveedores -> ajaxEditarProveedor();
}

/*======================================
TRAER CLIENTES
======================================**/
if (isset($_POST["nombreProveedor"])){
    $traerProveedores = new AjaxProveedores();
    $traerProveedores -> nombreProveedor = $_POST["nombreProveedor"];
    $traerProveedores -> ajaxEditarProveedor();
}