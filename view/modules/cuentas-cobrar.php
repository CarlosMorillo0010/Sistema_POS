<?php
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value):
    if ($_SESSION["perfil"] == $value["perfil"] && $value["ventas"] == "N") {
        echo '
            <script>
            window.location = "inicio";
            </script>
        ';
        return;
    }
endforeach;
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Cuentas por cobrar
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li>Cuentas por cobrar</li>
        </ol>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th class="text-center">Cliente</th>
                            <th class="text-center">Nº Factura</th>
                            <th class="text-center">Fecha de Vencimiento</th>
                            <th class="text-danger dt-center">Días Vencidos</th>
                            <th class="text-center">Monto Total (USD)</th>
                            <th class="text-center">Saldo Pendiente (USD)</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php

                        $cuentasPorCobrar = ControllerVentas::ctrObtenerCuentasPorCobrar();

                        foreach ($cuentasPorCobrar as $key => $value) {

                            // --- Cálculo de Días Vencidos ---
                            $fechaVencimiento = new DateTime($value["fecha_vencimiento"]);
                            $hoy = new DateTime();
                            $diasVencidos = 0;
                            if ($hoy > $fechaVencimiento) {
                                $intervalo = $hoy->diff($fechaVencimiento);
                                $diasVencidos = $intervalo->days;
                            }

                            echo '<tr>

                                <td class="dt-center">' . ($key + 1) . '</td>';

                            echo '<td class="text-center">' . htmlspecialchars($value["nombre_cliente"]) . '</td>';
                            echo '<td class="text-center">' . htmlspecialchars($value["codigo_venta"]) . '</td>';
                            echo '<td class="text-center">' . date("d/m/Y", strtotime($value["fecha_vencimiento"])) . '</td>';
                            echo '<td class="text-center"><strong>' . $diasVencidos . '</strong></td>';
                            echo '<td class="text-center">' . number_format($value["monto_total_usd"], 2) . ' $</td>';
                            echo '<td class="text-center"><strong>' . number_format($value["saldo_pendiente_usd"], 2) . ' $</strong></td>';
                            echo '<td class="text-center">' . htmlspecialchars($value["estado"]) . '</td>';

                            // --- Botón "Registrar Cobro" ---
                            $datosBoton = [
                                "id-venta" => $value["id_venta"],
                                "saldo-pendiente" => $value["saldo_pendiente_usd"],
                                "cliente" => htmlspecialchars($value["nombre_cliente"], ENT_QUOTES)
                            ];

                            // --- El Botón "Registrar Cobro" ---
                            echo '<td>
                                    <div class="btn-group">
                                        <button class="label btn p-2 btn-success btn-ms btnRegistrarCobro" 
                                                data-id-venta="' . $datosBoton["id-venta"] . '" 
                                                data-saldo-pendiente="' . $datosBoton["saldo-pendiente"] . '"
                                                data-cliente="' . $datosBoton["cliente"] . '"
                                                data-toggle="modal" data-target="#modalRegistrarPago">
                                            <i class="fa fa-money"></i> Registrar Cobro
                                        </button>
                                    </div>
                                </td>';

                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- =============================================
MODAL REGISTRAR PAGO DE CLIENTE
============================================== -->
<div id="modalRegistrarPago" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background:#00a65a; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Registrar Pago a Factura</h4>
                </div>

                <div class="modal-body">
                    <div class="box-body">

                        <input type="hidden" id="pagoIdVenta" name="pagoIdVenta">

                        <p><strong>Cliente:</strong> <span id="pagoNombreCliente"></span></p>
                        <p><strong>Saldo Pendiente:</strong> <span id="pagoSaldoPendiente"></span> USD</p>
                        <hr>

                        <!-- Fecha del Pago -->
                        <div class="form-group">
                            <div class="col-lg-4 mb-2">
                                <label class="control-label">Fecha del Pago:</label>
                                <input type="date" class="form-control" name="pagoFecha" required>
                            </div>
                        </div>

                        <!-- Monto Recibido -->
                        <div class="form-group">
                            <div class="col-lg-4 mb-2">
                                <label>Monto Recibido:</label>
                                <input type="number" step="0.01" class="form-control" id="pagoMonto" name="pagoMonto"
                                    placeholder="0.00" required>
                            </div>
                        </div>

                        <!-- Selector de Moneda -->
                        <div class="form-group">
                            <div class="col-lg-4 mb-2">
                                <label>Moneda del Pago:</label>
                                <select class="form-control" id="pagoMoneda" name="pagoMoneda" required>
                                    <option value="USD">Dólares (USD)</option>
                                    <option value="VES">Bolívares (VES)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Campo para Tasa de Cambio (inicialmente oculto) -->
                        <?php
                            require_once "controller/divisas.controller.php";
                            require_once "model/divisas.model.php";
                            $tasaUSD = ControllerDivisas::ctrObtenerTasaActual("USD");
                            $valorTasa = $tasaUSD ? number_format($tasaUSD, 2, ',', '.') : '';
                        ?>
                        <div class="form-group" id="pagoTasaCambioContainer" style="display: none;">
                            <div class="col-lg-4 mb-2">
                                <label>BCV del día del pago:</label>
                                <input type="number" step="0.01" class="form-control" name="pagoTasaCambio"
                                    placeholder="Bolivar por Dólar" value="<?php echo $tasaUSD; ?>">
                            </div>
                        </div>

                        <!-- Método de Pago -->
                        <div class="form-group">
                            <div class="col-lg-8 mb-2">
                                <label>Método de Pago:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="pagoMetodo"
                                        placeholder="Ej: Zelle, Efectivo, Pago Móvil" required>
                                </div>
                            </div>
                        </div>

                        <!-- Referencia -->
                        <div class="form-group">
                            <div class="col-lg-12 mb-2">
                                <label>Referencia:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="pagoReferencia"
                                        placeholder="Nº de confirmación (opcional)">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Pago</button>
                </div>

                <?php
                // Lógica para crear el objeto del controlador y llamar al método
                $registrarPago = new ControllerVentas();
                $registrarPago->ctrRegistrarPagoCliente();
                ?>
            </form>
        </div>
    </div>
</div>