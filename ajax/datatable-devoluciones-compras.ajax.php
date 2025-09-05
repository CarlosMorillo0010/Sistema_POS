<?php

require_once "../controller/devoluciones-compras.controller.php";
require_once "../model/devoluciones-compras.model.php";

class TablaDevolucionesCompras{

    public function mostrarTablaDevolucionesCompras(){

        $item = null;
        $valor = null;

        $devoluciones = ControllerDevolucionesCompras::ctrMostrarDevolucionCompra($item, $valor);

        if(count($devoluciones) == 0){
            echo '{"data": []}';
            return;
        }

        $datosJson = '{
            "data": [';

        for($i = 0; $i < count($devoluciones); $i++){

            $acciones = "<div class='btn-group'><button class='btn btn-info btnImprimirDevolucionCompra' idDevolucion='".$devoluciones[$i]["id_devolucion_compra"]."'><i class='fa fa-print'></i></button></div>";

            $datosJson .='[
                "'.($i+1).'",
                "'.$devoluciones[$i]["id_compra"].'",
                "'.$devoluciones[$i]["proveedor"].'",
                "'.number_format($devoluciones[$i]["monto_total"], 2).'",
                "'.$devoluciones[$i]["fecha_devolucion"].'",
                "'.$acciones.'"
            ],';
        }

        $datosJson = substr($datosJson, 0, -1);
        $datosJson .= ']
        }';

        echo $datosJson;
    }
}

$activarDevoluciones = new TablaDevolucionesCompras();
$activarDevoluciones -> mostrarTablaDevolucionesCompras();
