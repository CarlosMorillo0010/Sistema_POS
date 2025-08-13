<?php

class ControllerInventarios
{

    /**=====================================
     * MOSTRAR PRODUCTOS
     * ======================================**/
    static public function ctrMostrarProductos($items, $valor, $orden)
    {
        $tabla = "productos";
        $respuesta = ModelProducts::mdlMostrarProductos($tabla, $items, $valor, $orden);
        return $respuesta;
    }
}