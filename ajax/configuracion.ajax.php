<?php

// 1. CONFIGURACIÓN INICIAL
header('Content-Type: application/json; charset=utf-8');
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// 2. CAPA DE SEGURIDAD CRÍTICA
if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] != "ok" || $_SESSION["perfil"] != "ADMINISTRADOR") {
    http_response_code(403); // Prohibido
    echo json_encode(["status" => "error", "message" => "Acceso denegado. Permisos insuficientes."]);
    exit(); 
}

// 3. CARGA DE DEPENDENCIAS
require_once "../controller/divisas.controller.php";
require_once "../model/divisas.model.php";

// 4. CLASE AJAX
class AjaxConfiguracion {
    public function ajaxActualizarTasaBCV() {
        try {
            $respuesta = ControllerDivisas::ctrActualizarTasaBCV();
            if (isset($respuesta['status']) && in_array($respuesta['status'], ['ok', 'info']) && isset($respuesta["tasa"])) {
                $_SESSION['config']['tasa_bcv'] = $respuesta["tasa"];
            }
            echo json_encode($respuesta);
        } catch (Exception $e) {
            http_response_code(500); // Error Interno del Servidor
            // error_log("Error fatal en ajaxActualizarTasaBCV: " . $e->getMessage());
            echo json_encode(["status" => "error", "message" => "Ocurrió un error interno inesperado en el servidor."]);
        }
    }
}

// 5. PUNTO DE ENTRADA (ROUTER)
if (isset($_POST["accion"]) && $_POST["accion"] == "actualizarTasaBCV") {
    $config = new AjaxConfiguracion();
    $config->ajaxActualizarTasaBCV();
} else {
    http_response_code(400); // Petición Incorrecta
    echo json_encode(["status" => "error", "message" => "Petición incorrecta: acción no especificada o no válida."]);
}