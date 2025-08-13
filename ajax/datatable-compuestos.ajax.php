<?php

require_once "../controller/compuests.controller.php";
require_once "../model/compuests.model.php";
require_once "../controller/categories.controller.php";
require_once "../model/categories.model.php";

class TablaCompuestos{

    /**=====================================
        MOSTRAR TABLA DE PRODUCTOS COMPUESTOS
    ======================================**/
    public function mostrarTablaCompuestos(){

        $item = null;
        $valor = null;
        $compuestos = ControllerCompuestos::ctrMostrarCompuesto($item, $valor);

        $datos = '{
            "data": [';

                for($i = 0; $i < count($compuestos); $i++){

                    /**=====================================
                        TRAEMOS LA IMAGEN
                    ======================================**/
                    $imagen = "<img src='".$compuestos[$i]["imagen"]."' width='40px'>";

                    /**=====================================
                        TRAEMOS LA CATEGORIA
                    ======================================**/
                    $item = "id_categoria";
                    $valor = $compuestos[$i]["id"];
                    $categorias = ControllerCategories::ctrMostrarCategoria($item, $valor);

                    /**=====================================
                    TRAEMOS LAS ACCIONES DE BOTONES
                    ======================================**/
                    $botones =  "<div class='btn-group'><button class='btn btn-warning btnEditarCompuesto' idCompuesto='".$compuestos[$i]["id_compuesto"]."' data-toggle='modal' data-target='#modalEditarProductoCompuesto'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btnEliminarCompuesto' idCompuesto='".$compuestos[$i]["id_compuesto"]."' codigo='".$compuestos[$i]["codigo"]."' imagen='".$compuestos[$i]["imagen"]."'><i class='fa fa-times'></i></button></div>";

                    /**=====================================
                        STOCK
                    ======================================**/
                    if($compuestos[$i]["stock"] <= 10){
                        $stock = "<button style='width:60px;' class='btn btn-danger'>".$compuestos[$i]["stock"]."</button>";
                    }else if($compuestos[$i]["stock"] > 11 && $compuestos[$i]["stock"] <= 15){
                        $stock = "<button style='width:60px;' class='btn btn-warning'>".$compuestos[$i]["stock"]."</button>";
                    }else{
                        $stock = "<button style='width:60px;' class='btn btn-success'>".$compuestos[$i]["stock"]."</button>";
                    }

                    $datos .='[
                        "'.($i+1).'",
                        "'.$imagen.'",
                        "'.$compuestos[$i]["codigo"].'",
                        "'.$compuestos[$i]["servicio"].'",
                        "'.$compuestos[$i]["descripcion"].'",
                        "'.$categorias["categoria"].'",
                        "'.$stock.'",
                        "'.$compuestos[$i]["unidad"].'",
                        "'.number_format($compuestos[$i]["precio_unitario_total"],2, ',','.').'",
                        "'.number_format($compuestos[$i]["precio_oferta_total"],2, ',', '.').'",
                        "'.$compuestos[$i]["feregistro"].'",
                        "'.$botones.'"
                    ],';
                }

                 $datos = substr($datos, 0, -1);

                $datos .=  '] 
            }';

            echo $datos;
        }
}

/**=====================================
    ACTIVAR TABLA DE PRODUCTOS COMPUESTOS
======================================**/
$activarCompuestos = new TablaCompuestos();
$activarCompuestos -> mostrarTablaCompuestos();