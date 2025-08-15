<?php

require_once "../../../controller/ventas-controller.php";
require_once "../../../model/ventas-model.php";

require_once "../../../controller/clients.controller.php";
require_once "../../../model/clients.model.php";

require_once "../../../controller/users.controller.php";
require_once "../../../model/users.model.php";

require_once "../../../controller/products.controller.php";
require_once "../../../model/products.model.php";

class imprimirFactura{

public $codigo_venta;

public function traerImpresionFactura(){

//TRAEMOS LA INFORMACION DE LA VENTA

$itemVenta = "codigo_venta";
$valorVenta = $this->codigo_venta;

$respuestaVenta = ControllerVentas::ctrMostrarVenta($itemVenta, $valorVenta);

$fecha = substr($respuestaVenta["fecha"],0,-8);
$productos = json_decode($respuestaVenta["productos"], true);
$subtotal_usd = number_format($respuestaVenta["subtotal_usd"], 2);
$total_usd = number_format($respuestaVenta["total_usd"], 2);

//TRAEMOS LA INFORMACION DEL CLIENTE

$itemCliente = "id";
$valorCliente = $respuestaVenta["id_cliente"];

$respuestaCliente = ControllerClients::ctrMostrarClientes($itemCliente, $valorCliente);


//TRAEMOS LA INFORMACION DEL VENDEDOR

$itemVendedor = "id_usuario";

$valorVendedor = $respuestaVenta["id_vendedor"];

$respuestaVendedor = ControllerUsers::ctrMostrarUsuario($itemVendedor, $valorVendedor);

//REQUERIMOS LA CLASE TCPDF

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->startPageGroup();

$pdf->AddPage();

//------------------------------------------------------------------

$bloque1 = <<<EOF

	<table>

		<tr>

		<td style="background-color:white; width:150px"></td>

			<td style="background-color:white; width:140px">

				<div style="font-size:8.5px; text-align:right; line-height:15px;">

					<br>
					RIF: J-40982706-2

					<br>
					Zona industrial del este galpón 67, Filas de Mariche Municipio Sucre

				</div>

			</td>

			<td style="background-color:white; width:140px">

				<div style="font-size:8.5px; text-align:right; line-height:15px;">

					<br>
					Telefono: (0424)162.39.57

					<br>
					mantem03@gmail.com

				</div>

			</td>

			<td style="background-color:white; width:110px; text-align:center; color:red;"><br>
			<br>FACTURA N.<br>$valorVenta</td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($bloque1, false, false, false, false, '');

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

				Cliente: $respuestaCliente[nombre]

			</td>

			<td style="border: 1px solid #666; background-color:white; width:150px; text-align:right">
			
				Fecha: $fecha

			</td>

		</tr>

		<tr>
		
			<td style="border: 1px solid #666; background-color:white; width:540px">

				Vendedor: $respuestaVendedor[nombre]

			</td>

		</tr>

		<tr>
		
			<td style="border-bottom: 1px solid #666; background-color:white; width:540px"></td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($bloque2, false, false, false, false, '');

//------------------------------------------------------------------

$bloque3 = <<<EOF

	<table style="font-size:10px; padding:5px 10px;">

		<tr>
		
			<td style="border: 1px solid #666; background-color:white; width:260px; text-align:center">

				Producto

			</td>

			<td style="border: 1px solid #666; background-color:white; width:80px; text-align:center">

				Cantidad

			</td>

			<td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">

				Valor Unit.

			</td>

			<td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">

				Valor Total

			</td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($bloque3, false, false, false, false, '');

//------------------------------------------------------------------

foreach ($productos as $key => $item) {

$itemProducto = "descripcion";
$valorProducto = $item["descripcion"];
$orden = null;

$respuestaProducto = ControllerProducts::ctrMostrarProductos($itemProducto, $valorProducto, $orden);

$valorUnitario = number_format($respuestaProducto["subtotal_usd"], 2);

$precioTotal = number_format($item["total_usd"], 2);

$bloque4 = <<<EOF

	<table style="font-size:10px; padding:5px 10px;">

		<tr>
			
			<td style="border: 1px solid #666; color:#333; background-color:white; width:260px; text-align:center">
				$item[descripcion]
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:80px; text-align:center">
				$item[cantidad]
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">$ 
				$valorUnitario
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">$ 
				$precioTotal
			</td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($bloque4, false, false, false, false, '');

}

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

			<td style="border: 1px solid #666;  background-color:white; width:100px; text-align:center">
				Total:
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">
				$subtotal_usd
			</td>

		</tr>

	</table>


EOF;

$pdf->writeHTML($bloque5, false, false, false, false, '');

//------------------------------------------------------------------

//SALIDA DEL ARCHIVO

$pdf->Output('factura.pdf');

}


}

$factura = new imprimirFactura();
$factura -> codigo_venta = $_GET["codigo_venta"];
$factura -> traerImpresionFactura();

?>