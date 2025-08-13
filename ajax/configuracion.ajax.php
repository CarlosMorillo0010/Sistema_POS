<?php
// ajax/configuracion.ajax.php

// Inicia la sesión al principio para poder acceder a $_SESSION
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ajusta las rutas según la estructura de tu proyecto
require_once "../controller/divisas.controller.php";
require_once "../model/divisas.model.php";
require_once "../model/connection.php";

class AjaxConfiguracion {

    /**
     * Maneja la solicitud AJAX para actualizar la tasa del BCV.
     * Llama al controlador, procesa la respuesta y la devuelve como JSON.
     */
    public function ajaxActualizarTasaBCV() {
        // 1. Llama al método del controlador que hace todo el trabajo pesado.
        $respuestaBCV = ControllerDivisas::ctrActualizarTasaDesdeBCV();
        
        // 2. Comprueba si la operación en el controlador fue exitosa.
        if (isset($respuestaBCV["status"]) && $respuestaBCV["status"] === "ok") {
            
            // 3. ¡CLAVE! Actualiza la variable de sesión en tiempo real.
            // Esto asegura que cualquier página nueva que se abra use el valor correcto.
            $_SESSION['config']['tasa_bcv'] = $respuestaBCV["tasa"];
            
            // 4. Prepara la respuesta JSON de éxito para el cliente.
            $respuesta = [
                "status" => "ok",
                "mensaje" => "Tasa del BCV actualizada exitosamente a " . number_format($respuestaBCV["tasa"], 2, ',', '.'),
                "config" => [
                    "tasa_bcv" => $respuestaBCV["tasa"],
                    "iva_porcentaje" => $_SESSION['config']['iva_porcentaje'] ?? 16.00 // Enviamos otras configs relevantes
                ]
            ];

        } else {
            // Si el controlador devolvió un error, lo pasamos al cliente.
            $respuesta = [
                "status" => "error",
                "mensaje" => $respuestaBCV["message"] ?? "Ocurrió un error desconocido al procesar la tasa."
            ];
        }

        // 5. Envía la respuesta final en formato JSON.
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($respuesta);
    }
}

// Punto de entrada para la llamada AJAX.
// Verifica que la solicitud venga con la acción correcta.
if (isset($_POST["accion"]) && $_POST["accion"] == "actualizarTasaBCV") {
    $config = new AjaxConfiguracion();
    $config->ajaxActualizarTasaBCV();
}