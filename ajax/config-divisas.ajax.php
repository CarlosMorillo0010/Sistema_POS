<?php
require_once "../controller/perfiles.controller.php";
require_once "../model/perfiles.model.php";

class AjaxPerfiles
{
    /**=====================================
     * EDITAR PERFILES
     * ======================================**/
    public $idPerfil;

    public function ajaxEditarPerfil()
    {
        $item = "id_perfil";
        $valor = $this->idPerfil;
        $respuesta = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
        echo json_encode($respuesta);
    }
}

/**=====================================
EDITAR PERFILES
======================================**/
if(isset($_POST["idPerfil"])){
    $perfil = new AjaxPerfiles();
    $perfil -> idPerfil = $_POST["idPerfil"];
    $perfil->ajaxEditarPerfil();
}