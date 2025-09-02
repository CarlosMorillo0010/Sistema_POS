<?php

require_once "../controller/kardex.controller.php";
require_once "../model/kardex.model.php";
require_once "../model/connection.php";

class TablaKardex{

    public function mostrarTablaKardex(){

        // Validar que los datos necesarios lleguen por POST
        if (!isset($_POST["id_producto"]) || empty($_POST["id_producto"])) {
            echo '{"data": []}';
            return;
        }

        $item = "id_producto";
        $valor = $_POST["id_producto"];
        $fechaInicial = $_POST["fechaInicial"] ?? date("Y-m-d");
        $fechaFinal = $_POST["fechaFinal"] ?? date("Y-m-d");

        $kardexData = ControllerKardex::ctrMostrarKardex($item, $valor, $fechaInicial, $fechaFinal);

        if(empty($kardexData)){
            echo '{"data": []}';
            return;
        }

        $datosJson = [];
        $contador = 1;
        foreach ($kardexData as $key => $value) {
            $datosJson[] = [
                $contador++,
                $value["fecha"],
                $value["documento"],
                $value["concepto"],
                $value["ent_cant"] !== "" ? number_format($value["ent_cant"], 2, ',', '.') : "",
                $value["ent_costo_u"] !== "" ? number_format($value["ent_costo_u"], 2, ',', '.') : "",
                $value["ent_costo_t"] !== "" ? number_format($value["ent_costo_t"], 2, ',', '.') : "",
                $value["sal_cant"] !== "" ? number_format($value["sal_cant"], 2, ',', '.') : "",
                $value["sal_costo_u"] !== "" ? number_format($value["sal_costo_u"], 2, ',', '.') : "",
                $value["sal_costo_t"] !== "" ? number_format($value["sal_costo_t"], 2, ',', '.') : "",
                number_format($value["sld_cant"], 2, ',', '.'),
                number_format($value["sld_costo_u"], 2, ',', '.'),
                number_format($value["sld_costo_t"], 2, ',', '.')
            ];
        }

        echo json_encode(["data" => $datosJson]);
    }
}

$activarKardex = new TablaKardex();
$activarKardex -> mostrarTablaKardex();
