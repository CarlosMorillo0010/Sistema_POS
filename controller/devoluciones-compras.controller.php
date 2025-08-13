<?php

class ControllerDevolucionesCompras
{
    /*=============================================
    MOSTRAR FACTURA COMPRA
    =============================================*/
    static public function ctrMostrarDevolucionCompra($item, $valor)
    {
        $tabla = "devoluciones_compras";
        $respuesta = ModelDevolucionesCompras::mdlMostrarDevolucionCompra($tabla, $item, $valor);
        return $respuesta;
    }
}