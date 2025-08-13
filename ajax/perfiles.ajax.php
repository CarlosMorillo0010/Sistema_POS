<?php
require_once "../controller/perfiles.controller.php";
require_once "../model/perfiles.model.php";

class AjaxPerfiles
{
    /*======================================
     EDITAR PERFILES
    ======================================**/
    public $idPerfil;

    public function ajaxEditarPerfil()
    {
        $item = "id_perfil";
        $valor = $this->idPerfil;
        $respuesta = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
        echo json_encode($respuesta);
    }

    /**=====================================
    ACTIVAR MÓDULOS
    ======================================**/
    public $activarMantenimiento;
    public $activarIdMantenimiento;

    public function ajaxActivarMantenimiento(){
        $tabla = "perfiles";
        $item1 = "mantenimiento";
        $valor1 =  $this->activarMantenimiento;
        $item2 = "id_perfil";
        $valor2 = $this->activarIdMantenimiento;
        $respuesta = ModelPerfiles::mdlActualizarPerfil($tabla, $item1, $valor1, $item2, $valor2);
    }

    public $activarInventario;
    public $activarIdInventario;

    public function ajaxActivarInventario(){
        $tabla = "perfiles";
        $item1 = "inventario";
        $valor1 =  $this->activarInventario;
        $item2 = "id_perfil";
        $valor2 = $this->activarIdInventario;
        $respuesta = ModelPerfiles::mdlActualizarPerfil($tabla, $item1, $valor1, $item2, $valor2);
    }

    public $activarCompra;
    public $activarIdCompra;
    public function ajaxActivarCompra(){
        $tabla = "perfiles";
        $item1 = "compras";
        $valor1 =  $this->activarCompra;
        $item2 = "id_perfil";
        $valor2 = $this->activarIdCompra;
        $respuesta = ModelPerfiles::mdlActualizarPerfil($tabla, $item1, $valor1, $item2, $valor2);
    }

    public $activarVenta;
    public $activarIdVenta;
    public function ajaxActivarVenta(){
        $tabla = "perfiles";
        $item1 = "ventas";
        $valor1 =  $this->activarVenta;
        $item2 = "id_perfil";
        $valor2 = $this->activarIdVenta;
        $respuesta = ModelPerfiles::mdlActualizarPerfil($tabla, $item1, $valor1, $item2, $valor2);
    }

    public $activarReporteCompra;
    public $activarIdReporteCompra;
    public function ajaxActivarReporteCompra(){
        $tabla = "perfiles";
        $item1 = "reporte_compra";
        $valor1 =  $this->activarReporteCompra;
        $item2 = "id_perfil";
        $valor2 = $this->activarIdReporteCompra;
        $respuesta = ModelPerfiles::mdlActualizarPerfil($tabla, $item1, $valor1, $item2, $valor2);
    }

    public $activarReporteVenta;
    public $activarIdReporteVenta;
    public function ajaxActivarReporteVenta(){
        $tabla = "perfiles";
        $item1 = "reporte_venta";
        $valor1 =  $this->activarReporteVenta;
        $item2 = "id_perfil";
        $valor2 = $this->activarIdReporteVenta;
        $respuesta = ModelPerfiles::mdlActualizarPerfil($tabla, $item1, $valor1, $item2, $valor2);
    }

    public $activarConfiguracion;
    public $activarIdConfiguracion;
    public function ajaxActivarConfiguracion(){
        $tabla = "perfiles";
        $item1 = "configuracion";
        $valor1 =  $this->activarConfiguracion;
        $item2 = "id_perfil";
        $valor2 = $this->activarIdConfiguracion;
        $respuesta = ModelPerfiles::mdlActualizarPerfil($tabla, $item1, $valor1, $item2, $valor2);
    }

    public $activarCaja;
    public $activarIdCaja;
    public function ajaxActivarCaja(){
        $tabla = "perfiles";
        $item1 = "caja";
        $valor1 =  $this->activarCaja;
        $item2 = "id_perfil";
        $valor2 = $this->activarIdCaja;
        $respuesta = ModelPerfiles::mdlActualizarPerfil($tabla, $item1, $valor1, $item2, $valor2);
    }
}

/*======================================
 EDITAR PERFILES
======================================**/
if(isset($_POST["idPerfil"])){
    $perfil = new AjaxPerfiles();
    $perfil -> idPerfil = $_POST["idPerfil"];
    $perfil->ajaxEditarPerfil();
}

/**=====================================
ACTIVAR MÓDULOS
======================================**/
if (isset($_POST["activarMantenimiento"])){
    $activarModulos = new AjaxPerfiles();
    $activarModulos -> activarMantenimiento = $_POST["activarMantenimiento"];
    $activarModulos -> activarIdMantenimiento = $_POST["activarIdMantenimiento"];
    $activarModulos -> ajaxActivarMantenimiento();
}
if (isset($_POST["activarInventario"])){
    $activarModulos = new AjaxPerfiles();
    $activarModulos -> activarInventario = $_POST["activarInventario"];
    $activarModulos -> activarIdInventario = $_POST["activarIdInventario"];
    $activarModulos -> ajaxActivarInventario();
}
if (isset($_POST["activarCompra"])){
    $activarModulos = new AjaxPerfiles();
    $activarModulos -> activarCompra = $_POST["activarCompra"];
    $activarModulos -> activarIdCompra = $_POST["activarIdCompra"];
    $activarModulos -> ajaxActivarCompra();
}
if (isset($_POST["activarVenta"])){
    $activarModulos = new AjaxPerfiles();
    $activarModulos -> activarVenta = $_POST["activarVenta"];
    $activarModulos -> activarIdVenta = $_POST["activarIdVenta"];
    $activarModulos -> ajaxActivarVenta();
}
if (isset($_POST["activarReporteCompra"])){
    $activarModulos = new AjaxPerfiles();
    $activarModulos -> activarReporteCompra = $_POST["activarReporteCompra"];
    $activarModulos -> activarIdReporteCompra = $_POST["activarIdReporteCompra"];
    $activarModulos -> ajaxActivarReporteCompra();
}
if (isset($_POST["activarReporteVenta"])){
    $activarModulos = new AjaxPerfiles();
    $activarModulos -> activarReporteVenta = $_POST["activarReporteVenta"];
    $activarModulos -> activarIdReporteVenta = $_POST["activarIdReporteVenta"];
    $activarModulos -> ajaxActivarReporteVenta();
}
if (isset($_POST["activarConfiguracion"])){
    $activarModulos = new AjaxPerfiles();
    $activarModulos -> activarConfiguracion = $_POST["activarConfiguracion"];
    $activarModulos -> activarIdConfiguracion = $_POST["activarIdConfiguracion"];
    $activarModulos -> ajaxActivarConfiguracion();
}
if (isset($_POST["activarCaja"])){
    $activarModulos = new AjaxPerfiles();
    $activarModulos -> activarCaja = $_POST["activarCaja"];
    $activarModulos -> activarIdCaja = $_POST["activarIdCaja"];
    $activarModulos -> ajaxActivarCaja();
}