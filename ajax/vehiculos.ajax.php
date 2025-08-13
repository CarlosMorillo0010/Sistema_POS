<?php
require_once "../controller/vehiculos.controller.php";
require_once "../model/vehiculos.model.php";

class AjaxVehiculos
{
    /**=====================================
     * EDITAR VEHICULO
     * ======================================**/
    public $idVehiculo;
    public function ajaxEditarVehiculo()
    {
        $item = "id_vehiculo";
        $valor = $this->idVehiculo;
        $respuesta = ControllerVehiculos::ctrMostrarVehiculos($item, $valor);
        echo json_encode($respuesta);
    }

}

/**=====================================
EDITAR VEHICULOS
======================================**/
if(isset($_POST["idVehiculo"])){
    $vehiculo = new AjaxVehiculos();
    $vehiculo -> idVehiculo = $_POST["idVehiculo"];
    $vehiculo -> ajaxEditarVehiculo();
}