<?php

require_once "../controller/ventas.controller.php";
require_once "../model/ventas.model.php";
require_once "../controller/clientes.controller.php";
require_once "../model/clientes.model.php";

class TablaVentas{

    /*======================================
     MOSTRAR TABLA DE VENTAS
    ======================================**/
    public function mostrarTablaVentas(){

        $item = null;
        $valor = null;
        $ventas = ControllerVentas::ctrMostrarVenta($item, $valor);

        $datos = '{
            "data": [';
                for($i = 0; $i < count($ventas); $i++){

                    /*======================================
                     TRAEMOS AL CLIENTE
                    ======================================**/
                    $itemCliente = "id";
                    $valorCliente = $ventas[$i]["id_cliente"];
                    $cliente = ControllerClients::ctrMostrarClientes($itemCliente, $valorCliente);

                    /*======================================
                     TRAEMOS LAS ACCIONES DE BOTONES
                    ======================================**/
                    $botones =  "<div class='btn-group'><button class='btn btn-primary btnAgregarVenta' idVenta='".$ventas[$i]["id_venta"]."'><i class='fa fa-plus'></i></button></div>";

                    $datos .='[
                        "'.($i+1).'",
                        "'.$ventas[$i]["codigo_venta"].'",
                        "'.$cliente["nombre"].'",
                        "'.$ventas[$i]["fecha"].'",
                        "'.$botones.'"
                    ],';
                }

                 $datos = substr($datos, 0, -1);
                $datos .=  '] 
            }';
            echo $datos;
        }
}

/*======================================
 ACTIVAR TABLA DE VENTAS
======================================**/
$activarVentas = new TablaVentas();
$activarVentas -> mostrarTablaVentas();