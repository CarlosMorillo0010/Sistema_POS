<?php

require_once "../controller/ajustes.controller.php";
require_once "../model/ajustes.model.php";
// Si necesitas datos del usuario, también inclúyelos
// require_once "../controller/usuarios.controller.php";
// require_once "../model/usuarios.model.php";

class AjaxAjustes
{
    /**
     * ===============================================================
     * TRAER DETALLES DE UN AJUSTE PARA MOSTRAR EN EL MODAL
     * ===============================================================
     */
    public $idAjuste;

    public function ajaxVerDetalleAjuste()
    {
        // 1. Obtener los datos del encabezado del ajuste
        $itemHeader = "id_ajuste";
        $valorHeader = $this->idAjuste;
        $header = ControllerAjustes::ctrMostrarAjustes($itemHeader, $valorHeader);
        
        // 2. Obtener los detalles (los productos) del ajuste
        $itemDetails = "id_ajuste";
        $valorDetails = $this->idAjuste;
        $details = ControllerAjustes::ctrMostrarDetallesAjuste($itemDetails, $valorDetails);
        
        // 3. (Opcional) Obtener el nombre del usuario
        // $usuario = ControllerUsuarios::ctrMostrarUsuarios("id_usuario", $header["id_usuario"]);
        // $header["nombre_usuario"] = $usuario["nombre"];
        
        // 4. Combinar todo en una sola respuesta JSON
        $respuesta = [
            "header" => $header,
            "details" => $details
        ];
        
        echo json_encode($respuesta);
    }
}


// ===============================================================
// PUNTO DE ENTRADA: Se ejecuta cuando JS hace la petición
// ===============================================================
if (isset($_POST["idAjuste"])) {
    $verAjuste = new AjaxAjustes();
    $verAjuste->idAjuste = $_POST["idAjuste"];
    $verAjuste->ajaxVerDetalleAjuste();
}