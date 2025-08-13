<?php
// Necesario para acceder a las variables de sesión
session_start(); 

// Requerimos los archivos necesarios. Ajusta las rutas si es necesario.
require_once "../controller/divisas.controller.php";
require_once "../model/divisas.model.php";
require_once "../model/connection.php";

class AjaxTasaCambio {
    public function ajaxActualizarTasa() {
        header('Content-Type: application/json');
        $respuesta = ControllerDivisas::ctrActualizarTasaBCV();
        echo json_encode($respuesta);
    }
}

// Solo los administradores pueden ejecutar esto
if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok" && $_SESSION["perfil"] == "ADMINISTRADOR") {
    $actualizador = new AjaxTasaCambio();
    $actualizador->ajaxActualizarTasa();
} else {
    // Si no es un admin, devolver un error de autorización
    header('Content-Type: application/json');
    http_response_code(403); // Forbidden
    echo json_encode(["status" => "error", "message" => "Acceso no autorizado."]);
}