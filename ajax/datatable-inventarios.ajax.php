<?php

require_once "../controller/products.controller.php";
require_once "../model/products.model.php";
require_once "../controller/categories.controller.php";
require_once "../model/categories.model.php";

class TablaProductos{

    public function mostrarTablaProductos(){
        $item = null;
        $valor = null;
        $orden = "id_producto";
        $productos = ControllerProducts::ctrMostrarProductos($item, $valor, $orden);

        // Si no hay productos, devuelve un JSON vacío para evitar errores
        if(count($productos) == 0){
            echo json_encode(["data" => []]);
            return;
        }

        $datosJSON = []; // Usaremos un array para construir los datos

        for($i = 0; $i < count($productos); $i++){
            // TRAEMOS LA IMAGEN
            $imagen = "<img src='".$productos[$i]["imagen"]."' width='40px'>";

            // TRAEMOS LA CATEGORIA
            $itemCat = "id_categoria";
            $valorCat = $productos[$i]["id_categoria"];
            $categorias = ControllerCategories::ctrMostrarCategoria($itemCat, $valorCat);

            // STOCK
            if($productos[$i]["stock"] <= 10){
                $stock = "<button style='width:60px;' class='btn btn-danger'>".$productos[$i]["stock"]."</button>";
            } else if($productos[$i]["stock"] > 11 && $productos[$i]["stock"] <= 15){
                $stock = "<button style='width:60px;' class='btn btn-warning'>".$productos[$i]["stock"]."</button>";
            } else {
                $stock = "<button style='width:60px;' class='btn btn-success'>".$productos[$i]["stock"]."</button>";
            }

            // ACCIONES
            // $botones =  "<div class='btn-group'><button class='btn btn-warning btnEditarProducto' idProducto='".$productos[$i]["id_producto"]."' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btnEliminarProducto' idProducto='".$productos[$i]["id_producto"]."' codigo='".$productos[$i]["codigo"]."' imagen='".$productos[$i]["imagen"]."'><i class='fa fa-times'></i></button></div>";
            
            // Construimos un array para cada fila
            $fila = [
                ($i+1),
                $imagen,
                $productos[$i]["descripcion"],
                $productos[$i]["marca"],
                $productos[$i]["modelo"],
                $productos[$i]["ano"],
                $categorias["categoria"],
                $productos[$i]["codigo"],
                $stock,
                $productos[$i]["pvp_referencia"],
                $productos[$i]["pvp"],
                // $botones
            ];

            $datosJSON[] = $fila; // Agregamos la fila al array principal
        }

        // Usamos json_encode para crear el JSON de forma segura
        header('Content-Type: application/json');
        echo json_encode(["data" => $datosJSON]);
    }
}

$activarProductos = new TablaProductos();
$activarProductos -> mostrarTablaProductos();