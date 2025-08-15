<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Libro de ventas
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Libro de ventas</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- Total Base Imponible (VES) -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3 id="totalBaseImponible">0.00 Bs.</h3>
                        <p>Total Base Imponible (VES)</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-cash"></i>
                    </div>
                </div>
            </div>

            <!-- Total IVA (VES) -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3 id="totalIva">0.00 Bs.</h3>
                        <p>Total Débito Fiscal (VES)</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>

            <!-- Total General (VES) -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3 id="totalGeneral">0.00 Bs.</h3>
                        <p>Total General (VES)</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                </div>
            </div>

            <!-- NUEVO: Total General (USD) -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3 id="totalGeneralUsd">0.00 $</h3>
                        <p>Total General (USD)</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <div class="box-header with-border">
                    <button type="button" class="btn btn-default pull-right" id="daterange-btn-ventas">
                        <span>
                            <i class="fa fa-calendar"></i> Rango de fecha
                        </span>
                        <i class="fa fa-caret-down"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablaLibro" width="100%">
                    <thead>
                        <tr>
                            <th class="dt-center" style="width: 5%;">#</th>
                            <th class="dt-center">Emisión</th>
                            <th class="dt-center">Nº Factura</th>
                            <!-- <th class="dt-center">Nº Control</th> -->
                            <th class="dt-center">Cliente</th>
                            <th class="dt-center">Documento</th>
                            <th class="dt-center">Base imponible</th>
                            <th class="dt-center">Impuesto</th>
                            <th class="dt-center">Total VES</th>
                            <th class="dt-center">Total USD</th>
                            <th class="dt-center">Estado</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        // Inicializar variables para totales
                        $totalBaseImponible = 0;
                        $totalIva = 0;
                        $totalGeneral = 0;
                        $totalGeneralUsd = 0;

                        // Inicializar variables de fecha
                        $fechaInicial = null;
                        $fechaFinal = null;

                        // Verificar si las fechas vienen por GET
                        if (isset($_GET["fechaInicial"])) {
                            $fechaInicial = $_GET["fechaInicial"];
                            $fechaFinal = $_GET["fechaFinal"];
                        }

                        $respuesta = ControllerVentas::ctrRangoFechasVentas($fechaInicial, $fechaFinal);

                        foreach ($respuesta as $key => $value) {
                            // Solo sumar si el estado no es "Anulada"
                            if ($value["estado"] != "Anulada") {
                                $totalBaseImponible += $value["subtotal_bs"];
                                $totalIva += $value["iva_bs"];
                                $totalGeneral += $value["total_bs"];
                                $totalGeneralUsd += $value["total_usd"]; // <-- Sumamos el total en USD
                            }

                            echo '<tr>

                                <td class="dt-center" style="width: 5%;">' . ($key + 1) . '</td>
                                <td class="dt-center">' . date("d/m/Y", strtotime($value["fecha"])) . '</td>
                                <td class="dt-center">' . htmlspecialchars($value["codigo_venta"]) . '</td>';

                            // Obtener datos del cliente
                            $itemCliente = "id";
                            $valorCliente = $value["id_cliente"];
                            $respuestaCliente = ControllerClients::ctrMostrarClientes($itemCliente, $valorCliente);

                            echo '<td>' . ($respuestaCliente ? htmlspecialchars($respuestaCliente["nombre"]) : 'Cliente no encontrado') . '</td>';
                            echo '<td>' . ($respuestaCliente ? htmlspecialchars($respuestaCliente["documento"]) : 'Cliente no encontrado') . '</td>';

                            echo '<td class="dt-right">' . number_format($value["subtotal_bs"], 2, ',', '.') . '</td>
                                <td class="dt-right">' . number_format($value["iva_bs"], 2, ',', '.') . '</td>
                                <td class="dt-right">' . number_format($value["total_bs"], 2, ',', '.') . '</td>
                                <td class="dt-right">' . number_format($value["total_usd"], 2, ',', '.') . '</td>';

                            // Mostrar el estado de la venta con un select para cambiar el estado
                            $estado = $value["estado"];
                            $claseBadge = '';
                            if ($estado == 'Pagada') {
                                $claseBadge = 'label-success';
                            } elseif ($estado == 'Pendiente') {
                                $claseBadge = 'label-warning';
                            } elseif ($estado == 'Anulada') {
                                $claseBadge = 'label-danger';
                            }

                            echo '<td>
                                        <span 
                                            class="label btnCambiarEstado ' . $claseBadge . '" 
                                            style="cursor: pointer;"
                                            data-id-venta="' . $value["id_venta"] . '"
                                            data-estado-actual="' . $estado . '">'
                                . $estado .
                                '</span>
                                    </td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    $(document).ready(function() {
        // Obtenemos los valores calculados por PHP
        var baseImponible = <?php echo $totalBaseImponible; ?>;
        var iva = <?php echo $totalIva; ?>;
        var total = <?php echo $totalGeneral; ?>;
        var totalUsd = <?php echo $totalGeneralUsd; ?>; // <-- Obtenemos la nueva variable

        // Opciones de formato para USD
        const formatoUsd = { style: 'currency', currency: 'USD' };

        // Formateamos como moneda y actualizamos el HTML
        $('#totalBaseImponible').html(baseImponible.toLocaleString('es-VE', { minimumFractionDigits: 2 }) + ' Bs.');
        $('#totalIva').html(iva.toLocaleString('es-VE', { minimumFractionDigits: 2 }) + ' Bs.');
        $('#totalGeneral').html(total.toLocaleString('es-VE', { minimumFractionDigits: 2 }) + ' Bs.');
        
        // Actualizamos la nueva caja de USD
        $('#totalGeneralUsd').html(totalUsd.toLocaleString('en-US', formatoUsd));
        
        actualizarCajasDeTotales();
    });
</script>