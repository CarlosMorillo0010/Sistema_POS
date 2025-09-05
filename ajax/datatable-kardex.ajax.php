<?php
header('Content-Type: application/json; charset=utf-8');

require_once "../controller/kardex.controller.php"; // El controlador actúa como intermediario
require_once "../model/kardex.model.php";

class TablaKardex
{
    public function mostrarTablaKardex()
    {
        // 1. Validar rigurosamente los datos de entrada
        if (!isset($_POST["id_producto"]) || !is_numeric($_POST["id_producto"]) || $_POST["id_producto"] <= 0) {
            echo json_encode(["data" => []]);
            return;
        }
        if (!isset($_POST["fechaInicial"]) || !isset($_POST["fechaFinal"]) || empty($_POST["fechaInicial"]) || empty($_POST["fechaFinal"])) {
            echo json_encode(["data" => []]);
            return;
        }
        
        $idProducto = $_POST["id_producto"];
        $fechaInicial = $_POST["fechaInicial"];
        $fechaFinal = $_POST["fechaFinal"];
        
        // 2. Llamar al controlador para obtener los datos ya calculados por el modelo
        $kardexData = ControllerKardex::ctrMostrarKardex("id_producto", $idProducto, $fechaInicial, $fechaFinal);

        if (empty($kardexData)) {
            echo json_encode(["data" => []]);
            return;
        }

        // 3. Formatear la salida para que se vea bien en la tabla
        $datosJson = [];
        $contador = 1;
        $moneda = ""; 

        foreach ($kardexData as $value) {
            $esSaldoInicial = ($value["concepto"] === "SALDO INICIAL");
            
            $datosJson[] = [
                $contador++,
                $value["fecha"],
                $value["documento"],
                $esSaldoInicial ? "<strong>".$value["concepto"]."</strong>" : $value["concepto"],
                // Entradas
                !empty($value["ent_cant"]) ? number_format($value["ent_cant"], 2, ',', '.') : "",
                !empty($value["ent_costo_u"]) ? $moneda . number_format($value["ent_costo_u"], 2, ',', '.') : "",
                !empty($value["ent_costo_t"]) ? $moneda . number_format($value["ent_costo_t"], 2, ',', '.') : "",
                // Salidas
                !empty($value["sal_cant"]) ? number_format($value["sal_cant"], 2, ',', '.') : "",
                !empty($value["sal_costo_u"]) ? $moneda . number_format($value["sal_costo_u"], 2, ',', '.') : "",
                !empty($value["sal_costo_t"]) ? $moneda . number_format($value["sal_costo_t"], 2, ',', '.') : "",
                // Saldos
                $esSaldoInicial ? "<strong>".number_format($value["sld_cant"], 2, ',', '.')."</strong>" : number_format($value["sld_cant"], 2, ',', '.'),
                $esSaldoInicial ? "<strong>".$moneda . number_format($value["sld_costo_u"], 2, ',', '.')."</strong>" : $moneda . number_format($value["sld_costo_u"], 2, ',', '.'),
                $esSaldoInicial ? "<strong>".$moneda . number_format($value["sld_costo_t"], 2, ',', '.')."</strong>" : $moneda . number_format($value["sld_costo_t"], 2, ',', '.')
            ];
        }

        echo json_encode(["data" => $datosJson]);
    }
}

$activarKardex = new TablaKardex();
$activarKardex->mostrarTablaKardex();