<?php
require_once '../../extensions/tcpdf/tcpdf.php';
require_once '../../controller/ordenes-compras.controller.php';
require_once '../../model/ordenes-compras.model.php';
require_once '../../controller/proveedores.controller.php';
require_once '../../model/proveedores.model.php';

class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // Logo
        $image_file = '../img/plantilla/logo.png';
        $this->Image($image_file, 10, 10, 40, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, 'ORDEN DE COMPRA', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

if(isset($_GET["idOrden"])){

    $idOrden = $_GET["idOrden"];

    // Fetch order data
    $orden = ControllerOrdenesCompras::ctrMostrarOrdenCompra("id_orden_compra", $idOrden);
    $proveedor = ControllerProveedores::ctrMostrarProveedores("id_proveedor", $orden["id_proveedor"]);
    $detalle = ModelOrdenesCompras::mdlMostrarOrdenCompraDetalle("orden_compra_detalle", "id_orden_compra", $idOrden);

    // Create new PDF document
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Tu Nombre de Empresa');
    $pdf->SetTitle('Orden de Compra - ' . $orden["codigo"]);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 10);

    // --- ORDEN INFO ---
    $html = '<br><br><br><br><br><br>'; // Espacio para el header
    $html .= '<table border="1" cellpadding="4">';
    $html .= '<tr><td><strong>Nº ORDEN:</strong> '.str_pad($orden["codigo"], 8, '0', STR_PAD_LEFT).'</td><td><strong>FECHA:</strong> '.date("d/m/Y", strtotime($orden["fecha"])).'</td></tr>';
    $html .= '<tr><td colspan="2"><strong>PROVEEDOR:</strong> '.htmlspecialchars($proveedor["nombre"]).'</td></tr>';
    $html .= '<tr><td colspan="2"><strong>DIRECCIÓN:</strong> '.htmlspecialchars($proveedor["direccion"]).', '.htmlspecialchars($proveedor["ciudad"]).', '.htmlspecialchars($proveedor["estado"]).'</td></tr>';
    $html .= '<tr><td><strong>TELÉFONO:</strong> '.htmlspecialchars($proveedor["telefono"]).'</td><td><strong>EMAIL:</strong> '.htmlspecialchars($proveedor["email"]).'</td></tr>';
    $html .= '</table><br><br>';

    // --- PRODUCTOS ---
    $html .= '<table border="1" cellpadding="4">';
    $html .= '<tr bgcolor="#cccccc"><th>CÓDIGO</th><th>DESCRIPCIÓN</th><th>CANT.</th><th>P. UNIT.</th><th>SUBTOTAL</th></tr>';
    
    foreach($detalle as $item){
        $html .= '<tr>';
        $html .= '<td>'.$item["id_producto"].'</td>'; // Asumiendo que el id_producto es el código
        $html .= '<td>'.htmlspecialchars($item["descripcion"]).'</td>';
        $html .= '<td>'.$item["cantidad"].'</td>';
        $html .= '<td>'.number_format($item["precio_compra"], 2).'</td>';
        $html .= '<td align="right">'.number_format($item["subtotal"], 2).'</td>';
        $html .= '</tr>';
    }
    $html .= '</table><br><br>';

    // --- TOTALES ---
    $html .= '<table border="0" cellpadding="4" align="right">';
    $html .= '<tr><td align="right"><strong>SUBTOTAL:</strong></td><td align="right">'.number_format($orden["subtotal"], 2).'</td></tr>';
    $html .= '<tr><td align="right"><strong>IMPUESTOS:</strong></td><td align="right">'.number_format($orden["impuestos"], 2).'</td></tr>';
    $html .= '<tr><td align="right"><strong>DESCUENTO:</strong></td><td align="right">'.number_format($orden["descuento"], 2).'</td></tr>';
    $html .= '<tr><td align="right"><strong>ENVÍO:</strong></td><td align="right">'.number_format($orden["costo_envio"], 2).'</td></tr>';
    $html .= '<tr><td align="right"><strong>TOTAL:</strong></td><td align="right"><strong>'.number_format($orden["total"], 2).'</strong></td></tr>';
    $html .= '</table><br><br>';

    // --- NOTAS Y TÉRMINOS ---
    $html .= '<p><strong>Términos de Pago:</strong> '.$orden["terminos_pago"].'</p>';
    $html .= '<p><strong>Notas:</strong> '.$orden["notas"].'</p>';

    // Print text using writeHTMLCell()
    $pdf->writeHTML($html, true, false, true, false, '');

    // Close and output PDF document
    $pdf->Output('orden_compra_'.$orden["codigo"].'.pdf', 'I');

}
