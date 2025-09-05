<?php

class ControllerKardex
{
    static public function ctrMostrarKardex($item, $valor, $fechaInicial, $fechaFinal)
    {
        // El item "id_producto" no se usa directamente en el modelo final, 
        // pero lo mantenemos por consistencia. El modelo usa los parámetros directos.
        $respuesta = ModeloKardex::mdlMostrarKardex($valor, $fechaInicial, $fechaFinal);
        return $respuesta;
    }
}