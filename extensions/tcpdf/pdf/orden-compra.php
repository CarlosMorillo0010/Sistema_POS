<?php

require_once('tcpdf_include.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->startPageGroup();
$pdf->AddPage();

$bloque_1 = <<<EOF
    <table>
        <tr>
            <td style="background-color: #fff; width:270px">
                <div style="text-transform: uppercase;font-style: italic;font-size: 20px;">iTech Soluciones Web</div>
            </td>
            <td style="background-color: #fff; width:270px">
                <div style="font-size: 8px; text-align: right; line-height:15px;">RIF: J311383247<br>Dirección: Guarenas - Edo. Miranda.</div>
            </td>
        </tr>
    </table>
EOF;

$pdf->writeHTML($bloque_1, false, false, false, false, '');

/*======================================
 SALIDA DEL ARCHIVO PDF
=======================================*/
$pdf->Output('factura_orden_compra.pdf');

?>