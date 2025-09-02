<?php

require_once "connection.php";

class ModeloKardex
{

    static public function mdlMostrarKardex($item, $valor, $fechaInicial, $fechaFinal)
    {
        $pdo = Connection::connect();

        $stmtCompras = $pdo->prepare(
            "SELECT c.fecha_compra as fecha, 'Compra' as tipo, c.id_compra as documento, dc.cantidad, dc.precio_unitario as valor
            FROM compras c JOIN detalle_compras dc ON c.id_compra = dc.id_compra
            WHERE dc.id_producto = :id_producto"
        );
        $stmtCompras->bindParam(":id_producto", $valor, PDO::PARAM_INT);
        $stmtCompras->execute();
        $compras = $stmtCompras->fetchAll(PDO::FETCH_ASSOC);

        $stmtVentas = $pdo->prepare(
            "SELECT fecha, 'Venta' as tipo,                 codigo_venta as documento, productos FROM ventas
            WHERE JSON_SEARCH(productos, 'one', :id_producto_str, NULL, '$[*].id_producto') IS NOT NULL"
        );
        $id_producto_str = strval($valor);
        $stmtVentas->bindParam(":id_producto_str", $id_producto_str, PDO::PARAM_STR);
        $stmtVentas->execute();
        $ventas_raw = $stmtVentas->fetchAll(PDO::FETCH_ASSOC);

        $ventas = [];
        foreach ($ventas_raw as $venta) {
            $productos_venta = json_decode($venta['productos'], true);
            foreach ($productos_venta as $producto) {
                if ($producto['id_producto'] == $valor) {
                    $ventas[] = [
                        'fecha' => $venta['fecha'],
                        'tipo' => $venta['tipo'],
                        'documento' => $venta['documento'],
                        'cantidad' => $producto['cantidad'],
                        'valor' => 0
                    ];
                }
            }
        }

        $movimientos = array_merge($compras, $ventas);

        usort($movimientos, function ($a, $b) {
            $fechaA = strtotime($a['fecha']);
            $fechaB = strtotime($b['fecha']);
            if ($fechaA == $fechaB) {
                if ($a['tipo'] == 'Compra' && $b['tipo'] != 'Compra') return -1;
                if ($a['tipo'] != 'Compra' && $b['tipo'] == 'Compra') return 1;
                return 0;
            }
            return $fechaA - $fechaB;
        });

        $kardex = [];
        $saldo_cantidad = 0;
        $saldo_valor = 0;
        $costo_promedio = 0;
        $saldo_inicial_calculado = false;

        foreach ($movimientos as $mov) {
            $fecha_movimiento = date('Y-m-d', strtotime($mov['fecha']));

            if ($fechaInicial && $fecha_movimiento < $fechaInicial) {
                if ($mov['tipo'] == 'Compra') {
                    $saldo_cantidad += $mov['cantidad'];
                    $saldo_valor += $mov['cantidad'] * $mov['valor'];
                } else { 
                    $costo_salida = ($saldo_cantidad > 0) ? $saldo_valor / $saldo_cantidad : 0;
                    $saldo_cantidad -= $mov['cantidad'];
                    $saldo_valor -= $mov['cantidad'] * $costo_salida;
                }
                $costo_promedio = ($saldo_cantidad > 0) ? $saldo_valor / $saldo_cantidad : 0;
                continue;
            }

            if ($fechaInicial && !$saldo_inicial_calculado) {
                $kardex[] = [
                    "fecha" => date("d/m/Y", strtotime($fechaInicial)),
                    "detalle" => "Saldo Inicial",
                    "v_unitario" => $costo_promedio,
                    "entrada_cant" => null,
                    "entrada_valor" => null,
                    "salida_cant" => null,
                    "salida_valor" => null,
                    "saldo_cant" => $saldo_cantidad,
                    "saldo_valor" => $saldo_valor
                ];
                $saldo_inicial_calculado = true;
            }
            
            if ($fechaFinal && $fecha_movimiento > $fechaFinal) {
                continue;
            }

            $fila = [];
            $fila["fecha"] = date("d/m/Y", strtotime($mov['fecha']));
            $fila["detalle"] = $mov['tipo'] . ' / Doc: ' . $mov['documento'];

            if ($mov['tipo'] == 'Compra') {
                $fila["v_unitario"] = $mov['valor'];
                $fila["entrada_cant"] = $mov['cantidad'];
                $fila["entrada_valor"] = $mov['cantidad'] * $mov['valor'];
                $fila["salida_cant"] = null;
                $fila["salida_valor"] = null;

                $saldo_cantidad += $fila["entrada_cant"];
                $saldo_valor += $fila["entrada_valor"];
            } else { // Venta
                $costo_unitario_salida = ($saldo_cantidad > 0) ? $saldo_valor / $saldo_cantidad : $costo_promedio;

                $fila["v_unitario"] = $costo_unitario_salida;
                $fila["entrada_cant"] = null;
                $fila["entrada_valor"] = null;
                $fila["salida_cant"] = $mov['cantidad'];
                $fila["salida_valor"] = $mov['cantidad'] * $costo_unitario_salida;

                $saldo_cantidad -= $fila["salida_cant"];
                $saldo_valor -= $fila["salida_valor"];
            }

            $fila["saldo_cant"] = $saldo_cantidad;
            $fila["saldo_valor"] = $saldo_valor;
            
            $costo_promedio = ($saldo_cantidad > 0) ? $saldo_valor / $saldo_cantidad : 0;

            $kardex[] = $fila;
        }
        
        if ($fechaInicial && !$saldo_inicial_calculado && count($kardex) == 0) {
             $kardex[] = [
                "fecha" => date("d/m/Y", strtotime($fechaInicial)),
                "detalle" => "Saldo Inicial",
                "v_unitario" => $costo_promedio,
                "entrada_cant" => null,
                "entrada_valor" => null,
                "salida_cant" => null,
                "salida_valor" => null,
                "saldo_cant" => $saldo_cantidad,
                "saldo_valor" => $saldo_valor
            ];
        }

        return $kardex;
    }
}
