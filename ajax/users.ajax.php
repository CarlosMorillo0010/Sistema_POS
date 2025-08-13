<?php
require_once "../controller/users.controller.php";
require_once "../model/users.model.php";

class AjaxUsers{
    /**=====================================
        EDITAR USUARIOS
    ======================================**/
    public $idUsuario;
    public function ajaxEditarUsuario(){
        $item = "id_usuario";
        $valor = $this->idUsuario;
        $respuesta = ControllerUsers::ctrMostrarUsuario($item, $valor);
        echo json_encode($respuesta);
    }
    /**=====================================
        ACTIVAR USUARIOS
    ======================================**/
    public $activarUsuario;
    public $activarId;

    public function ajaxActivarUsuario(){
        $tabla = "usuarios";
        $item1 = "status";
        $valor1 =  $this->activarUsuario;
        $item2 = "id_usuario";
        $valor2 = $this->activarId;
        $respuesta = ModelUsers::mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2);
    }
    /**=====================================
        NO REPETIR USUARIOS REGISTRADO
    ======================================**/
    public $validarUsuario;
    public function ajaxValidarUsuario(){
        $item = "documento";
        $valor = $this->validarUsuario;
        $respuesta = ControllerUsers::ctrMostrarUsuario($item, $valor);
        echo json_encode($respuesta);
    }
}
/**=====================================
    EDITAR USUARIOS
======================================**/
if (isset($_POST["idUsuario"])){
    $editar = new AjaxUsers();
    $editar -> idUsuario = $_POST["idUsuario"];
    $editar -> ajaxEditarUsuario();
}
/**=====================================
    ACTIVAR USUARIOS
======================================**/
if (isset($_POST["activarUsuario"])){
    $activarUsuario = new AjaxUsers();
    $activarUsuario -> activarUsuario = $_POST["activarUsuario"];
    $activarUsuario -> activarId = $_POST["activarId"];
    $activarUsuario -> ajaxActivarUsuario();
}
/**=====================================
    NO REPETIR USUARIOS REGISTRADO
======================================**/
if (isset($_POST["validarUsuario"])){
    $valUsuario = new AjaxUsers();
    $valUsuario -> validarUsuario = $_POST["validarUsuario"];
    $valUsuario -> ajaxValidarUsuario();
}