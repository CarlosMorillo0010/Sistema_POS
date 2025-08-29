<?php
session_start();

require_once "../extensions/tcpdf/tcpdf.php";
require_once "../controller/ventas-controller.php";
require_once "../controller/clients.controller.php";
require_once "../controller/configuraciones.controller.php"; // Para datos de la empresa
require_once "../model/ventas-model.php";
require_once "../model/clients.model.php";
require_once "../model/configuraciones.model.php";
require_once "../model/connection.php";


if (isset($_GET["venta"])) {

    // --- OBTENER DATOS DE LA EMPRESA ---
    $empresa = ControllerConfiguraciones::ctrMostrarConfiguracion();

    if (!$empresa) {
        die("Error: No se pudieron cargar los datos de la configuración de la empresa. Verifique la base de datos.");
    }

    // --- OBTENER DATOS DE LA VENTA ---
    $idVenta = $_GET["venta"];
    $venta = ControllerVentas::ctrMostrarVentaDetallada("id_venta", $idVenta);

    if (!$venta) {
        echo "Venta no encontrada.";
        exit;
    }

    // --- CLASE PERSONALIZADA PARA HEADER Y FOOTER ---
    class MYPDF extends TCPDF
    {
        public $empresa;

        public function setEmpresaData($empresa)
        {
            $this->empresa = $empresa;
        }

        // Cabecera de página
        public function Header()
        {
            $this->SetFont('helvetica', 'B', 10);
            $this->Cell(0, 5, strtoupper($this->empresa['nombre']), 0, 1, 'C');
            $this->SetFont('helvetica', '', 8);
            $this->Cell(0, 3, 'RIF: ' . $this->empresa['rif'], 0, 1, 'C');
            $this->Cell(0, 3, $this->empresa['direccion'], 0, 1, 'C');
            $this->Cell(0, 3, 'Teléfono: ' . $this->empresa['telefono'], 0, 1, 'C');
            $this->Cell(0, 3, $this->empresa['email'], 0, 1, 'C');
            $this->Ln(3);
            $this->SetFont('helvetica', 'B', 9);
            $this->Cell(0, 5, 'NOTA DE ENTREGA', 'T', 1, 'C');
        }

        // Pie de página
        public function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 7);
            $this->Cell(0, 5, 'Gracias por su compra', 'T', 0, 'C');
        }
    }

    // --- CREACIÓN DEL DOCUMENTO PDF ---
    // Page size: 80mm de ancho, 250mm de alto (ajustable)
    $pdf = new MYPDF('P', 'mm', array(80, 250));
    $pdf->setEmpresaData($empresa);

    // --- METADATOS DEL DOCUMENTO ---
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('SistemaPOS');
    $pdf->SetTitle('Ticket de Venta ' . $venta['codigo_venta']);

    // --- MÁRGENES ---
    $pdf->SetMargins(5, 30, 5); // Izquierda, Arriba, Derecha
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(5);

    // --- AUTO PAGE BREAK ---
    $pdf->SetAutoPageBreak(TRUE, 15); // Habilitar con margen inferior

    // --- AÑADIR PÁGINA ---
    $pdf->AddPage();

    // --- INFORMACIÓN DE LA VENTA Y CLIENTE ---
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(0, 4, 'Fecha: ' . date("d/m/Y H:i:s", strtotime($venta['fecha'])), 0, 1, 'L');
    $pdf->Cell(0, 4, 'Ticket Nro: ' . $venta['codigo_venta'], 0, 1, 'L');
    $pdf->Cell(0, 4, 'Vendedor: ' . $venta['vendedor'], 0, 1, 'L');
    $pdf->Ln(2);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(0, 4, 'CLIENTE:', 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(0, 4, 'Nombre: ' . $venta['nombre'], 0, 1, 'L');
    $pdf->Cell(0, 4, $venta['tipo_documento'] . ': ' . $venta['documento'], 0, 1, 'L');
    $pdf->Ln(3);

    // --- TABLA DE PRODUCTOS ---
    $pdf->SetFont('helvetica', 'B', 7);
    $pdf->Cell(35, 5, 'Descripción', 'T', 0, 'L');
    $pdf->Cell(10, 5, 'Cant', 'T', 0, 'C');
    $pdf->Cell(12, 5, 'P.U.', 'T', 0, 'R');
    $pdf->Cell(13, 5, 'Total', 'T', 1, 'R');
    $pdf->SetFont('helvetica', '', 7);

    $productos = $venta['productos'];
    foreach ($productos as $producto) {
        $pdf->MultiCell(35, 4, $producto['descripcion'], 0, 'L', 0, 0, '', '', true, 0, false, true, 4, 'T');
        $pdf->Cell(10, 4, $producto['cantidad'], 0, 0, 'C');
        $pdf->Cell(12, 4, number_format($producto['pvp_referencia'], 2), 0, 0, 'R');
        $pdf->Cell(13, 4, number_format($producto['total'], 2), 0, 1, 'R');
    }
    $pdf->Cell(0, 1, '', 'T', 1, 'L'); // Línea final de la tabla
    $pdf->Ln(2);

    // --- TOTALES ---
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(40, 5, 'SUBTOTAL:', 0, 0, 'R');
    $pdf->Cell(30, 5, number_format($venta['subtotal_usd'], 2) . ' $', 0, 1, 'R');

    $pdf->Cell(40, 5, 'IVA (%):', 0, 0, 'R');
    $pdf->Cell(30, 5, number_format($venta['iva_usd'], 2) . ' $', 0, 1, 'R');

    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(40, 6, 'TOTAL A PAGAR:', 0, 0, 'R');
    $pdf->Cell(30, 6, number_format($venta['total_usd'], 2) . ' $', 0, 1, 'R');
    $pdf->Ln(3);

    // --- MÉTODO DE PAGO ---
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(0, 4, 'MÉTODO DE PAGO', 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 8);
    $pdf->MultiCell(0, 4, str_replace('-', ' ', $venta['metodo_pago']), 0, 'L');
    $pdf->Ln(5);


    // --- SALIDA DEL PDF ---
    $pdf->Output('ticket-' . $venta['codigo_venta'] . '.pdf', 'I'); // I: Muestra en navegador

} else {
    echo "Parámetro de venta no especificado.";
}
