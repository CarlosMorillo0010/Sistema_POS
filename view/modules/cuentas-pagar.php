<?php
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value):
    if ($_SESSION["perfil"] == $value["perfil"] && $value["compras"] == "N") {
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
            Cuentas por pagar
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li>Cuentas por pagar</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-body">
                <table class="table table-bordered table-striped tablas">
                    <thead>
                        <tr>
                            <th class="dt-center" style="width:5%">#</th>
                            <th class="dt-center">Proveedor</th>
                            <th class="dt-center">Factura N°</th>
                            <th class="dt-center">Monto</th>
                            <th class="dt-center">IVA</th>
                            <th class="dt-center">Total</th>
                            <th class="dt-center">Emision</th>
                            <th class="text-danger dt-center">Vencimiento</th>
                            <th class="dt-center">Estado</th>
                            <th class="dt-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $item = null;
                        $valor = null;
                        $cuentas_pagar = ControllerCuentasPagar::ctrMostrarCuentasPagar($item, $valor);

                        foreach ($cuentas_pagar as $key => $value) {

                            echo '<tr>
                                    <td class="dt-center">' . ($key + 1) . '</td>
                                    <td class="dt-center">' . $value["proveedor"] . '</td>
                                    <td class="dt-center">' . $value["factura"] . '</td>
                                    <td class="dt-center">' . number_format($value["monto"], 2, ',', '.') . '</td>
                                    <td class="dt-center">' . number_format($value["iva"], 2, ',', '.') . '</td>
                                    <td class="dt-center">' . number_format($value["total"], 2, ',', '.') . '</td>
                                    <td class="dt-center">' . $value["fecha_emision"] . '</td>
                                    <td class="dt-center"><strong>' . $value["fecha_vencimiento"] . '</strong></td>';

                            // Colorear el estado para mejor visualización
                            if ($value["estado"] == "Pendiente") {
                                echo '<td class="dt-center"><button class="btn btn-danger btn-xs">Pendiente</button></td>';
                            } else {
                                echo '<td class="dt-center"><button class="btn btn-success btn-xs">Pagada</button></td>';
                            }

                            echo '<td class="dt-center">

                                    <div class="btn-group">

                                        <button class="btn btn-dark btnVerCuentaPagar" data-toggle="modal" data-target="#modalVerCuentaPagar" idCuentaPagar="' . $value["id_libro_compra"] . '"><i class="fa fa-eye"></i></button>';

                            if ($value["estado"] == "Pendiente") {
                                echo '<button class="btn btn-success btnPagarDeuda" idCuentaPagar="' . $value["id_libro_compra"] . '" data-toggle="modal" data-target="#modalRealizarPago" title="Registrar Pago"><i class="fa fa-money"></i></button>';
                            }

                            echo '  </div>

                                </td>

                            </tr>';
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

<!---------------------------------------
MODAL VISUALIZAR CUENTA PAGAR
---------------------------------------->
<div id="modalVerCuentaPagar" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <!--=====================================
            CABEZA DEL MODAL
            ======================================-->
            <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                <div class="row justify-content-between">
                    <h4 class="col-md-7">Deuda con <strong><?php echo $value["proveedor"]; ?></strong></h4>
                    <h4 class="col-md-4 text-right">Factura N°<strong><?php echo $value["factura"]; ?></strong></h4>
                    <div class="col-md-1">
                        <button type="button" class="close" data-dismiss="modal"><span
                                class="glyphicon glyphicon-remove"></span></button>
                    </div>
                </div>
            </div>

            <!--=====================================
            CUERPO DEL MODAL
            ======================================-->
            <div class="modal-body" style="padding: 0;">
                <div class="box-body">
                    <div class="form-group">

                        <!-- ENTRADA PARA EL MONTO -->
                        <div class="col-lg-4 mb-2">
                            <label style="color: #000;" for="verMonto" class="text-center">Monto:</label>
                            <input type="number" class="form-control input-lg ver" style="text-align: center;"
                                id="verMonto" readonly>
                        </div>

                        <!-- ENTRADA PARA EL IVA -->
                        <div class="col-lg-4 mb-2">
                            <label style="color: #000;" for="verIva" class="text-center">IVA:</label>
                            <input type="number" class="form-control input-lg ver" style="text-align: center;"
                                id="verIva" readonly>
                        </div>

                        <!-- ENTRADA PARA EL TOTAL -->
                        <div class="col-lg-4 mb-2">
                            <label style="color: #000;" for="verTotal" class="text-center">Total:</label>
                            <input type="number" class="form-control input-lg ver" style="text-align: center;"
                                id="verTotal" readonly>
                        </div>

                        <!-- ENTRADA PARA LA FECHA EMISION -->
                        <div class="col-lg-4 mb-2">
                            <label style="color: #000;" for="verFechaEmision" class="text-center">Fecha emision:</label>
                            <input type="text" class="form-control input-lg ver" style="text-align: center;"
                                id="verFechaEmision" readonly>
                        </div>

                        <!-- ENTRADA PARA LA FECHA VENCIMIENTO -->
                        <div class="col-lg-4 mb-2">
                            <label style="color: #000;" for="verFechaVencimiento" class="text-center">Fecha
                                vencimiento:</label>
                            <input type="text" class="form-control input-lg ver" style="text-align: center;"
                                id="verFechaVencimiento" readonly>
                        </div>
                    </div>

                    <!-- ENTRADA PARA LA FECHA PAGO-->
                    <div class="col-lg-4 mb-2">
                        <label style="color: #000;" for="verFechaPago" class="text-center">Fecha pago:</label>
                        <input type="text" class="form-control input-lg ver" style="text-align: center;"
                            id="verFechaPago" readonly>
                    </div>

                    <!-- ENTRADA PARA EL ESTADO -->
                    <div class="col-lg-12 mb-2">
                        <label style="color: #000;" for="verEstado" class="text-center">Estado:</label>
                        <input class="form-control input-lg ver" style="text-align: center;" id="verEstado" readonly>
                    </div>

                    <!-- ENTRADA PARA LA OBSERVACION -->
                    <div class="col-lg-12 mb-2">
                        <label style="color: #000;" for="verObservacion">Detalles:</label>
                        <textarea type="text" class="form-control input-lg ver" id="verNotaPago" style="resize: none;"
                            readonly></textarea>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>
</div>

<!--=====================================
MODAL REALIZAR PAGO
======================================-->
<div id="modalRealizarPago" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#00a65a; color:white">
                    <button type="button" class="close" data-dismiss="modal"><span
                            class="glyphicon glyphicon-remove"></span></button>
                    <h4 class="modal-title">Registrar Pago</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">

                            <p class="text-center">¿Está seguro de que desea marcar esta deuda como
                                <strong>PAGADA</strong>?
                            </p>
                            <p class="text-center">Esta acción no se puede deshacer fácilmente.</p>

                            <!-- Campo para notas sobre el pago -->
                            <div class="col-lg-12 mb-2">
                                <label for="notaPago">Nota del Pago (Opcional):</label>
                                <div class="input-group">
                                    <textarea class="form-control" name="notaPago" rows="2"
                                        placeholder="Detalles"></textarea>
                                </div>
                            </div>

                            <!-- Campo para la fecha de pago -->
                            <div class="col-lg-6 mb-2">
                                <label for="fechaPago">Fecha de Pago:</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="fechaPago"
                                        value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>

                            <!-- Campo oculto para enviar el ID de la cuenta a pagar -->
                            <input type="hidden" id="idCuentaPagar" name="idCuentaPagar">

                        </div>
                    </div>
                </div>
                <!-- PIE DEL MODAL -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Confirmar Pago</button>
                </div>
                <?php
                // Llamada al controlador para procesar el pago
                $pagarCuenta = new ControllerCuentasPagar();
                $pagarCuenta->ctrPagarCuenta();
                ?>
            </form>
        </div>
    </div>
</div>