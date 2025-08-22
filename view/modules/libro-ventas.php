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

        <div class="row text-center">

            <!-- Total Base Imponible (VES) -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3 id="totalBaseImponible">0.00 Bs.</h3>
                        <p>Total Base Imponible VES</p>
                    </div>
                </div>
            </div>

            <!-- Total IVA (VES) -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3 id="totalIva">0.00 Bs.</h3>
                        <p>Total Débito Fiscal VES</p>
                    </div>
                </div>
            </div>

            <!-- Total General (VES) -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3 id="totalGeneral">0.00 Bs.</h3>
                        <p>Total General VES</p>
                    </div>
                </div>
            </div>

            <!-- NUEVO: Total General (USD) -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3 id="totalGeneralUsd">0.00 $</h3>
                        <p>Total General USD</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Default box -->
        <div class="box">
            <div class="box-header">

                <div id="customButtons2" class="hidden">

                    <button type="button" class="btn btn-default pull-right" id="daterange-btn-ventas">
                        <span>
                            <i class="fa fa-calendar"></i> Rango de fecha
                        </span>
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <button class="btn btn-default mr-1" data-toggle="modal" data-target="#modalInstruccionesVentas" title="Ver instrucciones de uso">
                        <i class="fa fa-question-circle"></i>
                    </button>

                </div>
                
                <div id="customButtons" class="hidden">

                    <a id="btnGenerarTxtSeniat" title="Exportar TXT SENIAT" class="btn btn-primary pull-left" target="_blank"><i
                        class="fa fa-file-text-o"></i> TXT</a>  
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
                            <th class="dt-center">Base Imponible VES</th>
                            <th class="dt-center">Débito Fiscal VES</th>
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
                                $totalGeneralUsd += $value["total_usd"];
                            }

                            echo '<tr>

                                <td class="dt-center">' . ($key + 1) . '</td>
                                <td class="dt-center">' . date("d/m/Y", strtotime($value["fecha"])) . '</td>';
                                
                            echo '<td class="dt-center">
                                <button class="btn btn-default btnVerDetalleVenta" style="color:#000; font-size: 16px;" 
                                        data-id-venta="' . $value["id_venta"] . '" 
                                        data-toggle="modal" 
                                        data-target="#modalDetalleVenta">
                                    ' . htmlspecialchars($value["codigo_venta"]) . '
                                </button>
                            </td>';

                            // Obtener datos del cliente
                            $itemCliente = "id";
                            $valorCliente = $value["id_cliente"];
                            $respuestaCliente = ControllerClients::ctrMostrarClientes($itemCliente, $valorCliente);

                            // Mostrar el nombre del cliente con un ancho fijo
                            echo '<td class="dt-center" style="width: 15%;">' . ($respuestaCliente ? htmlspecialchars($respuestaCliente["nombre"]) : 'Cliente no encontrado') . '</td>';
                            
                            // Mostrar el documento del cliente
                            echo '<td class="dt-center">' . ($respuestaCliente ? htmlspecialchars($respuestaCliente["tipo_documento"]) . ' ' . htmlspecialchars($respuestaCliente["documento"]) : 'Cliente no encontrado') . '</td>';

                            echo '<td class="dt-center">' . number_format($value["subtotal_bs"], 2, ',', '.') . '</td>
                                <td class="dt-center">' . number_format($value["iva_bs"], 2, ',', '.') . '</td>
                                <td class="dt-center">' . number_format($value["total_bs"], 2, ',', '.') . '</td>
                                <td class="dt-center">' . number_format($value["total_usd"], 2, ',', '.') . '</td>';

                            // 1. Determinar el estado actual. Si es NULL, es "Pagada".
                            $estado = $value["estado"] ?? "Pagada";

                            // 2. Determinar la clase de color basada en el estado.
                            $claseBadge = '';
                            if ($estado == 'Pagada') {
                                $claseBadge = 'label-success';
                            } elseif ($estado == 'Pendiente') {
                                $claseBadge = 'label-warning';
                            } elseif ($estado == 'Pagada Parcialmente') {
                                $claseBadge = 'label-warning';
                            } elseif ($estado == 'Anulada') {
                                $claseBadge = 'label-danger';
                            }

                            // 3. Determinar si es un registro nuevo para el sistema de cobros.
                            $esNuevo = ($value["estado"] === NULL) ? 'true' : 'false';

                            // 4. Imprimir el botón con la SINTAXIS CORRECTA.
                            echo '<td>
                                    <button class="label btn btn-block p-2 btn-sm btnCambiarEstado ' . $claseBadge . '"  
                                            style="cursor: pointer; font-size: 16px;" 
                                            data-id-venta="' . $value["id_venta"] . '" 
                                            data-estado-actual="' . $estado . '"
                                            data-es-nuevo="' . $esNuevo . '">
                                        ' . $estado . '
                                    </button>
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

<!-- =============================================
MODAL DETALLE DE VENTA
============================================== -->
<div id="modalDetalleVenta" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg"> <!-- modal-lg para más espacio -->
        <div class="modal-content">
            <div class="modal-header" style="background:#3c8dbc; color:white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Detalle de la Venta</h4>
            </div>
            
            <div class="modal-body">
                <div class="box-body" id="contenidoModalVenta">
                    <div class="text-center">
                        <i class="fa fa-spinner fa-spin fa-3x"></i>
                        <p>Cargando detalles...</p>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <!-- <button type="button" class="btn btn-primary"><i class="fa fa-print"></i> Imprimir Factura</button> -->
            </div>
        </div>
    </div>
</div>

<!-- =============================================
MODAL DE INSTRUCCIONES - LIBRO DE VENTAS
============================================== -->
<div id="modalInstruccionesVentas" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:#555; color:white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-info-circle"></i> Instrucciones de Uso - Libro de Ventas</h4>
            </div>
            
            <div class="modal-body">
                <div class="box-body">
                    
                    <h4><i class="fa fa-calendar"></i> 1. Filtrar por Rango de Fechas</h4>
                    <p>
                        Utilice el botón <strong>"Rango de fecha"</strong> para seleccionar un período específico. Puede elegir rangos predefinidos como "Este mes" o seleccionar fechas personalizadas en el calendario. La tabla y los totales se actualizarán automáticamente.
                    </p>
                    <hr>
                    
                    <h4><i class="fa fa-cogs"></i> 2. Gestionar Facturas</h4>
                    <ul>
                        <li><strong>Ver Detalle:</strong> Haga clic en el <strong>número de factura</strong> para abrir una ventana con todos los detalles de la venta, incluyendo los productos.</li>
                        <li><strong>Cambiar Estado:</strong> Haga clic en la insignia de estado (ej. <span class="btn btn-success btn-xs">Pagada</span>) para cambiar cíclicamente el estado de una factura (Pagada → Anulada → Pendiente). Los totales se recalcularán al instante.</li>
                    </ul>
                    <hr>

                    <h4><i class="fa fa-file-text-o"></i> 3. Generar Reportes</h4>
                    <ul>
                        <li><strong>Exportar a TXT (SENIAT):</strong> Después de seleccionar un rango de fechas, puede pulsar el boton del ícono <span class="btn btn-primary btn-xs"><i class="fa fa-file-text-o"></i> TXT</span>. para descargar el archivo de texto plano listo para el portal del SENIAT.</li>
                        <li><strong>Exportar a Excel/Imprimir:</strong> Use los botones de <span class="btn btn-success btn-xs"><i class="fa fa-file-excel-o"></i> Excel</span> o <span class="btn btn-warning btn-xs"><i class="fa fa-print"></i> Imprimir</span> para descargar una copia del reporte que está viendo en pantalla.</li>
                    </ul>
                    <hr>
                    
                    <h4><i class="fa fa-search"></i> 4. Búsqueda y Ordenamiento</h4>
                    <p>
                        Utilice el campo de <strong>"Buscar"</strong> para filtrar rápidamente por número de factura, cliente o cualquier otro dato. También puede hacer clic en los encabezados de las columnas para ordenar la tabla.
                    </p>

                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Obtenemos los valores calculados por PHP
        var baseImponible = <?php echo $totalBaseImponible; ?>;
        var iva = <?php echo $totalIva; ?>;
        var total = <?php echo $totalGeneral; ?>;
        var totalUsd = <?php echo $totalGeneralUsd; ?>;

        // Opciones de formato para USD
        const formatoUsd = { style: 'currency', currency: 'USD' };

        // Formateamos como moneda y actualizamos el HTML
        $('#totalBaseImponible').html(baseImponible.toLocaleString('es-VE', { minimumFractionDigits: 2 }) + ' Bs.');
        $('#totalIva').html(iva.toLocaleString('es-VE', { minimumFractionDigits: 2 }) + ' Bs.');
        $('#totalGeneral').html(total.toLocaleString('es-VE', { minimumFractionDigits: 2 }) + ' Bs.');

        // Actualizamos la nueva caja de USD
        $('#totalGeneralUsd').html(totalUsd.toLocaleString('en-US', {
            style: 'currency',
            currency: 'USD'
        }));

        actualizarCajasDeTotales();
    });
</script>