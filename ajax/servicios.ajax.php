<?php

require_once "../controller/services.controller.php";
require_once "../model/services.model.php";

class ajaxServicies{
    /**=====================================
    EDITAR SERVICIO
    ======================================**/

    public $idServicio;
    public function ajaxEditarServicio(){
        $item = "id_servicio";
        $valor = $this->idServicio;
        $respuesta = ControllerServices::ctrMostrarServicio($item,$valor);
        echo json_encode($respuesta);
    }

    /**=====================================
        NO REPETIR SERVICIO
    ======================================**/
    public $validarService;
    public function ajaxValidarService(){
        $item = "servicio";
        $valor = $this->validarService;
        $respuesta = ControllerServices::ctrMostrarServicio($item,$valor);
        echo json_encode($respuesta);
    }
}

/**=====================================
EDITAR SERVICIO
======================================**/

if (isset($_POST["idServicio"])){
    $servicios = new AjaxServicies();
    $servicios -> idServicio = $_POST["idServicio"];
    $servicios -> ajaxEditarServicio();
}

/**=====================================
    NO REPETIR SERVICIOS
======================================**/
if (isset($_POST["validarService"])){
    $valService = new AjaxServicies();
    $valService -> validarService = $_POST["validarService"];
    $valService -> ajaxValidarService();
}

