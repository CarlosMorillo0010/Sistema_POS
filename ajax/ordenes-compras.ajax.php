<?php

require_once "../controller/ordenes-compras.controller.php";
require_once "../model/ordenes-compras.model.php";
require_once "../controller/proveedores.controller.php";
require_once "../model/proveedores.model.php";

class AjaxOrdenesCompras
{
    public $idOrdenEstado;
    public $nuevoEstado;
    public $idOrdenVer;

    public function ajaxCambiarEstado()
    {
        $tabla = "orden_compra";
        $item1 = "estado";
        $valor1 = $this->nuevoEstado;
        $item2 = "id_orden_compra";
        $valor2 = $this->idOrdenEstado;

        $respuesta = ModelOrdenesCompras::mdlActualizarOrden($tabla, $item1, $valor1, $item2, $valor2);

        echo $respuesta;
    }

    public function ajaxVerDetalleOrden()
    {
        $item = "id_orden_compra";
        $valor = $this->idOrdenVer;
        $orden = ControllerOrdenesCompras::ctrMostrarOrdenCompra($item, $valor);

        $itemProveedor = "id_proveedor";
        $valorProveedor = $orden["id_proveedor"];
        $proveedor = ControllerProveedores::ctrMostrarProveedores($itemProveedor, $valorProveedor);

        $detalle = ModelOrdenesCompras::mdlMostrarOrdenCompraDetalle("orden_compra_detalle", "id_orden_compra", $valor);

        $html = '<div class="row">';
        $html .= '<div class="col-xs-6">';
        $html .= '<p><strong>Proveedor:</strong> '.$proveedor["nombre"].'</p>';
        $html .= '<p><strong>Fecha:</strong> '.date("d/m/Y", strtotime($orden["fecha"])).'</p>';
        $html .= '<p><strong>Estado:</strong> '.$orden["estado"].'</p>';
        $html .= '</div>';
        $html .= '<div class="col-xs-6">';
        $html .= '<p><strong>Subtotal:</strong> $'.number_format($orden["subtotal"], 2).'</p>';
        $html .= '<p><strong>Impuestos:</strong> $'.number_format($orden["impuestos"], 2).'</p>';
        $html .= '<p><strong>Descuento:</strong> $'.number_format($orden["descuento"], 2).'</p>';
        $html .= '<p><strong>Envío:</strong> $'.number_format($orden["costo_envio"], 2).'</p>';
        $html .= '<p><strong>Total:</strong> $'.number_format($orden["total"], 2).'</p>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<hr>';
        $html .= '<h4>Productos</h4>';
        $html .= '<table class="table table-bordered">';
        $html .= '<thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead>';
        $html .= '<tbody>';

        foreach($detalle as $producto){
            $html .= '<tr>';
            $html .= '<td>'.$producto["descripcion"].'</td>';
            $html .= '<td>'.$producto["cantidad"].'</td>';
            $html .= '<td>$'.number_format($producto["precio_compra"], 2).'</td>';
            $html .= '<td>$'.number_format($producto["subtotal"], 2).'</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        echo $html;
    }
}

if (isset($_POST["idOrdenEstado"])) {
    $cambiarEstado = new AjaxOrdenesCompras();
    $cambiarEstado->idOrdenEstado = $_POST["idOrdenEstado"];
    $cambiarEstado->nuevoEstado = $_POST["nuevoEstado"];
    $cambiarEstado->ajaxCambiarEstado();
}

if (isset($_POST["idOrdenVer"])) {
    $verOrden = new AjaxOrdenesCompras();
    $verOrden->idOrdenVer = $_POST["idOrdenVer"];
    $verOrden->ajaxVerDetalleOrden();
}
