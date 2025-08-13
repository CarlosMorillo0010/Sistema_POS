<?php

class ControllerFacturasGastos
{
    /*=============================================
    MOSTRAR FACTURA GASTOS
    =============================================*/
    static public function ctrMostrarFacturasGastos($item, $valor)
    {
        $tabla = "facturas_gastos";
        $respuesta = ModelFacturasGastos::mdlMostrarFacturasGastos($tabla, $item, $valor);
        return $respuesta;
    }
}