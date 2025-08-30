<?php
session_start();

require_once "../extensions/tcpdf/tcpdf.php";
require_once "../controller/ventas-controller.php";
require_once "../controller/clients.controller.php";
require_once "../controller/configuraciones.controller.php";
require_once "../controller/divisas.controller.php";
require_once "../model/ventas-model.php";
require_once "../model/clients.model.php";
require_once "../model/configuraciones.model.php";
require_once "../model/connection.php";
require_once "../model/divisas.model.php";

if (isset($_GET["venta"])) {

    // --- 1. OBTENER DATOS ---
    $empresa = ControllerConfiguraciones::ctrMostrarConfiguracion();
    $idVenta = $_GET["venta"];
    $venta = ControllerVentas::ctrMostrarVentaDetallada("id_venta", $idVenta);

    if (!$empresa || !$venta) {
        die("Error: No se pudieron cargar los datos necesarios para generar el ticket.");
    }
    
    // Datos de la venta y configuración
    $tasaBCV = $venta['tasa_dia'];
    $ivaPorcentaje = $_SESSION['config']['iva_porcentaje'] ?? 16.00;

    // --- 2. CLASE PERSONALIZADA PARA HEADER Y FOOTER ---
    class MYPDF extends TCPDF
    {
        public $empresa;
        public $venta;

        public function setTicketData($empresa, $venta)
        {
            $this->empresa = $empresa;
            $this->venta = $venta;
        }

        // El Header se genera automáticamente en cada página nueva
        // No lo usaremos para la información de la factura, ya que solo debe aparecer una vez.
        public function Header() {}

        // El Footer personalizado
        public function Footer()
        {
            $this->SetY(-25); // Posición a 25mm del final
            $this->SetFont('courier', '', 8);
            $this->Cell(0, 0, str_repeat('=', 64), 0, 1, 'C');
            $this->SetFont('courier', 'B', 9);
            $this->Cell(0, 5, '¡Gracias por su compra!', 0, 1, 'C');
            $this->SetFont('courier', '', 8);
            // Asegúrate de que tu array $empresa tenga 'website' y 'social'
            if (!empty($this->empresa['website'])) {
                $this->Cell(0, 4, 'Visitanos en ' . $this->empresa['website'], 0, 1, 'C');
            }
            if (!empty($this->empresa['social'])) {
                $this->Cell(0, 4, 'SÍGUENOS EN NUESTRAS REDES SOCIALES ' . strtoupper($this->empresa['social']), 0, 1, 'C');
            }
            $this->Cell(0, 0, str_repeat('=', 64), 0, 1, 'C');
        }
    }

    // --- 3. CREACIÓN DEL DOCUMENTO PDF ---
    // Tamaño del ticket 100mm de ancho. La altura puede variar.
    $pdf = new MYPDF('P', 'mm', array(100, 350)); 
    $pdf->setTicketData($empresa, $venta);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('TuSistemaPOS');
    $pdf->SetTitle('Factura ' . $venta['codigo_venta']);
    
    // Márgenes: Izquierda, Arriba, Derecha
    $pdf->SetMargins(5, 5, 5); 
    // Eliminar el margen del header y footer automáticos
    $pdf->SetHeaderMargin(0);
    $pdf->SetFooterMargin(10); // Margen para nuestro footer personalizado
    $pdf->SetAutoPageBreak(TRUE, 30); // Margen inferior para el salto de página
    
    $pdf->AddPage();

    // --- 4. ENCABEZADO DE LA FACTURA (LO PONEMOS AQUÍ MANUALMENTE) ---
    $pdf->SetFont('courier', 'B', 10);
    $pdf->MultiCell(0, 5, strtoupper($empresa['nombre']), 0, 'C', false, 1);
    $pdf->SetFont('courier', '', 8);
    $pdf->MultiCell(0, 4, $empresa['direccion'], 0, 'C', false, 1);
    $pdf->Cell(0, 4, 'RIF: ' . $empresa['rif'], 0, 1, 'C');
    $pdf->Cell(0, 4, 'Teléfono: ' . $empresa['telefono'], 0, 1, 'C');
    $pdf->Ln(2);
    $pdf->Cell(0, 0, str_repeat('-', 64), 0, 1, 'C');
    
    // --- INFORMACIÓN DE LA FACTURA ---
    // Usaremos celdas para alinear el texto en dos columnas
    $pdf->SetFont('courier', '', 8);
    $pdf->Cell(35, 4, 'FACTURA: ' . $venta['codigo_venta'], 0, 0, 'L');
    // **DATO DE EJEMPLO**: El número de control fiscal debe venir de tu sistema
    $pdf->Cell(35, 4, 'CONTROL: 2025/0A-0000' . $venta['id_venta'], 0, 1, 'L');
    $pdf->Cell(35, 4, 'FECHA: ' . date("d/m/Y", strtotime($venta['fecha'])), 0, 0, 'L');
    $pdf->Cell(35, 4, 'HORA: ' . date("h:i A", strtotime($venta['fecha'])), 0, 1, 'L');
        $pdf->Cell(70, 4, 'CAJERO: ' . $venta['vendedor'], 0, 1, 'L');
    
    $clienteInfo = $venta['tipo_documento'] . '-' . $venta['documento'];
    if (strtoupper($venta['nombre']) == 'GENERICO') {
        $clienteInfo .= ' (No indicó)';
    }
    $pdf->Cell(0, 4, 'CLIENTE: ' . $clienteInfo, 0, 1, 'L');
    $pdf->Cell(0, 0, str_repeat('-', 64), 0, 1, 'C');
    
    // --- 5. TABLA DE PRODUCTOS ---
    $pdf->SetFont('courier', '', 8);
    // Cabecera de la tabla
    $pdf->Cell(35, 4, 'DESCRIPCIÓN', 0, 0, 'L');
    $pdf->Cell(1, 4, '', 0, 0); // Gap
    $pdf->Cell(10, 4, 'CANT', 0, 0, 'C');
    $pdf->Cell(1, 4, '', 0, 0); // Gap
    $pdf->Cell(20, 4, 'PRECIO(Bs)', 0, 0, 'R');
    $pdf->Cell(1, 4, '', 0, 0); // Gap
    $pdf->Cell(22, 4, 'TOTAL(Bs)', 0, 1, 'R');
    $pdf->Cell(0, 0, str_repeat('-', 90), 0, 1, 'C');
    $pdf->Ln(1);

    // Variables para calcular los totales
    $totalArticulos = 0;
    $subtotalBs = 0;
    $baseImponibleG = 0;
    $baseImponibleE = 0;
    $montoNoSujeto = 0;

    $pdf->SetFont('courier', '', 7.5); // Fuente más pequeña para los productos

    // Abreviaturas para descripciones largas
    $abreviaturas = [
        'SUPERIOR' => 'SUP', 'DERECHA' => 'DER', 'IZQUIERDA' => 'IZQ',
        'DELANTERO' => 'DEL', 'TRASERO' => 'TRAS', 'INFERIOR' => 'INF',
        'EXTERNO' => 'EXT', 'INTERNO' => 'INT', 'JUEGO' => 'JGO',
        'RODAMIENTO' => 'ROD', 'TERMINAL' => 'TERM', 'ROTULA' => 'ROT',
        'BARRA' => 'B.', 'BOMBA' => 'BBA', 'CORREA' => 'CORR',
    ];

    foreach ($venta['productos'] as $producto) {
        $precioUnitarioBs = $producto['pvp_referencia'] * $tasaBCV;
        $totalProductoBs = $producto['total'] * $tasaBCV;
        
        $subtotalBs += $totalProductoBs;
        $totalArticulos += $producto['cantidad'];

        // **LÓGICA CLAVE**: Aquí se determina si el producto es gravable basándose en id_impuesto
        $idImpuesto = $producto['id_impuesto'] ?? 0;

        if ((int)$idImpuesto > 0) { // Asumimos que cualquier id_impuesto > 0 es gravable
            $baseImponibleG += $totalProductoBs;
        } else {
            // Si no tiene un id_impuesto, se considera Exento o No Sujeto
            $baseImponibleE += $totalProductoBs;
        }

        // Acortar la descripción
        $descripcion = strtoupper($producto['descripcion']);
        $descripcion = str_replace(array_keys($abreviaturas), array_values($abreviaturas), $descripcion);

        if (strlen($descripcion) > 20) {
            $descripcion = substr($descripcion, 0, 17) . '...';
        }

        // Imprimir toda la fila con celdas de altura fija y gaps
        $pdf->Cell(35, 4, $descripcion, 0, 0, 'L');
        $pdf->Cell(1, 4, '', 0, 0); // Gap
        $pdf->Cell(10, 4, number_format($producto['cantidad'], 2, ',', '.'), 0, 0, 'C');
        $pdf->Cell(1, 4, '', 0, 0); // Gap
        $pdf->Cell(20, 4, number_format($precioUnitarioBs, 2, ',', '.'), 0, 0, 'R');
        $pdf->Cell(1, 4, '', 0, 0); // Gap
        $pdf->Cell(22, 4, number_format($totalProductoBs, 2, ',', '.'), 0, 1, 'R');
    }
    
    $pdf->Ln(1);
    $pdf->Cell(0, 0, str_repeat('-', 90), 0, 1, 'C');
    
    // --- 6. SECCIÓN DE TOTALES ---
    $pdf->SetFont('courier', '', 8);
    
    $ivaCalculado = $baseImponibleG * ($ivaPorcentaje / 100);
    $totalAPagar = $subtotalBs; // El subtotal ya incluye los impuestos de cada producto si aplica
    
    // Función para imprimir una línea de total, alineada con la tabla de productos
    function imprimirTotal($pdf, $label, $value, $isBold = false) {
        if ($isBold) {
            $pdf->SetFont('courier', 'B', 9);
        } else {
            $pdf->SetFont('courier', '', 8);
        }
        $pdf->Cell(65, 5, $label, 0, 0, 'R'); 
        $pdf->Cell(25, 5, $value, 0, 1, 'R');
    }
    
    imprimirTotal($pdf, 'SUBTOTAL:', number_format($subtotalBs, 2, ',', '.'));
    $pdf->Ln(1);

    imprimirTotal($pdf, 'TOTAL ARTÍCULOS:', $totalArticulos);
    $pdf->Ln(1);

    imprimirTotal($pdf, 'BASE IMPONIBLE (G) ' . number_format($ivaPorcentaje, 0) . '%:', number_format($baseImponibleG, 2, ',', '.'));
    imprimirTotal($pdf, 'IVA (G) ' . number_format($ivaPorcentaje, 0) . '%:', number_format($ivaCalculado, 2, ',', '.'));
    $pdf->Ln(1);

    imprimirTotal($pdf, 'BASE IMPONIBLE (E):', number_format($baseImponibleE, 2, ',', '.'));
    imprimirTotal($pdf, 'MONTO EXENTO/EXONERADO (E):', number_format($baseImponibleE, 2, ',', '.'));
    $pdf->Ln(1);

    imprimirTotal($pdf, 'MONTO NO SUJETO A IVA:', number_format($montoNoSujeto, 2, ',', '.'));
    $pdf->Ln(1);
    
    imprimirTotal($pdf, 'TOTAL A PAGAR (Bs.):', number_format($totalAPagar, 2, ',', '.'), true);

    // --- INICIO: MOSTRAR TOTALES EN USD ---
    $pdf->Ln(3); 

    $pdf->SetFont('courier', '', 8);
    $pdf->Cell(65, 5, 'SUBTOTAL ($):', 0, 0, 'R'); 
    $pdf->Cell(25, 5, '$' . number_format($venta['subtotal_usd'], 2, '.', ','), 0, 1, 'R');

    $pdf->Cell(65, 5, 'IVA ($):', 0, 0, 'R'); 
    $pdf->Cell(25, 5, '$' . number_format($venta['iva_usd'], 2, '.', ','), 0, 1, 'R');
    
    $pdf->Ln(1);

    $pdf->SetFont('courier', 'B', 9);
    $pdf->Cell(65, 5, 'TOTAL A PAGAR ($):', 0, 0, 'R'); 
    $pdf->Cell(25, 5, '$' . number_format($venta['total_usd'], 2, '.', ','), 0, 1, 'R');
    // --- FIN: MOSTRAR TOTALES EN USD ---
    
    $pdf->Cell(0, 2, str_repeat('-', 90), 0, 1, 'C');

    // --- 7. FORMA DE PAGO ---
    $pdf->SetFont('courier', 'B', 8);
    $pdf->Cell(0, 5, 'FORMA DE PAGO:', 0, 1, 'L');
    $pdf->SetFont('courier', '', 8);
    
    $metodos_pago_map = [
        'Efectivo-BS' => 'Bolivares (EFECTIVO)',
        'Efectivo-USD' => 'Dolares (Efectivo)',
        'Pago-Movil' => 'Pago Movil (DEBITO)',
        'Transferencia' => 'Transferencia (DEBITO)',
        'Punto-Venta' => 'Punto de Venta (DEBITO)',
        'Zelle' => 'Zeller'
    ];

    $metodoPagoOriginal = $venta['metodo_pago'];
    $metodoPagoMostrado = $metodos_pago_map[$metodoPagoOriginal] ?? ucwords(str_replace(['-', '_'], ' ', $metodoPagoOriginal));

    $pdf->Cell(45, 5, $metodoPagoMostrado, 0, 1, 'L');
    
    // --- 8. SALIDA DEL PDF ---
    $pdf->Output('factura-' . $venta['codigo_venta'] . '.pdf', 'I'); 

} else {
    echo "Parámetro de venta no especificado.";
}