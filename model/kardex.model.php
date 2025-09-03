<?php

require_once "connection.php";

class ModeloKardex
{
    /**
     * Muestra el historial de movimientos (Kardex) para un producto específico dentro de un rango de fechas.
     * Calcula un saldo inicial basado en los movimientos anteriores a la fecha de inicio.
     * Utiliza el método de costo promedio ponderado para valorar las salidas.
     */
    static public function mdlMostrarKardex($item, $valor, $fechaInicial, $fechaFinal)
    {
        $pdo = Connection::connect();

        // --- 1. CÁLCULO DEL SALDO INICIAL (MOVIMIENTOS ANTERIORES A $fechaInicial) ---
        $stmtComprasAnt = $pdo->prepare(
            "SELECT c.fecha_compra as fecha, 'Compra' as tipo, CONCAT('OC-', c.id_compra) as documento, dc.cantidad, dc.precio_unitario as valor
             FROM compras c JOIN detalle_compras dc ON c.id_compra = dc.id_compra
             WHERE dc.id_producto = :id_producto AND c.fecha_compra < :fechaInicial"
        );
        $stmtComprasAnt->bindParam(":id_producto", $valor, PDO::PARAM_INT);
        $stmtComprasAnt->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
        $stmtComprasAnt->execute();
        $compras_ant = $stmtComprasAnt->fetchAll(PDO::FETCH_ASSOC);

        $stmtVentasAnt = $pdo->prepare(
            "SELECT v.fecha, 'Venta' as tipo, v.codigo_venta as documento, dv.cantidad, 0 as valor
             FROM ventas v JOIN detalle_ventas dv ON v.id_venta = dv.id_venta
             WHERE dv.id_producto = :id_producto AND v.fecha < :fechaInicial"
        );
        $stmtVentasAnt->bindParam(":id_producto", $valor, PDO::PARAM_INT);
        $stmtVentasAnt->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
        $stmtVentasAnt->execute();
        $ventas_ant = $stmtVentasAnt->fetchAll(PDO::FETCH_ASSOC);

        $movimientos_anteriores = array_merge($compras_ant, $ventas_ant);
        usort($movimientos_anteriores, function ($a, $b) {
            $fechaA = strtotime($a['fecha']);
            $fechaB = strtotime($b['fecha']);
            if ($fechaA == $fechaB) return ($a['tipo'] == 'Compra') ? -1 : 1;
            return $fechaA - $fechaB;
        });

        $saldo_inicial_cantidad = 0;
        $saldo_inicial_valor_total = 0;
        foreach ($movimientos_anteriores as $mov) {
            if ($mov['tipo'] == 'Compra') {
                $saldo_inicial_cantidad += $mov['cantidad'];
                $saldo_inicial_valor_total += $mov['cantidad'] * $mov['valor'];
            } else { // Venta
                $costo_unitario_salida = ($saldo_inicial_cantidad != 0) ? $saldo_inicial_valor_total / $saldo_inicial_cantidad : 0;
                $saldo_inicial_cantidad -= $mov['cantidad'];
                $saldo_inicial_valor_total -= $mov['cantidad'] * $costo_unitario_salida;
            }
        }
        $saldo_inicial_costo_unitario = ($saldo_inicial_cantidad != 0) ? $saldo_inicial_valor_total / $saldo_inicial_cantidad : 0;

        // --- 2. OBTENER MOVIMIENTOS DENTRO DEL RANGO DE FECHAS ---
        $fechaFinalMasUnDia = date('Y-m-d', strtotime($fechaFinal . ' +1 day'));

        $stmtCompras = $pdo->prepare(
            "SELECT c.fecha_compra as fecha, 'Compra' as tipo, CONCAT('OC-', c.id_compra) as documento, dc.cantidad, dc.precio_unitario as valor
             FROM compras c JOIN detalle_compras dc ON c.id_compra = dc.id_compra
             WHERE dc.id_producto = :id_producto AND c.fecha_compra >= :fechaInicial AND c.fecha_compra < :fechaFinal"
        );
        $stmtCompras->bindParam(":id_producto", $valor, PDO::PARAM_INT);
        $stmtCompras->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
        $stmtCompras->bindParam(":fechaFinal", $fechaFinalMasUnDia, PDO::PARAM_STR);
        $stmtCompras->execute();
        $compras = $stmtCompras->fetchAll(PDO::FETCH_ASSOC);

        $stmtVentas = $pdo->prepare(
            "SELECT v.fecha, 'Venta' as tipo, v.codigo_venta as documento, dv.cantidad, 0 as valor
             FROM ventas v JOIN detalle_ventas dv ON v.id_venta = dv.id_venta
             WHERE dv.id_producto = :id_producto AND v.fecha >= :fechaInicial AND v.fecha < :fechaFinal"
        );
        $stmtVentas->bindParam(":id_producto", $valor, PDO::PARAM_INT);
        $stmtVentas->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
        $stmtVentas->bindParam(":fechaFinal", $fechaFinalMasUnDia, PDO::PARAM_STR);
        $stmtVentas->execute();
        $ventas = $stmtVentas->fetchAll(PDO::FETCH_ASSOC);

        $movimientos_rango = array_merge($compras, $ventas);
        usort($movimientos_rango, function ($a, $b) {
            $fechaA = strtotime($a['fecha']);
            $fechaB = strtotime($b['fecha']);
            if ($fechaA == $fechaB) return ($a['tipo'] == 'Compra') ? -1 : 1;
            return $fechaA - $fechaB;
        });

        // --- 3. PROCESAR Y CONSTRUIR EL KARDEX ---
        $kardexProcesado = [];
        $saldo_cantidad = $saldo_inicial_cantidad;
        $saldo_valor_total = $saldo_inicial_valor_total;

        // Fila de Saldo Inicial
        $kardexProcesado[] = [
            "fecha" => date("d/m/Y", strtotime($fechaInicial)),
            "documento" => "",
            "concepto" => "SALDO INICIAL",
            "ent_cant" => "", "ent_costo_u" => "", "ent_costo_t" => "",
            "sal_cant" => "", "sal_costo_u" => "", "sal_costo_t" => "",
            "sld_cant" => $saldo_inicial_cantidad,
            "sld_costo_u" => $saldo_inicial_costo_unitario,
            "sld_costo_t" => $saldo_inicial_valor_total
        ];

        foreach ($movimientos_rango as $mov) {
            $fila = [
                "fecha" => date("d/m/Y", strtotime($mov['fecha'])),
                "documento" => $mov['documento'],
                "concepto" => $mov['tipo'],
                "ent_cant" => "", "ent_costo_u" => "", "ent_costo_t" => "",
                "sal_cant" => "", "sal_costo_u" => "", "sal_costo_t" => ""
            ];

            if ($mov['tipo'] == 'Compra') {
                $cantidad_entrada = floatval($mov['cantidad']);
                $costo_unitario_entrada = floatval($mov['valor']);
                $costo_total_entrada = $cantidad_entrada * $costo_unitario_entrada;

                $saldo_cantidad += $mov['cantidad'];
                $saldo_valor_total += $costo_total_entrada;

                $fila["ent_cant"] = $cantidad_entrada;
                $fila["ent_costo_u"] = $costo_unitario_entrada;
                $fila["ent_costo_t"] = $costo_total_entrada;
            } else { // Venta
                $cantidad_salida = floatval($mov['cantidad']);
                $costo_unitario_salida = ($saldo_cantidad != 0) ? $saldo_valor_total / $saldo_cantidad : 0;
                $costo_total_salida = $cantidad_salida * $costo_unitario_salida;

                $saldo_cantidad -= $cantidad_salida;
                $saldo_valor_total -= $costo_total_salida;

                $fila["sal_cant"] = $cantidad_salida;
                $fila["sal_costo_u"] = $costo_unitario_salida;
                $fila["sal_costo_t"] = $costo_total_salida;
            }

            $costo_unitario_saldo = ($saldo_cantidad != 0) ? $saldo_valor_total / $saldo_cantidad : 0;
            $fila["sld_cant"] = $saldo_cantidad;
            $fila["sld_costo_u"] = $costo_unitario_saldo;
            $fila["sld_costo_t"] = $saldo_valor_total;

            $kardexProcesado[] = $fila;
        }

        return $kardexProcesado;
    }
}