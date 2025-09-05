<?php

require_once "connection.php";

class ModeloKardex
{
    /**
     * Genera el Kardex valorizado usando el método de costo promedio móvil.
     * Este es el método contablemente correcto para un kardex perpetuo.
     */
    static public function mdlMostrarKardex($idProducto, $fechaInicial, $fechaFinal)
    {
        // Ajuste para incluir el día completo en la fecha final
        $fechaFinal = $fechaFinal . ' 23:59:59';

        try {
            $pdo = Connection::connect();

            // --- FASE 1: CALCULAR SALDO INICIAL (USANDO ÚLTIMO COSTO DE COMPRA) ---

            // 1.1. Calcular cantidad inicial
            $stmtCantidades = $pdo->prepare(
                "SELECT 
                    (SELECT COALESCE(SUM(dc.cantidad), 0) 
                     FROM compras c JOIN detalle_compras dc ON c.id_compra = dc.id_compra 
                     WHERE dc.id_producto = :id_producto AND c.fecha_compra < :fechaInicial)
                    -
                    (SELECT COALESCE(SUM(dv.cantidad), 0) 
                     FROM ventas v JOIN detalle_ventas dv ON v.id_venta = dv.id_venta 
                     WHERE dv.id_producto = :id_producto AND v.fecha < :fechaInicial)
                 AS cantidad_inicial"
            );
            $stmtCantidades->bindParam(":id_producto", $idProducto, PDO::PARAM_INT);
            $stmtCantidades->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
            $stmtCantidades->execute();
            $resultado = $stmtCantidades->fetch(PDO::FETCH_ASSOC);
            $saldo_inicial_cantidad = (float)($resultado['cantidad_inicial'] ?? 0.0);

            // 1.2. Obtener el último costo de compra
            $stmtUltimoCosto = $pdo->prepare(
                "SELECT dc.precio_unitario 
                 FROM compras c JOIN detalle_compras dc ON c.id_compra = dc.id_compra
                 WHERE dc.id_producto = :id_producto AND c.fecha_compra < :fechaInicial
                 ORDER BY c.fecha_compra DESC, c.id_compra DESC
                 LIMIT 1"
            );
            $stmtUltimoCosto->bindParam(":id_producto", $idProducto, PDO::PARAM_INT);
            $stmtUltimoCosto->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
            $stmtUltimoCosto->execute();
            $ultimo_costo = $stmtUltimoCosto->fetch(PDO::FETCH_ASSOC);
            $saldo_inicial_costo_unitario = (float)($ultimo_costo['precio_unitario'] ?? 0.0);

            // 1.3. Calcular el valor total inicial
            $saldo_inicial_valor_total = $saldo_inicial_cantidad * $saldo_inicial_costo_unitario;

            // --- FASE 2: OBTENER MOVIMIENTOS DEL PERIODO ---
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
                "ent_cant" => "", "ent_costo_u" => "", "ent_costo_t" => "",
                "sal_cant" => "", "sal_costo_u" => "", "sal_costo_t" => "",
                "sld_cant" => $saldo_inicial_cantidad,
                "sld_costo_u" => $saldo_inicial_costo_unitario,
                "sld_costo_t" => $saldo_inicial_valor_total
            ];

            foreach ($movimientos_rango as $mov) {
                $fila = ["fecha" => date("d/m/Y", strtotime($mov['fecha'])), "documento" => $mov['documento']];

                if ($mov['tipo'] == 'Compra') {
                    $fila["concepto"] = 'COMPRA';
                    $cantidad_entrada = (float)$mov['cantidad'];
                    $costo_unitario_entrada = (float)$mov['valor'];
                    $costo_total_entrada = $cantidad_entrada * $costo_unitario_entrada;

                    $saldo_cantidad_actual += $cantidad_entrada;
                    $saldo_valor_total_actual += $costo_total_entrada;

                    $fila += ["ent_cant" => $cantidad_entrada, "ent_costo_u" => $costo_unitario_entrada, "ent_costo_t" => $costo_total_entrada, "sal_cant" => "", "sal_costo_u" => "", "sal_costo_t" => ""];
                
                } else if ($mov['tipo'] == 'Venta') {
                    $fila["concepto"] = 'VENTA';
                    $cantidad_salida = (float)$mov['cantidad'];

                    $costo_unitario_salida = ($saldo_cantidad_actual > 0) ? $saldo_valor_total_actual / $saldo_cantidad_actual : 0;
                    $costo_total_salida = $cantidad_salida * $costo_unitario_salida;

                    $saldo_cantidad_actual -= $cantidad_salida;
                    $saldo_valor_total_actual -= $costo_total_salida;

                    $fila += ["ent_cant" => "", "ent_costo_u" => "", "ent_costo_t" => "", "sal_cant" => $cantidad_salida, "sal_costo_u" => $costo_unitario_salida, "sal_costo_t" => $costo_total_salida];
                }

                // Guarda contra saldos negativos por errores de redondeo o data.
                if ($saldo_cantidad_actual < 0.001) {
                    $saldo_cantidad_actual = 0;
                }
                if ($saldo_cantidad_actual == 0) {
                    $saldo_valor_total_actual = 0;
                }

                $costo_unitario_saldo = ($saldo_cantidad_actual > 0) ? $saldo_valor_total_actual / $saldo_cantidad_actual : 0;
                $fila += ["sld_cant" => $saldo_cantidad_actual, "sld_costo_u" => $costo_unitario_saldo, "sld_costo_t" => $saldo_valor_total_actual];

                $kardexProcesado[] = $fila;
            }
            return $kardexProcesado;

        } catch (Exception $e) {
            error_log("Error en Kardex: " . $e->getMessage());
            return [];
        }
    }
}