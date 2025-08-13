<?php

require_once "../controller/products.controller.php";
require_once "../model/products.model.php";

class TablaProductosCaja{

    /*======================================
     MOSTRAR TABLA DE PRODUCTOS TERMINADOS
    ======================================**/
    public function mostrarTablaProductosCaja(){
        $item = null;
        $valor = null;
        $orden = "codigo";
        $productos = ControllerProducts::ctrMostrarProductos($item, $valor, $orden);

        $datos = '{
            "data": [';
                for($i = 0; $i < count($productos); $i++){
                    /*======================================
                     TRAEMOS LA IMAGEN
                    ======================================**/
                    $imagen = "<img src='".$productos[$i]["imagen"]."' width='40px'>";

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

                    /*======================================
                     TRAEMOS LAS ACCIONES DE BOTONES
                    ======================================**/
                    $botones =  "<div class='btn-group'><button class='btn btn-primary agregarProducto recuperarBoton' idProducto='".$productos[$i]["id_producto"]."'>Agregar</button></div>";

                    $datos .='[
                        "'.$imagen.'",
                        "'.$productos[$i]["codigo"].'",
                        "'.$productos[$i]["descripcion"].'",
                        "'.$stock.'",
                        "'.$botones.'"
                    ],';
                }
                $datos = substr($datos, 0, -1);
                $datos .='] 
            }';
            echo $datos;
    }
}

/*======================================
 ACTIVAR TABLA DE PRODUCTOS
======================================**/
$activarProductosCaja = new TablaProductosCaja();
$activarProductosCaja -> mostrarTablaProductosCaja();