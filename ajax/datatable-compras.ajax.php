<?php

require_once "../controller/compras.controller.php";
require_once "../model/compras.model.php";
require_once "../controller/proveedores.controller.php";
require_once "../model/proveedores.model.php";

class TablaCompras{

    /*======================================
     MOSTRAR TABLA DE COMPRAS
    ======================================**/
    public function mostrarTablaCompras(){

        $item = null;
        $valor = null;
        $compras = ControllerCompras::ctrMostrarCompras($item, $valor);

        $datos = '{
            "data": [';
                for($i = 0; $i < count($compras); $i++){

                    /*======================================
                     TRAEMOS AL PROVEEDOR
                    ======================================**/
                    $itemProveedor = "id_proveedor";
                    $valorProveedor = $compras[$i]["id_proveedor"];
                    $proveedor = ControllerProveedores::ctrMostrarProveedores($itemProveedor, $valorProveedor);

                    /*======================================
                     TRAEMOS LAS ACCIONES DE BOTONES
                    ======================================**/
                    $botones =  "<div class='btn-group'><button class='btn btn-primary btnAgregarCompra' idCompra='".$compras[$i]["id_compra"]."'><i class='fa fa-plus'></i></button></div>";

                    $datos .='[
                        "'.($i+1).'",
                        "'.$compras[$i]["id_compra"].'",
                        "'.$proveedor["nombre"].'",
                        "'.$compras[$i]["fecha_compra"].'",
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
 ACTIVAR TABLA DE COMPRAS
======================================**/
$activarCompras = new TablaCompras();
$activarCompras -> mostrarTablaCompras();