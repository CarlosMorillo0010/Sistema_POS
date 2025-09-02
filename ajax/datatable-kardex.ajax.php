<?php

require_once "../controller/kardex.controller.php";
require_once "../model/kardex.model.php";

class TablaKardex{

    /*======================================
     MOSTRAR TABLA DE KARDEX VALORIZADO
    ======================================**/
    public function mostrarTablaKardex(){

        if (!isset($_GET["idProducto"]) || empty($_GET["idProducto"])) {
            echo '{"data": []}';
            return;
        }

        $item = "id_producto";
        $valor = $_GET["idProducto"];

        $fechaInicial = (isset($_GET["fechaInicial"]) && $_GET["fechaInicial"] != '') ? $_GET["fechaInicial"] : null;
        $fechaFinal = (isset($_GET["fechaFinal"]) && $_GET["fechaFinal"] != '') ? $_GET["fechaFinal"] : date("Y-m-d");

        // Get all movements up to fechaFinal to calculate the running stock and cost correctly
        $kardexCompleto = ControllerKardex::ctrMostrarKardex($item, $valor, null, $fechaFinal);

        if(empty($kardexCompleto)){
            echo '{"data": []}';
            return;
        }

        $cantidadSaldo = 0;
        $costoTotalSaldo = 0.00;
        $kardexProcesado = [];

        $fechaInicialDate = $fechaInicial ? new DateTime($fechaInicial) : null;

        // 1. Calculate Initial Balance
        if ($fechaInicialDate) {
            foreach ($kardexCompleto as $movimiento) {
                $fechaMovimientoDate = new DateTime($movimiento['fecha']);
                if ($fechaMovimientoDate < $fechaInicialDate) {
                    $tipo = $movimiento["tipo"];
                    $cantidad = $movimiento["cantidad"];
                    $precio = $movimiento["precio"];

                    if ($tipo == 'COMPRA' || $tipo == 'DEV. VENTA' || $tipo == 'ENTRADA') {
                        $cantidadSaldo += $cantidad;
                        $costoTotalSaldo += $cantidad * $precio;
                    } else { // VENTA, DEV. COMPRA, SALIDA
                        $costoUnitarioSalida = ($cantidadSaldo > 0) ? $costoTotalSaldo / $cantidadSaldo : 0;
                        $costoTotalSalida = $cantidad * $costoUnitarioSalida;
                        $cantidadSaldo -= $cantidad;
                        $costoTotalSaldo -= $costoTotalSalida;
                    }
                }
            }

            $costoUnitarioSaldo = ($cantidadSaldo != 0) ? $costoTotalSaldo / $cantidadSaldo : 0;
            $kardexProcesado[] = [
                "fecha" => date("d/m/Y", strtotime($fechaInicial . ' -1 day')),
                "documento" => "",
                "concepto" => "SALDO INICIAL",
                "ent_cant" => "", "ent_costo_u" => "", "ent_costo_t" => "",
                "sal_cant" => "", "sal_costo_u" => "", "sal_costo_t" => "",
                "sld_cant" => $cantidadSaldo,
                "sld_costo_u" => number_format($costoUnitarioSaldo, 2, ',', '.'),
                "sld_costo_t" => number_format($costoTotalSaldo, 2, ',', '.')
            ];
        }

        // 2. Process movements within the date range
        foreach ($kardexCompleto as $movimiento) {
            $fechaMovimientoDate = new DateTime($movimiento['fecha']);
            if (!$fechaInicialDate || $fechaMovimientoDate >= $fechaInicialDate) {
                $tipo = $movimiento["tipo"];
                $cantidad = $movimiento["cantidad"];
                $precio = $movimiento["precio"];

                $fila = [
                    "fecha" => date("d/m/Y", strtotime($movimiento['fecha'])),
                    "documento" => $movimiento["documento"],
                    "concepto" => $movimiento["tipo"],
                    "ent_cant" => "", "ent_costo_u" => "", "ent_costo_t" => "",
                    "sal_cant" => "", "sal_costo_u" => "", "sal_costo_t" => ""
                ];

                if ($tipo == 'COMPRA' || $tipo == 'DEV. VENTA' || $tipo == 'ENTRADA') {
                    $costoUnitarioEntrada = $precio;
                    $costoTotalEntrada = $cantidad * $costoUnitarioEntrada;
                    
                    $cantidadSaldo += $cantidad;
                    $costoTotalSaldo += $costoTotalEntrada;

                    $fila["ent_cant"] = $cantidad;
                    $fila["ent_costo_u"] = number_format($costoUnitarioEntrada, 2, ',', '.');
                    $fila["ent_costo_t"] = number_format($costoTotalEntrada, 2, ',', '.');

                } else { // VENTA, DEV. COMPRA, SALIDA
                    $costoUnitarioSalida = ($cantidadSaldo > 0) ? $costoTotalSaldo / $cantidadSaldo : 0;
                    $costoTotalSalida = $cantidad * $costoUnitarioSalida;

                    $cantidadSaldo -= $cantidad;
                    $costoTotalSaldo -= $costoTotalSalida;

                    $fila["sal_cant"] = $cantidad;
                    $fila["sal_costo_u"] = number_format($costoUnitarioSalida, 2, ',', '.');
                    $fila["sal_costo_t"] = number_format($costoTotalSalida, 2, ',', '.');
                }

                $costoUnitarioSaldo = ($cantidadSaldo != 0) ? $costoTotalSaldo / $cantidadSaldo : 0;
                $fila["sld_cant"] = $cantidadSaldo;
                $fila["sld_costo_u"] = number_format($costoUnitarioSaldo, 2, ',', '.');
                $fila["sld_costo_t"] = number_format($costoTotalSaldo, 2, ',', '.');

                $kardexProcesado[] = $fila;
            }
        }

        $datosJson = [];
        foreach ($kardexProcesado as $key => $value) {
            $datosJson[] = [
                ($key + 1),
                $value["fecha"],
                $value["documento"],
                $value["concepto"],
                $value["ent_cant"],
                $value["ent_costo_u"],
                $value["ent_costo_t"],
                $value["sal_cant"],
                $value["sal_costo_u"],
                $value["sal_costo_t"],
                $value["sld_cant"],
                $value["sld_costo_u"],
                $value["sld_costo_t"]
            ];
        }

        echo json_encode(["data" => $datosJson]);
    }
}

/*======================================
 ACTIVAR TABLA DE KARDEX
======================================**/
$activarKardex = new TablaKardex();
$activarKardex -> mostrarTablaKardex();

