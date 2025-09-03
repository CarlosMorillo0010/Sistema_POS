<?php

require_once "connection.php";

class ModeloKardex
{
    /**
     * Genera el Kardex valorizado leyendo la estructura de tu base de datos.
     * Lee: compras.fecha_compra, detalle_compras.cantidad, detalle_compras.precio_unitario
     * Lee: ventas.fecha, ventas.codigo_venta, detalle_ventas.cantidad
     */
    static public function mdlMostrarKardex($idProducto, $fechaInicial, $fechaFinal)
    {
        try {
            $pdo = Connection::connect();

            // --- FASE 1: CALCULAR SALDO INICIAL ---
            // Leemos de `compras` y `detalle_compras` para las entradas anteriores.
            // Leemos de `ventas` y `detalle_ventas` para las salidas anteriores.
            $stmtAnteriores = $pdo->prepare(
                "SELECT * FROM (
                    (SELECT c.fecha_compra as fecha, 'Compra' as tipo, dc.cantidad, dc.precio_unitario as valor
                     FROM compras c JOIN detalle_compras dc ON c.id_compra = dc.id_compra 
                     WHERE dc.id_producto = :id_producto AND c.fecha_compra < :fechaInicial)
                     UNION ALL
                    (SELECT v.fecha, 'Venta' as tipo, dv.cantidad, 0 as valor
                     FROM ventas v JOIN detalle_ventas dv ON v.id_venta = dv.id_venta 
                     WHERE dv.id_producto = :id_producto AND v.fecha < :fechaInicial)
                 ) AS movimientos_anteriores
                 ORDER BY fecha ASC, (CASE WHEN tipo = 'Compra' THEN 0 ELSE 1 END) ASC"
            );
            $stmtAnteriores->bindParam(":id_producto", $idProducto, PDO::PARAM_INT);
            $stmtAnteriores->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
            $stmtAnteriores->execute();
            $movimientos_anteriores = $stmtAnteriores->fetchAll(PDO::FETCH_ASSOC);

            $saldo_inicial_cantidad = 0.00;
            $saldo_inicial_valor_total = 0.00;
            foreach ($movimientos_anteriores as $mov) {
                if ($mov['tipo'] == 'Compra' && (float)$mov['valor'] > 0) {
                    $saldo_inicial_cantidad += (float)$mov['cantidad'];
                    // EL VALOR SE INCREMENTA GRACIAS A `detalle_compras.precio_unitario`
                    $saldo_inicial_valor_total += (float)$mov['cantidad'] * (float)$mov['valor'];
                } else { // Venta
                    $costo_unitario_salida = ($saldo_inicial_cantidad > 0) ? $saldo_inicial_valor_total / $saldo_inicial_cantidad : 0;
                    $saldo_inicial_cantidad -= (float)$mov['cantidad'];
                    $saldo_inicial_valor_total -= round((float)$mov['cantidad'] * $costo_unitario_salida, 2);
                }
            }
            $saldo_inicial_costo_unitario = ($saldo_inicial_cantidad > 0) ? $saldo_inicial_valor_total / $saldo_inicial_cantidad : 0;

            // --- FASE 2: OBTENER MOVIMIENTOS DEL PERIODO ---
            // Nuevamente, se usan los mismos nombres de campo que coinciden con tu BD.
            $stmtRango = $pdo->prepare(
                "SELECT * FROM (
                    (SELECT c.fecha_compra as fecha, 'Compra' as tipo, CONCAT('Orden de Compra - ', c.id_compra) as documento, dc.cantidad, dc.precio_unitario as valor
                     FROM compras c JOIN detalle_compras dc ON c.id_compra = dc.id_compra 
                     WHERE dc.id_producto = :id_producto AND c.fecha_compra BETWEEN :fechaInicial AND :fechaFinal)
                     UNION ALL
                    (SELECT v.fecha, 'Venta' as tipo, v.codigo_venta as documento, dv.cantidad, 0 as valor
                     FROM ventas v JOIN detalle_ventas dv ON v.id_venta = dv.id_venta 
                     WHERE dv.id_producto = :id_producto AND v.fecha BETWEEN :fechaInicial AND :fechaFinal)
                ) AS movimientos_rango
                ORDER BY fecha ASC, (CASE WHEN tipo = 'Compra' THEN 0 ELSE 1 END) ASC"
            );
            $stmtRango->bindParam(":id_producto", $idProducto, PDO::PARAM_INT);
            $stmtRango->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
            $stmtRango->bindParam(":fechaFinal", $fechaFinal, PDO::PARAM_STR);
            $stmtRango->execute();
            $movimientos_rango = $stmtRango->fetchAll(PDO::FETCH_ASSOC);

            // --- FASE 3: CONSTRUIR EL REPORTE FINAL ---
            $kardexProcesado = [];
            $saldo_cantidad_actual = $saldo_inicial_cantidad;
            $saldo_valor_total_actual = $saldo_inicial_valor_total;

            $kardexProcesado[] = [
                "fecha" => date("d/m/Y", strtotime($fechaInicial . ' -1 day')),
                "documento" => "",
                "concepto" => "SALDO INICIAL",
                "ent_cant" => "",
                "ent_costo_u" => "",
                "ent_costo_t" => "",
                "sal_cant" => "",
                "sal_costo_u" => "",
                "sal_costo_t" => "",
                "sld_cant" => $saldo_inicial_cantidad,
                "sld_costo_u" => $saldo_inicial_costo_unitario,
                "sld_costo_t" => $saldo_inicial_valor_total
            ];

            foreach ($movimientos_rango as $mov) {
                $fila = ["fecha" => date("d/m/Y", strtotime($mov['fecha'])), "documento" => $mov['documento']];

                if ($mov['tipo'] == 'Compra' && (float)$mov['valor'] > 0) {
                    $fila["concepto"] = 'COMPRA';
                    $cantidad_entrada = (float)$mov['cantidad'];
                    $costo_unitario_entrada = (float)$mov['valor']; // <-- Viene de `detalle_compras.precio_unitario`
                    $costo_total_entrada = $cantidad_entrada * $costo_unitario_entrada;

                    $saldo_cantidad_actual += $cantidad_entrada;
                    $saldo_valor_total_actual += $costo_total_entrada;

                    $fila += ["ent_cant" => $cantidad_entrada, "ent_costo_u" => $costo_unitario_entrada, "ent_costo_t" => $costo_total_entrada, "sal_cant" => "", "sal_costo_u" => "", "sal_costo_t" => ""];
                } else { // Venta
                    $fila["concepto"] = 'VENTA';
                    $cantidad_salida = (float)$mov['cantidad']; // <-- Viene de `detalle_ventas.cantidad`

                    $costo_unitario_salida = ($saldo_cantidad_actual > 0) ? $saldo_valor_total_actual / $saldo_cantidad_actual : 0;
                    $costo_total_salida = round($cantidad_salida * $costo_unitario_salida, 2);

                    $saldo_cantidad_actual -= $cantidad_salida;
                    $saldo_valor_total_actual -= $costo_total_salida;

                    $fila += ["ent_cant" => "", "ent_costo_u" => "", "ent_costo_t" => "", "sal_cant" => $cantidad_salida, "sal_costo_u" => $costo_unitario_salida, "sal_costo_t" => $costo_total_salida];
                }

                if ($saldo_cantidad_actual < 0.001) {
                    $saldo_cantidad_actual = 0;
                    $saldo_valor_total_actual = 0;
                }

                $costo_unitario_saldo = ($saldo_cantidad_actual > 0) ? $saldo_valor_total_actual / $saldo_cantidad_actual : 0;
                $fila += ["sld_cant" => $saldo_cantidad_actual, "sld_costo_u" => $costo_unitario_saldo, "sld_costo_t" => $saldo_valor_total_actual];

                $kardexProcesado[] = $fila;
            }
            return $kardexProcesado;
        } catch (Exception $e) {
            return [];
        }
    }
}
