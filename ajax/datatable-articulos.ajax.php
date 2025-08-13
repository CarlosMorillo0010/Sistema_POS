<?php

require_once "../controller/articulos.controller.php";
require_once "../model/articulos.model.php";
require_once "../controller/marcas.controller.php";
require_once "../model/marcas.model.php";

class TablaArticulos{

    /**=====================================
        MOSTRAR TABLA DE ARTICULOS
    ======================================**/
    public function mostrarTablaArticulos(){

        $item = null;
        $valor = null;
        $articulos = ControllerArticulos::ctrMostrarArticulos($item, $valor);

        $datos = '{
            "data": [';

                for($i = 0; $i < count($articulos); $i++){

                    /**=====================================
                        TRAEMOS LA IMAGEN
                    ======================================**/
                    $imagen = "<img src='".$articulos[$i]["imagen"]."' width='40px'>";

                    /**=====================================
                        TRAEMOS LA MARCA
                    ======================================**/
                    $item = "id_marca";
                    $valor = $articulos[$i]["id"];
                    $marcas = ControllerMarcas::ctrMostrarMarca($item, $valor);

                    /**=====================================
                    TRAEMOS LAS ACCIONES DE BOTONES
                    ======================================**/
                    $botones =  "<div class='btn-group'><button class='btn btn-warning btnEditarArticulo' idArticulo='".$articulos[$i]["id_articulo"]."' data-toggle='modal' data-target='#modalEditarArticulo'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btnEliminarArticulo' idArticulo='".$articulos[$i]["id_articulo"]."' articulo='".$articulos[$i]["articulo"]."' imagen='".$articulos[$i]["imagen"]."'><i class='fa fa-times'></i></button></div>";

                    /**=====================================
                        UNIDADES
                    ======================================**/
                    if($articulos[$i]["unidades"] <= 10){
                        $unidades = "<button style='width:60px;' class='btn btn-danger'>".$articulos[$i]["unidades"]."</button>";
                    }else if($articulos[$i]["unidades"] > 11 && $articulos[$i]["unidades"] <= 15){
                        $unidades = "<button style='width:60px;' class='btn btn-warning'>".$articulos[$i]["unidades"]."</button>";
                    }else{
                        $unidades = "<button style='width:60px;' class='btn btn-success'>".$articulos[$i]["unidades"]."</button>";
                    }

                    $datos .='[
                        "'.($i+1).'",
                        "'.$imagen.'",
                        "'.$articulos[$i]["articulo"].'",
                        "'.$marcas["marca"].'",
                        "'.$unidades.'",
                        "'.$articulos[$i]["precio_compra"].'",
                        "'.$articulos[$i]["precio_venta"].'",
                        "'.$articulos[$i]["festamp"].'",
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
    ACTIVAR TABLA DE ARTICULOS
======================================**/
$activarArticulos = new TablaArticulos();
$activarArticulos -> mostrarTablaArticulos();