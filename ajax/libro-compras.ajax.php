<?php

require_once "../controller/libro-compras.controller.php";
require_once "../model/libro-compras.model.php";
require_once "../controller/proveedores.controller.php";
require_once "../model/proveedores.model.php";

class ajaxLibrosCompras
{
    /**=====================================
     * EDITAR LIBRO DE COMPRAS
     * ======================================**/
    public $idLibroCompra;
    public function ajaxEditarLibroCompra()
    {
        $item = "id";
        $valor = $this->idLibroCompra;
        $respuesta = ControllerLibroCompras::ctrMostrarLibroCompras($item, $valor);
        echo json_encode($respuesta);
    }
}

/**=====================================
 * EDITAR LIBRO DE COMPRAS
 * ======================================**/

if (isset($_POST["idLibroCompra"])) {
    $libroCompras = new ajaxLibrosCompras();
    $libroCompras->idLibroCompra = $_POST["idLibroCompra"];
    $libroCompras->ajaxEditarLibroCompra();
}

if (isset($_GET["id_proveedor"])) {
    $item = "id_proveedor";
    $valor = $_GET["id_proveedor"];
    $proveedor = ControllerProveedores::ctrMostrarProveedores($item, $valor);
    echo json_encode($proveedor);
}