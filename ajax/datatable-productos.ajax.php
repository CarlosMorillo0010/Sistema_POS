<?php

require_once "../controller/products.controller.php";
require_once "../model/products.model.php";
require_once "../controller/categories.controller.php";
require_once "../model/categories.model.php";

class TablaProductos{

    /*======================================
     MOSTRAR TABLA DE PRODUCTOS
    ======================================**/
    public function mostrarTablaProductos(){

        $item = null;
        $valor = null;
        $orden = "id_producto";
        $productos = ControllerProducts::ctrMostrarProductos($item, $valor, $orden);

        $datos = '{
            "data": [';
                for($i = 0; $i < count($productos); $i++){
                    /*======================================
                        TRAEMOS LA IMAGEN
                    ======================================**/
                    $imagen = "<img src='".$productos[$i]["imagen"]."' width='40px'>";

                    /*======================================
                     TRAEMOS LA CATEGORIA
                    ======================================**/
                    $item = "id_categoria";
                    $valor = $productos[$i]["id_categoria"];
                    $categorias = ControllerCategories::ctrMostrarCategoria($item, $valor);

                    /*======================================
                     TRAEMOS LAS ACCIONES DE BOTONES
                    ======================================**/
                    $botones =  "<div class='btn-group'><button class='btn btn-warning btnEditarProducto' idProducto='".$productos[$i]["id_producto"]."' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btnEliminarProducto' idProducto='".$productos[$i]["id_producto"]."' codigo='".$productos[$i]["codigo"]."' imagen='".$productos[$i]["imagen"]."'><i class='fa fa-times'></i></button></div>";

                    /*======================================
                     STOCK
                    ======================================**/
                    if($productos[$i]["stock"] <= 10){
                        $stock = "<button style='width:60px;' class='btn btn-danger'>".$productos[$i]["stock"]."</button>";
                    }else if($productos[$i]["stock"] > 11 && $productos[$i]["stock"] <= 15){
                        $stock = "<button style='width:60px;' class='btn btn-warning'>".$productos[$i]["stock"]."</button>";
                    }else{
                        $stock = "<button style='width:60px;' class='btn btn-success'>".$productos[$i]["stock"]."</button>";
                    }

                    $datos .='[
                        "'.($i+1).'",
                        "'.$imagen.'",
                        "'.$productos[$i]["descripcion"].'",
                        "'.$productos[$i]["marca"].'",
                        "'.$productos[$i]["modelo"].'",
                        "'.$categorias["categoria"].'",
                        "'.$productos[$i]["codigo"].'",
                        "'.$stock.'",
                        "'.number_format($productos[$i]["pvp_referencia"],2, ',','.').'",
                        "'.number_format($productos[$i]["pvp"],2, ',','.').'",
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
 ACTIVAR TABLA DE PRODUCTOS
======================================**/
$activarProductos = new TablaProductos();
$activarProductos -> mostrarTablaProductos();