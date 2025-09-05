<?php

// --- DEPENDENCIAS --- //
require_once "../../../controller/ventas-controller.php";
require_once "../../../model/ventas-model.php";
require_once "../../../controller/clients.controller.php";
require_once "../../../model/clients.model.php";
require_once "../../../controller/users.controller.php";
require_once "../../../model/users.model.php";
require_once "../../../controller/products.controller.php";
require_once "../../../model/products.model.php";
// --- NUEVAS DEPENDENCIAS PARA OBTENER DATOS DE LA EMPRESA ---
require_once "../../../controller/configuraciones.controller.php";
require_once "../../../model/configuraciones.model.php";
require_once "../../../model/connection.php";

class imprimirFactura{

    public $codigo_venta;

    public function traerImpresionFactura(){

        // --- 1. OBTENER DATOS DE LA EMPRESA ---
        $empresa = ControllerConfiguraciones::ctrMostrarConfiguracion();

        // --- 2. OBTENER DATOS DE LA VENTA ---
        $itemVenta = "codigo_venta";
        $valorVenta = $this->codigo_venta;
        $respuestaVenta = ControllerVentas::ctrMostrarVenta($itemVenta, $valorVenta);

        $fecha = substr($respuestaVenta["fecha"],0,-8);
        $productos = json_decode($respuestaVenta["productos"], true);
        $precio_neto = number_format($respuestaVenta["subtotal_usd"],2);
        $total = number_format($respuestaVenta["total_usd"],2);

        // --- 3. OBTENER DATOS DEL CLIENTE ---
        $itemCliente = "id";
        $valorCliente = $respuestaVenta["id_cliente"];
        $respuestaCliente = ControllerClients::ctrMostrarClientes($itemCliente, $valorCliente);

        // --- 4. OBTENER DATOS DEL VENDEDOR ---
        $itemVendedor = "id_usuario";
        $valorVendedor = $respuestaVenta["id_vendedor"];
        $respuestaVendedor = ControllerUsers::ctrMostrarUsuario($itemVendedor, $valorVendedor);

        // --- 5. CONFIGURACIÓN DEL PDF ---
        require_once('tcpdf_include.php');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->startPageGroup();
        $pdf->AddPage();

        //------------------------------------------------------------------
        // --- BLOQUE 1: CABECERA DE LA FACTURA ---
        //------------------------------------------------------------------
        
        $logoPath = $_SERVER['DOCUMENT_ROOT'] . "/Sistema_POS/" . $empresa["logo"];
        $logoPath = str_replace('\\', '/', $logoPath);

        $bloque1 = <<<EOF
        <table>
            <tr>
                <td style="width:150px; text-align:center;">
                    <img src="$logoPath" width="100" />
                    <br>
                    <span style="font-size:9px; font-weight:bold;">{$empresa["nombre"]}</span>
                </td>
                <td style="width:140px;">
                    <div style="font-size:8.5px; text-align:right; line-height:15px;">
                        <br>
                        RIF: {$empresa["rif"]}
                        <br>
                        {$empresa["direccion"]}
                    </div>
                </td>
                <td style="width:140px;">
                    <div style="font-size:8.5px; text-align:right; line-height:15px;">
                        <br>
                        Teléfono: {$empresa["telefono"]}
                        <br>
                        {$empresa["email"]}
                    </div>
                </td>
                <td style="width:110px; text-align:center; color:red;"><br>
                <br>FACTURA N.<br>$valorVenta</td>
            </tr>
        </table>
EOF;

        $pdf->writeHTML($bloque1, false, false, false, false, '');

        //------------------------------------------------------------------
        // --- BLOQUE 2: DATOS DEL CLIENTE Y VENDEDOR ---
        //------------------------------------------------------------------

        $bloque2 = <<<EOF
        <table>
            <tr>
                <td style="width:540px"><img src="images/back.jpg"></td>
            </tr>
        </table>
        <table style="font-size:10px; padding:5px 10px;">
            <tr>
                <td style="border: 1px solid #666; background-color:white; width:390px">
                    Vendedor: $respuestaVendedor[nombre]
                </td>
                <td style="border: 1px solid #666; background-color:white; width:150px; text-align:right">
                    Fecha: $fecha
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #666; background-color:white; width:540px">
                    Cliente: $respuestaCliente[nombre]
                </td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #666; background-color:white; width:540px"></td>
            </tr>
        </table>
EOF;

        $pdf->writeHTML($bloque2, false, false, false, false, '');

        //------------------------------------------------------------------
        // --- BLOQUE 3: CABECERA DE LA TABLA DE PRODUCTOS ---
        //------------------------------------------------------------------

        $bloque3 = <<<EOF
        <table style="font-size:10px; padding:5px 10px;">
            <tr>
                <td style="border: 1px solid #666; background-color:white; width:260px; text-align:center">Producto</td>
                <td style="border: 1px solid #666; background-color:white; width:80px; text-align:center">Cantidad</td>
                <td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">Valor Unit.</td>
                <td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">Valor Total</td>
            </tr>
        </table>
EOF;

        $pdf->writeHTML($bloque3, false, false, false, false, '');

        //------------------------------------------------------------------
        // --- BLOQUE 4: LISTA DE PRODUCTOS ---
        //------------------------------------------------------------------

        foreach ($productos as $key => $item) {

            $itemProducto = "descripcion";
            $valorProducto = $item["descripcion"];
            $orden = null;
            $respuestaProducto = ControllerProducts::ctrMostrarProductos($itemProducto, $valorProducto, $orden);
            $valorUnitario = number_format($respuestaProducto["pvp_referencia"], 2);
            $precioTotal = number_format($item["total"], 2);

            $bloque4 = <<<EOF
            <table style="font-size:10px; padding:5px 10px;">
                <tr>
                    <td style="border: 1px solid #666; color:#333; background-color:white; width:260px; text-align:center">$item[descripcion]</td>
                    <td style="border: 1px solid #666; color:#333; background-color:white; width:80px; text-align:center">$item[cantidad]</td>
                    <td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">$ $valorUnitario</td>
                    <td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">$ $precioTotal</td>
                </tr>
            </table>
EOF;

            $pdf->writeHTML($bloque4, false, false, false, false, '');
        }

        //------------------------------------------------------------------
        // --- BLOQUE 5: TOTALES ---
        //------------------------------------------------------------------

        $bloque5 = <<<EOF
        <table style="font-size:10px; padding:5px 10px;">
            <tr>
                <td style="color:#333; background-color:white; width:340px; text-align:center"></td>
                <td style="border-bottom: 1px solid #666; background-color:white; width:100px; text-align:center"></td>
                <td style="border-bottom: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center"></td>
            </tr>
            <tr>
                <td style="border-right: 1px solid #666; color:#333; background-color:white; width:340px; text-align:center"></td>
                <td style="border: 1px solid #666;  background-color:white; width:100px; text-align:center">Total:</td>
                <td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">$ $precio_neto</td>
            </tr>
        </table>
EOF;

        $pdf->writeHTML($bloque5, false, false, false, false, '');

        // --- 6. SALIDA DEL ARCHIVO ---
        $pdf->Output('factura.pdf', 'I');
    }
}

$factura = new imprimirFactura();
$factura ->codigo_venta = $_GET["codigo_venta"];
$factura ->traerImpresionFactura();

?>