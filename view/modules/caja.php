<?php
// =================================================================
// BLOQUE DE SEGURIDAD Y CARGA DE DATOS INICIAL
// Se verifica que el perfil del usuario tenga permisos para esta vista.
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

// --- 2. Preparamos las variables que se pasarán a JavaScript ---
$tasaBCV = ControllerDivisas::ctrObtenerTasaActual("USD");
$ivaPorcentaje = $_SESSION['config']['iva_porcentaje'] ?? 16.00;
$monedaPrincipal = $_SESSION['config']['moneda_principal'] ?? 'USD';
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
                    
                    echo '
                        <button type="button" class="category-card btn-categoria active" data-id-categoria="todos">
                            <div class="title">TODOS</div>
                        </button>
                    ';
                    
                    foreach ($categorias as $key => $value) {
                        echo '
                            <button type="button" class="category-card btn-categoria" data-id-categoria="' . $value["id_categoria"] . '">
                                <div class="title">' . htmlspecialchars($value["categoria"]) . '</div>
                            </button>
                            ';
                    }
                    
                    echo '</div>';

                } else {
                    // Este mensaje ahora solo aparecerá si NINGUNA categoría tiene productos.
                    echo '
                        <div class="alert alert-warning" role="alert">
                        No hay categorías con productos disponibles.
                        </div>
                    ';
                }
                ?>


                <!-- CUADRICULA DE PRODUCTOS (AHORA SE LLENARÁ CON JAVASCRIPT) -->
                <div class="product-grid" id="productosCategoria">
                    <!-- Los productos se cargarán aquí dinámicamente -->
                </div>
            </div>

            <!--=====================================
             SECCION DE VENTA (DERECHA)
            ======================================-->
            <div class="col-md-5">
                <div class="order-sidebar">
                    <form role="form" method="post" class="formularioVentas" id="formularioVentas" style="display: flex; flex-direction: column; height: 100%;">

                        <!-- HEADER DE LA ORDEN (DATOS DE LA VENTA) -->
                        <div class="form-group" style="display: flex;">
                            <div class="input-group" style="width: 20%;">
                                <?php
                                $item = null;
                                $valor = null;
                                $ventas = ControllerVentas::ctrMostrarVenta($item, $valor);

                                if (!$ventas) {
                                    echo '<input type="text" class="form-control" name="codigoVenta" id="codigoVenta" readonly="readonly" value="10001">';
                                } else {
                                    foreach ($ventas as $key => $value) {
                                    }
                                    $codigo = $value["codigo_venta"] + 1;
                                    echo '<input type="text" class="form-control" name="codigoVenta" id="codigoVenta" readonly="readonly" value="' . $codigo . '">';
                                }
                                ?>
                            </div>
                            <div class="input-group" style="width: 80%;padding-left: 10px;">
                                <input type="text" class="form-control" readonly="readonly"
                                    value="<?php echo $_SESSION['nombre']; ?>" name="nombreVendedor"
                                    id="nombreVendedor">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group" style="display: flex;">
                                <select class="form-control select2" id="seleccionarCliente" name="seleccionarCliente" required="required">
                                    <option value="">Seleccionar cliente</option>
                                    <?php
                                    $item = null;
                                    $valor = null;
                                    $clientes = ControllerClients::ctrMostrarClientes($item, $valor);
                                    foreach ($clientes as $key => $value) {
                                        echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                                    }
                                    ?>
                                </select>
                                <button type="button" class="btn btn-default " data-toggle="modal"
                                    data-target="#modalAgregarCliente" data-dismiss="modal" style="width: 15%; margin-left: 10px;">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                        <!-- LISTA DE ITEMS EN LA ORDEN -->
                        <div class="order-items nuevoProducto">
                            <!-- Los productos se añadirán aquí dinámicamente -->
                        </div>

                        <!-- PIE DE PAGINA DE LA ORDEN (TOTALES Y BOTÓN DE PAGO) -->
                        <div class="order-footer">
                            <!-- El div de totals-box ahora usará los nuevos estilos -->
                            <div class="totals-box">

                                <!-- Fila para Subtotal (USD y Bs) -->
                                <div class="total-row">
                                    <span>Subtotal:</span>
                                    <div>
                                        <span id="subtotalUsdDisplay">$0.00</span>
                                        <span id="subtotalBsDisplay" class="monto-bs">(0.00 Bs)</span>
                                    </div>
                                </div>

                                <!-- Fila para IVA (USD y Bs) -->
                                <div class="total-row">
                                    <span>IVA (<?php echo $ivaPorcentaje; ?>%):</span>
                                    <div>
                                        <span id="ivaUsdDisplay">$0.00</span>
                                        <span id="ivaBsDisplay" class="monto-bs">(0.00 Bs)</span>
                                    </div>
                                </div>
                                
                                <!-- Línea separadora decorativa -->
                                <div class="divider"></div>

                                <!-- Fila para Gran Total en Bs (moneda secundaria) -->
                                <div class="total-row grand-total grand-total-bs">
                                    <span>Total a Pagar (Bs):</span>
                                    <span id="displayTotalBs" class="total-display-bs">0.00 Bs</span>
                                </div>
                                
                                <!-- Fila para Gran Total en USD (moneda principal) -->
                                <div class="total-row grand-total">
                                    <span>Total a Pagar ($):</span>
                                    <!-- Se cambió el input por un span para consistencia y se le añadió una clase -->
                                    <span id="displayTotalVenta" class="total-display-usd">$0.00</span>
                                </div>

                            </div>

                            <!-- BOTON PARA INICIAR EL PROCESO DE PAGO -->
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalMetodoPago" style="margin-top:20px; padding: 15px; font-size: 16px; font-weight: 600;">
                                PROCEDER AL PAGO
                            </button>

                            <!-- Inputs Ocultos del Formulario (sin cambios, pero importante que sigan aquí) -->
                            <input type="hidden" name="idVendedor" value="<?php echo $_SESSION['id_usuario']; ?>">
                            <input type="hidden" name="listaMetodoPago" id="listaMetodoPago" required>
                            <input type="hidden" name="listaProductosCaja" id="listaProductosCaja" required>
                            <!-- Este input sigue siendo clave para el backend, aunque ya no sea visible -->
                            <!-- <input type="hidden" name="nuevoTotalVenta" id="nuevoTotalVenta" required> -->
                            <input type="hidden" name="totalVenta" id="totalVenta" required>
                        </div>
                    </form>
                    <?php
                    // Instancia del controlador para procesar la creación de la venta
                    $crearVenta = new ControllerVentas();
                    $crearVenta->ctrCrearVenta();
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- ====================================================================== -->
<!-- MODAL 1: SELECCIONAR MÉTODO DE PAGO (DISEÑO MEJORADO) -->
<!-- ====================================================================== -->
<div id="modalMetodoPago" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg"> <!-- modal-lg para más espacio -->
        <div class="modal-content">
            <div class="modal-header" style="background:#3c8dbc; color:white">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Seleccionar Método de Pago</h4>
            </div>
            <div class="modal-body">
                <div class="payment-columns-container">

                    <!-- COLUMNA PARA PAGOS EN BOLÍVARES -->
                    <div class="payment-group">
                        <h4>Pagos en Bolívares (Bs)</h4>
                        <p class="total-a-pagar">Total: <strong id="totalPagarBs">0.00 Bs</strong></p>
                        <hr>
                        <div class="payment-option-list">
                            <button class="payment-option" data-value="Efectivo-BS" data-text="Efectivo Bs">
                                <span>Efectivo Bs</span>
                            </button>
                            <button class="payment-option" data-value="Pago-Movil" data-text="Pago Móvil">
                                <span>Pago Móvil</span>
                            </button>
                            <button class="payment-option" data-value="Punto-Venta" data-text="Punto de Venta">
                                <span>Punto de Venta</span>
                            </button>
                             <button class="payment-option" data-value="Transferencia" data-text="Transferencia">
                                <span>Transferencia</span>
                            </button>
                        </div>
                    </div>

                    <!-- COLUMNA PARA PAGOS EN DÓLARES -->
                    <div class="payment-group">
                        <h4>Pagos en Dólares ($)</h4>
                        <p class="total-a-pagar">Total: <strong id="totalPagarUsd">$0.00</strong></p>
                        <hr>
                        <div class="payment-option-list">
                            <button class="payment-option" data-value="Efectivo-USD" data-text="Efectivo $">
                                <span>Efectivo $</span>
                            </button>
                            <button class="payment-option" data-value="Zelle" data-text="Zelle">
                                <span>Zelle</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- ====================================================================== -->
<!-- MODAL 2: CONFIRMAR PAGO (VERSIÓN FINAL CON INPUTS DE CONVERSIÓN) -->
<!-- ====================================================================== -->
<div id="modalConfirmacionPago" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:#28a745; color:white">
                <h4 class="modal-title">Confirmar Pago y Vuelto</h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <div class="row">
                        <!-- COLUMNA DE DÓLARES -->
                        <div class="col-md-6">
                            <div class="price-summary-box">
                                <h5 class="text-center">Resumen en Dólares ($)</h5>
                                <div class="summary-row">
                                    <label>Total a Pagar:</label>
                                    <span class="amount-display" id="totalPagarModalUsd">$0.00</span>
                                </div>
                                <div class="form-group">
                                    <label>Dinero Recibido ($):</label>
                                    <input type="text" class="form-control" id="montoRecibidoUsd" placeholder="0.00" inputmode="decimal">
                                    <!-- Input readonly para la conversión -->
                                    <input type="text" class="form-control conversion-display" id="conversionDisplayBs" readonly>
                                </div>
                                <div class="vuelto-container" style="display:none;">
                                    <label>Vuelto en $:</label>
                                    <span class="vuelto-display" id="vueltoDisplayUsd">$0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- COLUMNA DE BOLÍVARES -->
                        <div class="col-md-6">
                            <div class="price-summary-box">
                                <h5 class="text-center">Resumen en Bolívares (Bs)</h5>
                                <div class="summary-row">
                                    <label>Total a Pagar:</label>
                                    <span class="amount-display" id="totalPagarModalBs">0.00 Bs</span>
                                </div>
                                <div class="form-group">
                                    <label>Dinero Recibido (Bs):</label>
                                    <input type="text" class="form-control" id="montoRecibidoBs" placeholder="0.00" inputmode="decimal">
                                    <!-- Input readonly para la conversión -->
                                    <input type="text" class="form-control conversion-display" id="conversionDisplayUsd" readonly>
                                </div>
                                <div class="vuelto-container" style="display:none;">
                                    <label>Vuelto en Bs:</label>
                                    <span class="vuelto-display" id="vueltoDisplayBs">0.00 Bs</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fila para Alertas -->
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-xs-12">
                            <div id="alertaMontoInsuficiente" class="alert alert-danger text-center" style="display:none;">
                                El monto recibido es menor al total de la venta.
                            </div>
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

<!-- =================================================================
// MODAL PARA AGREGAR NUEVO CLIENTE (CÓDIGO ORIGINAL RESTAURADO)
// ================================================================= -->
<div id="modalAgregarCliente" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background:#3c8dbc; color:white"><button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Agregar cliente</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group col-lg-3">
                            <div class="input-group"><label><small style="color: #000;">Codigo:</small><?php $item = null;
                                                                                                        $valor = null;
                                                                                                        $codigo_cliente = ControllerClients::ctrMostrarClientes($item, $valor);
                                                                                                        if (!$codigo_cliente) {
                                                                                                            echo '<input type="text" class="text-center form-control input-lg" name="codigoCliente" id="codigoCliente" readonly="readonly" value="' . mt_rand(10000000, 99999999) . '">';
                                                                                                        } else {
                                                                                                            foreach ($codigo_cliente as $key => $value) {
                                                                                                            }
                                                                                                            $codigo = $value["codigo"] + 1;
                                                                                                            echo '<input type="text" class="text-center form-control input-lg" name="codigoCliente" id="codigoCliente" readonly="readonly" value="' . $codigo . '">';
                                                                                                        } ?></label></div>
                        </div>
                        <div class="form-group col-lg-4">
                            <div class="input-group"><label style="color: red;"> * <small style="color: #000;">Nombre y apellido:</small><input type="text" class="form-control input-lg" name="nuevoNombre" placeholder="Nombre y apellido" required></label></div>
                        </div>
                        <div class="form-group col-lg-2">
                            <div class="input-group"><label style="color: red;"> * <small style="color: #000;">Nacionalidad:</small><select class="form-control input-lg" name="nuevaNacionalidad" required>
                                        <option value=""></option>
                                        <option value="V">V</option>
                                        <option value="J">J</option>
                                        <option value="G">G</option>
                                        <option value="C">C</option>
                                        <option value="E">E</option>
                                    </select></label></div>
                        </div>
                        <div class="form-group col-lg-3">
                            <div class="input-group"><label style="color: red;"> * <small style="color: #000;">Documento:</small><input type="text" min="0" class="form-control input-lg" name="nuevoDocumento" placeholder="Documento" required></label></div>
                        </div>
                        <div class="form-group col-lg-6">
                            <div class="input-group"><label><small style="color: #000;">Teléfono:</small><input type="text" class="form-control input-lg" name="nuevoTelefono" placeholder="Teléfono" data-inputmask="'mask':'(9999) 999-99-99'" data-mask></label></div>
                        </div>
                        <div class="form-group col-lg-6">
                            <div class="input-group"><label><small style="color: #000;">Email:</small><input type="email" class="form-control input-lg" name="nuevoEmail" placeholder="Correo Electronico"></label></div>
                        </div>
                        <div class="form-group col-lg-12">
                            <div class="input-group"><label><small style="color: #000;">Dirección:</small><input type="text" class="form-control input-lg" name="nuevaDireccion" placeholder="Ingresar dirección"></label></div>
                        </div>
                    </div>
                    <span style="color: red;">( * ) Campo obligatorio</span>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Guardar</button></div>
            </form>
            <?php
            $crearCliente = new ControllerClients();
            $crearCliente->ctrCrearClientes();
            ?>
        </div>
    </div>
</div>