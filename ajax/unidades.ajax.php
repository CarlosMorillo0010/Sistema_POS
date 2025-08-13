<?php

require_once "../controller/unidades.controller.php";
require_once "../model/unidades.model.php";

class AjaxUnidades
{
    /**=====================================
     * EDITAR UNIDAD
     * ======================================**/
    public $idUnidad;

    public function ajaxEditarUnidad()
    {
        $item = "id_unidad";
        $valor = $this->idUnidad;
        $respuesta = ControllerUnidades::ctrMostrarUnidad($item, $valor);
        echo json_encode($respuesta);
    }
    /**=====================================
        NO REPETIR UNIDADES
    ======================================**/
    public $validarMedida;
    public function ajaxValidarMedida(){
        $item = "nombre";
        $valor = $this->validarMedida;
        $respuesta = ControllerUnidades::ctrMostrarUnidad($item, $valor);
        echo json_encode($respuesta);
    }
}

/**=====================================
 * EDITAR UNIDAD
 * ======================================**/
if (isset($_POST["idUnidad"])) {
    $unidad = new AjaxUnidades();
    $unidad->idUnidad = $_POST["idUnidad"];
    $unidad->ajaxEditarUnidad();
}

class AjaxMedidas
{
    /*=*=====================================
      EDITAR NOMBRE
     =======================================*/
    public $idMedida;
    public $traerMedidas;

    public function ajaxEditarMedida()
    {
        if ($this->traerMedidas == "OK"){
            $item = null;
            $valor = null;
            $respuesta = ControllerUnidades::ctrMostrarUnidad($item, $valor);
            echo json_encode($respuesta);
        }else if ($this->nombreUnidad != "")
        {
            $item = "unidad";
            $valor = $this->nombreUnidad;
            $orden = "codigo";
            $respuesta = ControllerUnidades::ctrMostrarUnidad($item, $valor);
            echo json_encode($respuesta);
        }else{
            $item = "id_unidad";
            $valor = $this->idMedida;
            $respuesta = ControllerUnidades::ctrMostrarUnidad($item, $valor);
            echo json_encode($respuesta);
        }
    }
}

/*======================================
 EDITAR NOMBRE
=======================================*/
if (isset($_POST["idMedida"])) {
    $editarMedida = new AjaxMedidas();
    $editarMedida->idMedida = $_POST["idMedida"];
    $editarMedida->ajaxEditarMedida();
}

/*======================================
 TRAER MEDIDAS
=======================================*/
if(isset($_POST["traerMedidas"])){
    $traerMedidas = new AjaxMedidas();
    $traerMedidas -> traerMedidas = $_POST["traerMedidas"];
    $traerMedidas -> ajaxEditarMedida();
}
if(isset($_POST["nombreUnidad"])){
    $traerMedidas = new AjaxMedidas();
    $traerMedidas -> nombreUnidad = $_POST["nombreUnidad"];
    $traerMedidas -> ajaxEditarMedida();
}


/**=====================================
    NO REPETIR UNIDADES
======================================**/
if (isset($_POST["validarMedida"])){
    $valMedida = new AjaxUnidades();
    $valMedida -> validarMedida = $_POST["validarMedida"];
    $valMedida -> ajaxValidarMedida();
}