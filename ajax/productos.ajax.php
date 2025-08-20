<?php

require_once "../controller/products.controller.php";
require_once "../model/products.model.php";
require_once "../controller/categories.controller.php";
require_once "../model/categories.model.php";

// =====================================================================
// NUEVO: PROCESAR BÚSQUEDA DE PRODUCTOS
// =====================================================================
if (isset($_POST["terminoBusqueda"])) {

    $termino = $_POST["terminoBusqueda"];
    $respuesta = ControllerProducts::ctrBuscarProductos($termino);
    echo json_encode($respuesta);
    return; // Detenemos la ejecución aquí

}


// =====================================================================
// PROCESAR FILTRO POR CATEGORÍA (CÓDIGO EXISTENTE)
// =====================================================================
if (isset($_POST["idCategoriaFiltro"])) {
    
    $idCategoria = $_POST["idCategoriaFiltro"];

    if ($idCategoria == "todos") {
        $item = null;
        $valor = null;
    } else {
        $item = "p.id_categoria";
        $valor = $idCategoria;
    }
    
    $orden = "id_producto DESC";
    
    $respuesta = ControllerProducts::ctrMostrarProductosCaja($item, $valor, $orden);
    echo json_encode($respuesta);
    return; // Detenemos la ejecución aquí
}


// =====================================================================
// OTROS MÉTODOS AJAX (CÓDIGO EXISTENTE)
// =====================================================================
class AjaxProductos{

    public $idProducto;
    public $idCategoria;
    public $traerArticulos;
    public $nombreProducto;
    public $validarCodigo;

    public function ajaxEditarProducto(){
        $item = "id_producto";
        $valor = $this->idProducto;
        $orden = "id_producto";
        $respuesta = ControllerProducts::ctrMostrarProductos($item, $valor, $orden);
        echo json_encode($respuesta);
    }

    public function ajaxCrearCodigoProducto(){
        $item = "id_categoria";
        $valor = $this->idCategoria;
        $orden = "codigo";
        $respuesta = ControllerProducts::ctrMostrarProductos($item, $valor, $orden);
        echo json_encode($respuesta);
    }

    public function ajaxValidarCodigo(){
        $item = "codigo";
        $valor = $this->validarCodigo;
        $orden = "id_producto";
        $respuesta = ControllerProducts::ctrMostrarProductos($item, $valor, $orden);
        echo json_encode($respuesta);
    }

    public function ajaxTraerProductos() {
        if ($this->traerArticulos == "OK") {
            $item = null;
            $valor = null;
            $orden = "id_producto DESC";
        } else {
            $item = "descripcion";
            $valor = $this->nombreProducto;
            $orden = "id_producto DESC";
        }
        $respuesta = ControllerProducts::ctrMostrarProductosCaja($item, $valor, $orden);
        echo json_encode($respuesta);
    }
}

if(isset($_POST["idProducto"])){
    $editarProducto = new AjaxProductos();
    $editarProducto->idProducto = $_POST["idProducto"];
    $editarProducto->ajaxEditarProducto();
}

if(isset($_POST["idCategoria"])){
    $codigoProducto = new AjaxProductos();
    $codigoProducto->idCategoria = $_POST["idCategoria"];
    $codigoProducto->ajaxCrearCodigoProducto();
}

if(isset($_POST["validarCodigo"])){
    $validarCodigo = new AjaxProductos();
    $validarCodigo->validarCodigo = $_POST["validarCodigo"];
    $validarCodigo->ajaxValidarCodigo();
}

if(isset($_POST["traerArticulos"]) || isset($_POST["nombreProducto"])){
    $traerProductos = new AjaxProductos();
    $traerProductos->traerArticulos = $_POST["traerArticulos"] ?? null;
    $traerProductos->nombreProducto = $_POST["nombreProducto"] ?? null;
    $traerProductos->ajaxTraerProductos();
}