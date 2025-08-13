<?php

class ControllerPaises
{
    /*=============================================
    MOSTRAR PAISES
    =============================================*/
    static public function ctrMostrarPaises($item, $valor)
    {
        $tabla = "pais";
        $respuesta = ModelPaises::mdlMostrarPaises($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    MOSTRAR ESTADOS
    =============================================*/
    static public function ctrMostrarEstados($item, $valor)
    {
        $tabla = "estados";
        $respuesta = ModelPaises::mdlMostrarEstados($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    MOSTRAR CIUDADES
    =============================================*/
    static public function ctrMostrarCiudades($item, $valor)
    {
        $tabla = "ciudades";
        $respuesta = ModelPaises::mdlMostrarCiudades($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    MOSTRAR MUNICIPIOS
    =============================================*/
    static public function ctrMostrarMunicipios($item, $valor)
    {
        $tabla = "municipios";
        $respuesta = ModelPaises::mdlMostrarMunicipios($tabla, $item, $valor);
        return $respuesta;
    }
}