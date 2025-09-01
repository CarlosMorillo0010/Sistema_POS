<?php

// Definir la ruta raíz para inclusiones seguras
define('BASE_PATH', dirname(__DIR__));

require_once(BASE_PATH . "/controller/configuraciones.controller.php");
require_once(BASE_PATH . "/model/configuraciones.model.php");
require_once(BASE_PATH . "/controller/compras.controller.php");
require_once(BASE_PATH . "/model/compras.model.php");
require_once(BASE_PATH . "/controller/proveedores.controller.php");
require_once(BASE_PATH . "/model/proveedores.model.php");
require_once(BASE_PATH . "/extensions/tcpdf/tcpdf.php");

// Custom PDF class
class MYPDF extends TCPDF {
    public $config;

    public function setConfig($config) {
        $this->config = $config;
    }

    // Page header
    public function Header() {
        // Logo
        if (!empty($this->config["logo"])) {
            $image_file = '../view/img/template/' . $this->config["logo"];
            $this->Image($image_file, 15, 10, 40, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
        
        // Company Info
        $this->SetFont('helvetica', 'B', 12);
        $this->SetXY(60, 15);
        $this->Cell(0, 10, $this->config["nombre"], 0, 1, 'L');
        $this->SetFont('helvetica', '', 8);
        $this->SetX(60);
        $this->MultiCell(80, 4, $this->config["direccion"], 0, 'L');
        $this->SetX(60);
        $this->Cell(0, 4, 'RIF: ' . $this->config["rif"], 0, 1, 'L');
        $this->SetX(60);
        $this->Cell(0, 4, 'Teléfono: ' . $this->config["telefono"], 0, 1, 'L');

        // Document Title
        $this->SetFont('helvetica', 'B', 16);
        $this->SetXY(140, 25);
        $this->Cell(0, 15, 'COMPRA', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

if (isset($_GET["idCompra"])) {
    $idCompra = $_GET["idCompra"];

    // Fetch data
    $configuracion = ControllerConfiguraciones::ctrMostrarConfiguracion();
    $compra = ControllerCompras::ctrMostrarCompras("id_compra", $idCompra);
    $detalle = ControllerCompras::ctrMostrarDetalleCompra("id_compra", $idCompra);
    $proveedor = ControllerProveedores::ctrMostrarProveedores("id_proveedor", $compra["id_proveedor"]);

    // Create new PDF document
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setConfig($configuracion);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($configuracion["nombre"]);
    $pdf->SetTitle('Compra ' . $compra["id_compra"]);

    // Set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // Set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, 45, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // Add a page
    $pdf->AddPage();

    // --- CONTENT ---
    $pdf->SetFont('helvetica', '', 10);

    // Supplier and Order Info
    $info_html = '
    <table border="1" cellpadding="4">
        <tr>
            <td width="65%"><b>PROVEEDOR:</b><br/>' . ($proveedor["nombre"] ?? 'N/A') . '<br/>' . ($proveedor["direccion"] ?? 'N/A') . '<br/>RIF: ' . ($proveedor["rif"] ?? 'N/A') . '</td>
            <td width="35%"><b>N° COMPRA:</b> ' . str_pad($compra["id_compra"], 8, '0', STR_PAD_LEFT) . '<br/><b>FECHA:</b> ' . date("d/m/Y", strtotime($compra["fecha_compra"])) . '</td>
        </tr>
    </table>';
    $pdf->writeHTML($info_html, true, false, true, false, '');

    // Products table
    $products_html = '
    <table border="1" cellpadding="4" style="margin-top: 20px;">
        <tr bgcolor="#dddddd">
            <th width="15%" align="center"><b>CÓDIGO</b></th>
            <th width="45%" align="center"><b>DESCRIPCIÓN</b></th>
            <th width="15%" align="center"><b>CANTIDAD</b></th>
            <th width="15%" align="center"><b>P. UNIT.</b></th>
            <th width="10%" align="center"><b>TOTAL</b></th>
        </tr>';

    $subtotal = 0;
    foreach ($detalle as $item) {
        $products_html .= '<tr>
            <td>' . $item["codigo"] . '</td>
            <td>' . $item["descripcion"] . '</td>
            <td align="center">' . $item["cantidad"] . '</td>
            <td align="right">' . number_format($item["precio_unitario"], 2) . '</td>
            <td align="right">' . number_format($item["subtotal"], 2) . '</td>
        </tr>';
        $subtotal += $item["subtotal"];
    }

    $products_html .= '</table>';
    $pdf->writeHTML($products_html, true, false, true, false, '');

    // Totals
    $totals_html = '
    <table border="0" cellpadding="4" style="margin-top: 20px;">
        <tr>
            <td width="70%"></td>
            <td width="15%" align="right"><b>SUBTOTAL:</b></td>
            <td width="15%" align="right">' . number_format($subtotal, 2) . '</td>
        </tr>
         <tr>
            <td width="70%"></td>
            <td width="15%" align="right"><b>IMPUESTOS:</b></td>
            <td width="15%" align="right">' . number_format(0, 2) . '</td>
        </tr>
         <tr>
            <td width="70%"></td>
            <td width="15%" align="right"><b>TOTAL:</b></td>
            <td width="15%" align="right"><b>' . number_format($compra["monto_total"], 2) . '</b></td>
        </tr>
    </table>';
    $pdf->writeHTML($totals_html, true, false, true, false, '');

    // Close and output PDF document
    $pdf->Output('compra_' . $compra["id_compra"] . '.pdf', 'I');

} else {
    echo "No se especificó una compra.";
}

?>