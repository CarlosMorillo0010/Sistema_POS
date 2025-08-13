<?php

require_once "../controller/sucursales.controller.php";
require_once "../model/sucursales.model.php";

class AjaxSucursales
{
    /**=====================================
     * EDITAR CODIGO SUCURSAL
     * ======================================**/
    public $idSucursal;
    public function ajaxEditarSucursal()
    {
        $item = "id_sucursal";
        $valor = $this->idSucursal;
        $respuesta = ControllerSucursales::ctrMostrarSucursal($item, $valor);
        echo json_encode($respuesta);
    }

    /**=====================================
        NO REPETIR SUCURSALES
    ======================================**/
    public $validarSucursal;
    public function ajaxValidarSucursal(){
        $item = "nombre";
        $valor = $this->validarSucursal;
        $respuesta = ControllerSucursales::ctrMostrarSucursal($item, $valor);
        echo json_encode($respuesta);
    }
}

/**=====================================
 * EDITAR CODIGO SUCURSAL
 * ======================================**/
if (isset($_POST["idSucursal"])) {
    $sucursales = new AjaxSucursales();
    $sucursales->idSucursal = $_POST["idSucursal"];
    $sucursales->ajaxEditarSucursal();
}

class ajaxNombres
{
    /**=====================================
     * EDITAR NOMBRE SUCURSAL
     * ======================================**/
    public $idNombre;
    public function ajaxEditarNombre()
    {
        $item = "id_sucursal";
        $valor = $this->idNombre;
        $respuesta = ControllerSucursales::ctrMostrarSucursal($item, $valor);
        echo json_encode($respuesta);
    }
}

/**=====================================
 * EDITAR NOMBRE SUCURSAL
 * ======================================**/
if (isset($_POST["idNombre"])) {
    $nombres = new AjaxNombres();
    $nombres->idNombre = $_POST["idNombre"];
    $nombres->ajaxEditarNombre();
}

/**=====================================
    NO REPETIR SUCURSALES
======================================**/
if (isset($_POST["validarSucursal"])){
    $valSucursal = new AjaxSucursales();
    $valSucursal -> validarSucursal = $_POST["validarSucursal"];
    $valSucursal -> ajaxValidarSucursal();
}