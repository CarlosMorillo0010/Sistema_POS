<?php

require_once "../controller/compuests.controller.php";
require_once "../model/compuests.model.php";

class TablaCompuestosCaja{

    /*======================================
     MOSTRAR TABLA DE PRODUCTOS COMPUESTOS
    ======================================**/
    public function mostrarTablaCompuestosCaja(){
        $item = null;
        $valor = null;
        $compuesto = ControllerCompuestos::ctrMostrarCompuesto($item, $valor);

        $dates = '{
            "data": [';
                for($a = 0; $a < count($compuesto); $a++){
                    /*======================================
                     TRAEMOS LA IMAGEN
                    ======================================**/
                    $imagen = "<img src='".$compuesto[$a]["imagen"]."' width='40px'>";

                    /*======================================
                     STOCK
                    ======================================**/
                    if($compuesto[$a]["stock"] <= 10){
                        $stock = "<button style='width:40px;' class='btn btn-danger'>".$compuesto[$a]["stock"]."</button>";
                    }else if($compuesto[$a]["stock"] > 11 && $compuesto[$a]["stock"] <= 15){
                        $stock = "<button style='width:40px;' class='btn btn-warning'>".$compuesto[$a]["stock"]."</button>";
                    }else{
                        $stock = "<button style='width:40px;' class='btn btn-success'>".$compuesto[$a]["stock"]."</button>";
                    }

                    /*======================================
                     TRAEMOS LAS ACCIONES DE BOTONES
                    ======================================**/
                    $botones =  "<div class='btn-group'><button class='btn btn-primary agregarProducto recuperarBoton' idProducto='".$compuesto[$a]["id_compuesto"]."'>Agregar</button></div>";

                    $dates .='[
                        "'.$imagen.'",
                        "'.$compuesto[$a]["codigo"].'",
                        "'.$compuesto[$a]["descripcion"].'",
                        "'.$stock.'",
                        "'.$botones.'"
                    ],';
                }
                $dates = substr($dates, 0, -1);
                $dates .='] 
            }';
            echo $dates;
    }
}

/*======================================
 ACTIVAR TABLA DE PRODUCTOS COMPUESTOS
======================================**/
$activarCompuestosCaja = new TablaCompuestosCaja();
$activarCompuestosCaja -> mostrarTablaCompuestosCaja();