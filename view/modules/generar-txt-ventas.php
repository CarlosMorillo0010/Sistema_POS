<?php
require_once "../../controller/ventas-controller.php";
require_once "../../controller/clients.controller.php";
require_once "../../model/ventas-model.php";
require_once "../../model/clients.model.php";
require_once "../../controller/configuraciones.controller.php";
require_once "../../model/configuraciones.model.php";
require_once "../../model/connection.php";

// 1. OBTENER LAS FECHAS DESDE LA URL
if (isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])) {
    $fechaInicial = $_GET["fechaInicial"];
    $fechaFinal = $_GET["fechaFinal"];
} else {
    // Si no se proporcionan fechas, no hacer nada o mostrar un error
    die("Error: Rango de fechas no especificado.");
}

// OBTENER LOS DATOS DE LAS VENTAS
$ventas = ControllerVentas::ctrRangoFechasVentas($fechaInicial, $fechaFinal);

// OBTENER DATOS DE LA EMPRESA
$empresa = ControllerConfiguraciones::ctrMostrarConfiguracion();

if (empty($ventas)) {
    die("No hay ventas para el período seleccionado.");
}

//CONSTRUIR EL CONTENIDO DEL ARCHIVO TXT
$contenidoTxt = "";
$rifEmpresa = $empresa["rif"];

foreach ($ventas as $venta) {

    //saltar facturas anuladas o con total cero
    if ($venta["estado"] == "Anulada" || $venta["total_bs"] == 0) {
        continue; // Salta a la siguiente iteración del bucle
    }

    // Obtener datos del cliente (RIF)
    $cliente = ControllerClients::ctrMostrarClientes("id", $venta["id_cliente"]);
    
     // --- LÓGICA OPTIMIZADA PARA TIPO DE OPERACIÓN ---
    $tipoOperacion = 'N'; // Por defecto es No Contribuyente
    $tipoDocumentoCliente = '';
    
    if ($cliente && !empty($cliente["tipo_documento"])) {
        
        $tipoDocumentoCliente = strtoupper($cliente["tipo_documento"]); // Aseguramos que sea mayúscula
        
        // Verificamos si la letra corresponde a un Contribuyente
        if (in_array($tipoDocumentoCliente, ['J', 'G', 'C'])) {
            $tipoOperacion = 'C';
            // Si quieres ser aún más específico, podrías usar la letra G para Gobierno
            if ($tipoDocumentoCliente == 'G') {
                // $tipoOperacion = 'G'; // Descomenta si el formato del SENIAT te lo pide
            }
        }
        // Si es 'V' o 'E', se quedará como 'N', que es el valor por defecto.
    }

    // --- PROCESAR RIF DEL CLIENTE (Combinando tipo y número) ---
    $rifCliente = "V000000000"; // Consumidor final por defecto
    if ($cliente && !empty($cliente["documento"])) {
        // Limpiamos el número de documento de cualquier caracter no numérico
        $numeroDocumento = preg_replace('/[^0-9]/', '', $cliente["documento"]);
        // Construimos el RIF completo
        $rifCliente = $tipoDocumentoCliente . $numeroDocumento;
    }

    // Formatear la fecha de la venta
    $fechaFormateada = date("d/m/Y", strtotime($venta["fecha"]));
    $periodoImpositivo = date("Ym", strtotime($venta["fecha"]));
    
    // Formateo de Montos (sin puntos ni comas, 2 decimales implícitos)
    $totalConIva = number_format($venta["total_bs"], 2, '.', '');
    $baseImponible = number_format($venta["subtotal_bs"], 2, '.', '');
    $iva = number_format($venta["iva_bs"], 2, '.', '');
    $ventasExentas = number_format(0, 2, '.', '');
    $ivaRetenido = number_format(0, 2, '.', '');

    // Construir la línea para el archivo TXT, separada por tabulaciones (\t)
    $linea = [
        $rifEmpresa,                        // 1. RIF del Agente
        $periodoImpositivo,                 // 2. Período (YYYYMM)
        $fechaFormateada,                   // 3. Fecha (DD/MM/YYYY)
        $tipoOperacion,                     // <-- Usamos la variable calculada
        '01',                               // 5. Tipo de Documento (01=Factura)
        $rifCliente,                        // 6. RIF del Comprador
        $venta["codigo_venta"],           // 7. Número de Factura
        // $venta["numero_control"],           // 8. Número de Control
        $totalConIva,                       // 9. Monto Total
        $baseImponible,                     // 10. Base Imponible
        '0.00',                             // 11. Monto Exento (si no aplica)
        '0.00',                             // 12. Monto IVA Retenido (si no aplica)
        '0',                                // 13. Nro. de Comprobante de Retención
        $iva,                               // 14. IVA
    ];

    // Unir los campos con tabulaciones y añadir un salto de línea
    $contenidoTxt .= implode("\t", $linea) . "\r\n";
}

// ORZAR LA DESCARGA DEL ARCHIVO
$nombreArchivo = "LibroVentas_" . $periodoImpositivo . ".txt";
header('Content-Type: text/plain; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
header('Content-Length: ' . strlen($contenidoTxt));
echo $contenidoTxt;
exit();
?>