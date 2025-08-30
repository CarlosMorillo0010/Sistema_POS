<?php
// =================================================================
// BLOQUE DE SEGURIDAD Y CARGA DE DATOS INICIAL
// =================================================================
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value):
    if ($_SESSION["perfil"] == $value["perfil"] && $value["caja"] == "N") {
        echo '
            <script>
            window.location = "inicio";
            </script>
        ';
        return;
    }
endforeach;

// --- DATOS PARA JS Y FORMULARIOS ---
$tasaBCV = ControllerDivisas::ctrObtenerTasaActual("USD");
$ivaPorcentaje = $_SESSION['config']['iva_porcentaje'] ?? 16.00;
$monedaPrincipal = $_SESSION['config']['moneda_principal'] ?? 'USD';
$estados = ["Amazonas", "Anzoátegui", "Apure", "Aragua", "Barinas", "Bolívar", "Carabobo", "Cojedes", "Delta Amacuro", "Distrito Capital", "Falcón", "Guárico", "Lara", "Mérida", "Miranda", "Monagas", "Nueva Esparta", "Portuguesa", "Sucre", "Táchira", "Trujillo", "Vargas", "Yaracuy", "Zulia"];
?>

<!-- =================================================================
// INCLUSIÓN DE FUENTES Y ESTILOS EXTERNOS
// ================================================================= -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div id="config-vars"
    data-tasa-bcv="<?php echo htmlspecialchars($tasaBCV, ENT_QUOTES, 'UTF-8'); ?>"
    data-iva-porcentaje="<?php echo htmlspecialchars($ivaPorcentaje, ENT_QUOTES, 'UTF-8'); ?>"
    data-moneda-principal="<?php echo htmlspecialchars($monedaPrincipal, ENT_QUOTES, 'UTF-8'); ?>"
    style="display: none;">
</div>

<div class="content-wrapper">
    <section class="content pos-container">

        <div class="row">
            <!--===================================== 
             SECCION DE PRODUCTOS (IZQUIERDA)
            ======================================-->
            <div class="col-md-7">
                <!-- CATEGORIAS -->
                <?php
                $categorias = ControllerCategories::ctrMostrarCategoriasConProductos();
                if (!empty($categorias)) {
                    echo '<div class="category-tabs">';
                    echo '<button type="button" class="category-card btn-categoria active" data-id-categoria="todos"><div class="title">TODOS</div></button>';
                    foreach ($categorias as $key => $value) {
                        echo '<button type="button" class="category-card btn-categoria" data-id-categoria="' . $value["id_categoria"] . '"><div class="title">' . htmlspecialchars($value["categoria"]) . '</div></button>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-warning" role="alert">No hay categorías con productos disponibles.</div>';
                }
                ?>
                <!-- BUSCADOR DE PRODUCTOS -->
                <div class="form-group" style="margin-top: 15px; margin-bottom: 15px;">
                    <div class="input-group">
                        <input type="text" class="form-control" id="buscadorProductos" placeholder="Buscar producto por nombre o código...">
                    </div>
                </div>
                <!-- CUADRICULA DE PRODUCTOS -->
                <div class="product-grid" id="productosCategoria">
                    <!-- Los productos se cargarán aquí dinámicamente -->
                </div>
            </div>

            <!--===================================== 
             SECCION DE VENTA (DERECHA)
            ======================================-->
            <div class="col-md-5">
                <div class="order-sidebar mb-4">
                    <form role="form" method="post" class="formularioVentas" id="formularioVentas" style="display: flex; flex-direction: column; height: 100%;">
                        <!-- HEADER DE LA ORDEN -->
                        <div class="form-group" style="display: flex;">
                            <div class="input-group" style="width: 20%;">
                                <?php
                                $item = null;
                                $valor = null;
                                $ventas = ControllerVentas::ctrMostrarVenta($item, $valor);
                                if (!$ventas) {
                                    echo '<input type="text" class="form-control" name="codigoVenta" id="codigoVenta" readonly="readonly" value="10001">';
                                } else {
                                    foreach ($ventas as $key => $value) {}
                                    $codigo = $value["codigo_venta"] + 1;
                                    echo '<input type="text" class="form-control" name="codigoVenta" id="codigoVenta" readonly="readonly" value="' . $codigo . '">';
                                }
                                ?>
                            </div>
                            <div class="input-group" style="width: 80%;padding-left: 10px;">
                                <input type="text" class="form-control" readonly="readonly" value="<?php echo $_SESSION['nombre']; ?>" name="nombreVendedor" id="nombreVendedor">
                            </div>
                        </div>
                        <!-- BUSCADOR DE CLIENTE -->
                        <div class="form-group">
                            <label for="buscadorClienteCedula">Buscar Cliente:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="buscadorClienteCedula" name="buscadorClienteCedula" placeholder="Cédula/RIF" style="width: 25%;">
                                <input type="text" class="form-control" id="nombreClienteDisplay" name="nombreClienteDisplay" placeholder="Nombre del Cliente" readonly style="width: 65%;">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarCliente" title="Añadir Nuevo Cliente">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="seleccionarCliente" id="seleccionarCliente" required>
                        </div>
                        <!-- LISTA DE ITEMS -->
                        <div class="order-items nuevoProducto mb-4">
                            <!-- Los productos se añadirán aquí dinámicamente -->
                        </div>
                </div>
                <!-- FOOTER DE LA ORDEN -->
                <div class="order-footer">
                    <div class="totals-box">
                        <div class="total-row"><span>Subtotal:</span><div><span id="subtotalUsdDisplay">$0.00</span><span id="subtotalBsDisplay" class="monto-bs">(0.00 Bs)</span></div></div>
                        <div class="total-row"><span>IVA (<?php echo $ivaPorcentaje; ?>%):</span><div><span id="ivaUsdDisplay">$0.00</span><span id="ivaBsDisplay" class="monto-bs">(0.00 Bs)</span></div></div>
                        <div class="divider"></div>
                        <div class="total-row grand-total grand-total-bs"><span>Total a Pagar (Bs):</span><span id="displayTotalBs" class="total-display-bs">0.00 Bs</span></div>
                        <div class="total-row grand-total"><span>Total a Pagar ($):</span><span id="displayTotalVenta" class="total-display-usd">$0.00</span></div>
                    </div>
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalMetodoPago" style="margin-top:20px; padding: 15px; font-size: 16px; font-weight: 600;">PROCEDER AL PAGO</button>
                    <!-- Inputs Ocultos -->
                    <input type="hidden" name="idVendedor" value="<?php echo $_SESSION['id_usuario']; ?>">
                    <input type="hidden" name="listaMetodoPago" id="listaMetodoPago" required>
                    <input type="hidden" name="listaProductosCaja" id="listaProductosCaja" required>
                    <input type="hidden" name="tasaDelDia" id="tasaDelDia">
                    <input type="hidden" name="subtotalUsd" id="subtotalUsd">
                    <input type="hidden" name="subtotalBs" id="subtotalBs">
                    <input type="hidden" name="ivaUsd" id="ivaUsd">
                    <input type="hidden" name="ivaBs" id="ivaBs">
                    <input type="hidden" name="totalUsd" id="totalUsd">
                    <input type="hidden" name="totalBs" id="totalBs">
                </div>
                </form>
                <?php
                $crearVenta = new ControllerVentas();
                $crearVenta->ctrCrearVenta();
                ?>
            </div>
        </div>
    </section>
</div>

<!-- MODAL SELECCIONAR MÉTODO DE PAGO -->
<div id="modalMetodoPago" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:#3c8dbc; color:white">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Seleccionar Método de Pago</h4>
            </div>
            <div class="modal-body">
                <div class="payment-columns-container">
                    <div class="payment-group">
                        <h4>Pagos en Bolívares (Bs)</h4>
                        <p class="total-a-pagar">Total: <strong id="totalPagarBs">0.00 Bs</strong></p>
                        <hr>
                        <div class="payment-option-list">
                            <button class="payment-option" data-value="Efectivo-BS"><span>Efectivo Bs</span></button>
                            <button class="payment-option" data-value="Pago-Movil"><span>Pago Móvil</span></button>
                            <button class="payment-option" data-value="Punto-Venta"><span>Punto de Venta</span></button>
                            <button class="payment-option" data-value="Transferencia"><span>Transferencia</span></button>
                        </div>
                    </div>
                    <div class="payment-group">
                        <h4>Pagos en Dólares ($)</h4>
                        <p class="total-a-pagar">Total: <strong id="totalPagarUsd">$0.00</strong></p>
                        <hr>
                        <div class="payment-option-list">
                            <button class="payment-option" data-value="Efectivo-USD"><span>Efectivo $</span></button>
                            <button class="payment-option" data-value="Zelle"><span>Zelle</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CONFIRMAR PAGO -->
<div id="modalConfirmacionPago" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:#28a745; color:white">
                <h4 class="modal-title">Confirmar Pago y Vuelto</h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="price-summary-box">
                                <h5 class="text-center">Resumen en Dólares ($)</h5>
                                <div class="summary-row"><label>Total a Pagar:</label><span class="amount-display" id="totalPagarModalUsd">$0.00</span></div>
                                <div class="form-group">
                                    <label>Dinero Recibido ($):</label>
                                    <input type="text" class="form-control" id="montoRecibidoUsd" placeholder="0.00" inputmode="decimal">
                                    <input type="text" class="form-control conversion-display" id="conversionDisplayBs" readonly>
                                </div>
                                <div class="vuelto-container" style="display:none;"><label>Vuelto en $:</label><span class="vuelto-display" id="vueltoDisplayUsd">$0.00</span></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="price-summary-box">
                                <h5 class="text-center">Resumen en Bolívares (Bs)</h5>
                                <div class="summary-row"><label>Total a Pagar:</label><span class="amount-display" id="totalPagarModalBs">0.00 Bs</span></div>
                                <div class="form-group">
                                    <label>Dinero Recibido (Bs):</label>
                                    <input type="text" class="form-control" id="montoRecibidoBs" placeholder="0.00" inputmode="decimal">
                                    <input type="text" class="form-control conversion-display" id="conversionDisplayUsd" readonly>
                                </div>
                                <div class="vuelto-container" style="display:none;"><label>Vuelto en Bs:</label><span class="vuelto-display" id="vueltoDisplayBs">0.00 Bs</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-xs-12">
                            <div id="alertaMontoInsuficiente" class="alert alert-danger text-center" style="display:none;">El monto recibido es menor al total de la venta.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" id="btnVolverMetodos"><i class="fa fa-arrow-left"></i> Volver</button>
                <button type="button" class="btn btn-success btn-lg" id="btnConfirmarYCrearVenta" disabled><i class="fa fa-check-circle"></i> CREAR VENTA</button>
            </div>
        </div>
    </div>
</div>

<!--===================================== 
MODAL AGREGAR CLIENTE (DESDE CAJA)
======================================-->
<div id="modalAgregarCliente" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar cliente</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label style="color: red;"> * <small style="color: #000;">Nombre o Razón Social:</small></label>
                                <input type="text" class="form-control input-lg" name="nuevoNombre" placeholder="Nombre o Razón Social" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label style="color: red;"> * <small style="color: #000;" class="document-label">Cédula de Identidad:</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="padding: 0; border: none;">
                                        <select class="form-control input-lg nacionalidad-select" name="nuevaNacionalidad" required style="width: 80px;">
                                            <option value="V">V</option>
                                            <option value="E">E</option>
                                            <option value="P">P</option>
                                            <option value="J">J</option>
                                            <option value="G">G</option>
                                            <option value="C">C</option>
                                        </select>
                                    </span>
                                    <input type="text" class="form-control input-lg" name="nuevoDocumento" placeholder="Número de Documento" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Teléfono Celular:</small></label>
                                <input type="text" class="form-control input-lg" name="nuevoTelefono1" placeholder="Ej: (0414)-123.45.67" data-inputmask="{'mask': '(9999)-999.99.99'}" data-mask>
                            </div>
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Teléfono Fijo:</small></label>
                                <input type="text" class="form-control input-lg" name="nuevoTelefono2" placeholder="Ej: (0212)-987.65.43" data-inputmask="{'mask': '(9999)-999.99.99'}" data-mask>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Estado:</small></label>
                                <select class="form-control input-lg" name="nuevoEstado">
                                    <option value="">Seleccionar Estado</option>
                                    <?php
                                    foreach($estados as $estado){
                                        echo "<option value=\"$estado\">$estado</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Ciudad:</small></label>
                                <input type="text" class="form-control input-lg" name="nuevaCiudad" placeholder="Ciudad">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label><small style="color: #000;">Dirección Específica:</small></label>
                                <input type="text" class="form-control input-lg" name="nuevaDireccion" placeholder="Av. Principal, Edificio, Piso, etc.">
                            </div>
                        </div>
                    </div>
                    <span style="color: red;">( * ) Campo obligatorio</span>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar cliente</button>
                </div>
            </form>
            <?php
            $crearCliente = new ControllerClients();
            $crearCliente->ctrCrearClientes();
            ?>
        </div>
    </div>
</div>

<script>
$(document).on('change', '.nacionalidad-select', function() {
    var selectedType = $(this).val();
    var formGroup = $(this).closest('.form-group');
    var label = formGroup.find('.document-label');
    var input = $(this).closest('.input-group').find('input[type="text"]');

    // Clear existing mask and value
    input.inputmask('remove');
    input.val('');

    if (selectedType === 'J' || selectedType === 'G' || selectedType === 'C') {
        label.text('RIF:');
        input.attr('placeholder', 'Número de RIF');
        input.inputmask({ 'mask': '99999999-9' });
    } else { // V, E, P
        label.text('Cédula de Identidad:');
        input.attr('placeholder', 'Número de Cédula');
    }
});
</script>