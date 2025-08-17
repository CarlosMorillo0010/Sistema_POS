<?php

// Habilitar la visualización de errores para depuración (opcional, comentar en producción final)
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// Rutas a los archivos del controlador y modelo. ¡Asegúrate de que sean correctas!
require_once "../controller/clients.controller.php";
require_once "../model/clients.model.php";

/**
 * Clase AjaxClients
 * Maneja todas las peticiones AJAX relacionadas con los clientes.
 */
class AjaxClients
{
    /*======================================
	 PROPIEDADES PARA LA CLASE
	 ======================================*/
    public $idCliente;
    public $traerClientes;
    public $nombreCliente;

    /**
     * Propiedad para buscar por cédula/documento.
     * @var string
     */
    public $cedulaCliente;

    /*===================================================================
	 MÉTODO CENTRALIZADO PARA MOSTRAR/BUSCAR CLIENTES
	 Este método actúa como un enrutador basado en las propiedades
	 que se le hayan asignado al objeto.
	 ===================================================================*/
    public function ajaxMostrarClientes()
    {
        // Caso 1: Buscar cliente por su documento (cédula/RIF) - para el POS
        if (isset($this->cedulaCliente)) {
            $item = "documento"; // La columna en tu base de datos
            $valor = $this->cedulaCliente;

        // Caso 2: Buscar cliente por su ID (para editar)
        } else if (isset($this->idCliente)) {
            $item = "id";
            $valor = $this->idCliente;
            
        // Caso 3: Traer TODOS los clientes (usado en otras partes del sistema)
        } else if (isset($this->traerClientes) && $this->traerClientes == "OK") {
            $item = null;
            $valor = null;
            
        // Caso 4: Buscar cliente por nombre
        } else if (isset($this->nombreCliente)) {
            $item = "nombre";
            $valor = $this->nombreCliente;
            
        // Si no se cumple ninguna condición, no hacemos nada para evitar errores.
        } else {
            echo json_encode(null);
            return;
        }

        // Ejecuta la consulta a través del controlador
        $respuesta = ControllerClients::ctrMostrarClientes($item, $valor);
        
        // Devuelve la respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
}

/*===================================================================
 PUNTOS DE ENTRADA (DISPATCHER)
 Se activa el bloque correspondiente según la variable $_POST recibida.
===================================================================*/

// NUEVO: Se activa al buscar desde el POS por Cédula/RIF
if (isset($_POST["buscarCedula"])) {
    $clienteAjax = new AjaxClients();
    $clienteAjax->cedulaCliente = $_POST["buscarCedula"];
    $clienteAjax->ajaxMostrarClientes();
}

// Se activa al editar un cliente y cargar sus datos en el modal
else if (isset($_POST["idCliente"])) {
    $clienteAjax = new AjaxClients();
    $clienteAjax->idCliente = $_POST["idCliente"];
    $clienteAjax->ajaxMostrarClientes();
}

// Se activa para cargar la lista completa de clientes (ej. en Datatables)
else if (isset($_POST["traerClientes"])) {
    $clienteAjax = new AjaxClients();
    $clienteAjax->traerClientes = $_POST["traerClientes"];
    $clienteAjax->ajaxMostrarClientes();
}

// Se activa al buscar por nombre (si tienes esa funcionalidad en otra parte)
else if (isset($_POST["nombreCliente"])) {
    $clienteAjax = new AjaxClients();
    $clienteAjax->nombreCliente = $_POST["nombreCliente"];
    $clienteAjax->ajaxMostrarClientes();
}