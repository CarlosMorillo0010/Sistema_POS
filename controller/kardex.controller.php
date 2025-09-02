<?php

class ControllerKardex
{
    static public function ctrMostrarKardex($item, $valor, $fechaInicial, $fechaFinal)
    {
        $respuesta = ModeloKardex::mdlMostrarKardex($item, $valor, $fechaInicial, $fechaFinal);
        return $respuesta;
    }
}