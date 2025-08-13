<?php
// --- 1. Bloque Inicial PHP ---
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value) {
    if ($_SESSION["perfil"] == $value["perfil"] && $value["inventario"] == "N") {
        echo '<script>window.location = "inicio";</script>';
        return;
    }
}

// --- 2. Preparamos las variables que se pasarán a JavaScript ---
$tasaBCV = ControllerDivisas::ctrObtenerTasaActual("USD");
$ivaPorcentaje = $_SESSION['config']['iva_porcentaje'] ?? 16.00;
$monedaPrincipal = $_SESSION['config']['moneda_principal'] ?? 'USD';
?>

<style>
    .price-summary-box {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .price-summary-box .form-group {
        margin-bottom: 10px;
    }
    .price-summary-box h5 {
        margin-top: 0;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    .price-summary-box label {
        font-weight: normal;
        color: #555;
    }
    .price-summary-box .form-control[readonly] {
        background-color: #fff;
        cursor: text;
    }
    .price-summary-box .final-price-label {
        font-weight: bold;
        color: #333;
    }
    .price-summary-box .final-price-input {
        font-weight: bold;
        background-color: #e9f5e9 !important;
        color: #2b542c;
    }
    .btn-moneda-entrada.active {
        box-shadow: inset 0 3px 5px rgba(0,0,0,.125);
    }
</style>

<div id="config-vars"
     data-tasa-bcv="<?php echo htmlspecialchars($tasaBCV, ENT_QUOTES, 'UTF-8'); ?>"
     data-iva-porcentaje="<?php echo htmlspecialchars($ivaPorcentaje, ENT_QUOTES, 'UTF-8'); ?>"
     data-moneda-principal="<?php echo htmlspecialchars($monedaPrincipal, ENT_QUOTES, 'UTF-8'); ?>"
     style="display: none;">
</div>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Administrar productos</h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar producto</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProducto">
                    Agregar Producto
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablaProductos" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 10px;">#</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Categoría</th>
                            <th>Código</th>
                            <th>Stock</th>
                            <th>PVP ($)</th>
                            <th>PVP (Bs.)</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
                <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">
            </div>
        </div>
    </section>
</div>

<!--================================================
    MODAL AGREGAR PRODUCTO
=================================================-->
<div id="modalAgregarProducto" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post" enctype="multipart/form-data" id="formAgregarProducto">
                <input type="hidden" name="monedaEntrada" id="monedaEntrada_nuevo" value="<?php echo htmlspecialchars($monedaPrincipal, ENT_QUOTES, 'UTF-8'); ?>">

                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Agregar producto</h4>
                </div>

                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-lg-4 text-center">
                                <img src="view/img/products/default/anonymous.png" class="img-thumbnail previsualizar" width="150px">
                                <input type="file" class="imagenProducto" name="imagenProducto" style="margin-top: 15px;">
                                <p class="help-block">Peso máximo de la foto 2MB</p>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="form-group col-lg-6"><label for="nuevoCodigo"><span style="color: red;">*</span> Código:</label><input type="text" class="form-control" id="nuevoCodigo" name="nuevoCodigo" placeholder="Código del Producto" required></div>
                                    <div class="form-group col-lg-6"><label for="nuevaDescripcion"><span style="color: red;">*</span> Nombre:</label><input type="text" class="form-control" id="nuevaDescripcion" name="nuevaDescripcion" placeholder="Nombre del Producto" required></div>
                                    <div class="form-group col-lg-12"><label for="nuevaCategoria"><span style="color: red;">*</span> Categoría:</label><select class="form-control" id="nuevaCategoria" name="nuevaCategoria" required><option value="">Elija una categoría</option><?php $categorias = ControllerCategories::ctrMostrarCategoria(null, null); foreach ($categorias as $value) { echo '<option value="' . $value["id_categoria"] . '">' . $value["categoria"] . '</option>'; } ?></select></div>
                                    <div class="form-group col-lg-4"><label for="nuevaMarca"><span style="color: red;">*</span> Marca:</label><select class="form-control select2" id="nuevaMarca" name="nuevaMarca" style="width: 100%;" required><option value="">Elige una marca</option><?php $marcas = ControllerMarcas::ctrMostrarMarca(null, null); foreach ($marcas as $value) { echo '<option value="' . $value["marca"] . '">' . $value["marca"] . '</option>'; } ?></select></div>
                                    <div class="form-group col-lg-4"><label for="nuevoModelo"><span style="color: red;">*</span> Modelo:</label><select class="form-control select2" id="nuevoModelo" name="nuevoModelo" style="width: 100%;" required><option value="">Elige un modelo</option><?php $modelos = ControllerModelos::ctrMostrarModelos(null, null); foreach ($modelos as $value) { echo '<option value="' . $value["modelo"] . '">' . $value["modelo"] . '</option>'; } ?></select></div>
                                    <div class="form-group col-lg-4"><label for="nuevoAno">Año:</label><input type="number" min="1900" max="2100" class="form-control" id="nuevoAno" name="nuevoAno" placeholder="Ej: 2024"></div>
                                    <div class="form-group col-lg-4"><label for="nuevoStock"><span style="color: red;">*</span> Stock:</label><input type="number" class="form-control" id="nuevoStock" name="nuevoStock" min="0" value="0" required></div>
                                    <div class="form-group col-lg-8"><label for="nuevaUbicacion">Ubicación:</label><input type="text" class="form-control" id="nuevaUbicacion" name="nuevaUbicacion" placeholder="¿Dónde está ubicado?"></div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h4>Estructura de Precios (Tasa BCV: <?php echo number_format($tasaBCV, 2, ',', '.'); ?>)</h4>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Moneda de Costo:</label>
                                <div class="btn-group" style="width: 100%;">
                                    <button type="button" class="btn btn-flat btn-moneda-entrada" data-moneda="USD" style="width: 50%;">$ Dólares</button>
                                    <button type="button" class="btn btn-flat btn-moneda-entrada" data-moneda="VES" style="width: 50%;">Bs. Bolívares</button>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label id="label-costo" for="nuevoPrecioCosto"><span style="color: red;">*</span> Costo (Sin IVA)</label>
                                <input type="text" class="form-control" id="nuevoPrecioCosto" name="nuevoPrecioCosto" placeholder="0,00" required>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="nuevoPorcentajeGanancia"><span style="color: red;">*</span> % Utilidad</label>
                                <input type="text" class="form-control" id="nuevoPorcentajeGanancia" name="nuevoPorcentajeGanancia" value="30" placeholder="30" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nuevoTipoIva"><span style="color: red;">*</span> Condición de IVA</label>
                                <select class="form-control" id="nuevoTipoIva" name="nuevoTipoIva" required>
                                    <option value="gravado" data-tasa="<?php echo $ivaPorcentaje; ?>" selected>Grava IVA (<?php echo $ivaPorcentaje; ?>%)</option>
                                    <option value="exento" data-tasa="0">Exento de IVA</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12"><hr><p class="text-center" style="font-weight: bold;">Resumen de Precios Calculados</p></div>
                            <div class="col-md-6">
                                <div class="price-summary-box">
                                    <h5 class="text-center">Resumen en Dólares ($)</h5>
                                    <div class="form-group"><label>Costo Base ($)</label><input type="text" class="form-control input-sm" id="resumenCostoUsd_nuevo" readonly></div>
                                    <div class="form-group"><label>Precio Venta sin IVA ($)</label><input type="text" class="form-control input-sm" id="resumenVentaUsd_nuevo" readonly></div>
                                    <div class="form-group"><label>Monto IVA ($)</label><input type="text" class="form-control input-sm" id="resumenIvaUsd_nuevo" readonly></div>
                                    <div class="form-group"><label class="final-price-label">PVP Final ($)</label><input type="text" class="form-control input-sm final-price-input" id="resumenPvpUsd_nuevo" readonly></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="price-summary-box">
                                    <h5 class="text-center">Resumen en Bolívares (Bs.)</h5>
                                    <div class="form-group"><label>Costo Base (Bs.)</label><input type="text" class="form-control input-sm" id="resumenCostoBs_nuevo" readonly></div>
                                    <div class="form-group"><label>Precio Venta sin IVA (Bs.)</label><input type="text" class="form-control input-sm" id="resumenVentaBs_nuevo" readonly></div>
                                    <div class="form-group"><label>Monto IVA (Bs.)</label><input type="text" class="form-control input-sm" id="resumenIvaBs_nuevo" readonly></div>
                                    <div class="form-group"><label class="final-price-label">PVP Final (Bs.)</label><input type="text" class="form-control input-sm final-price-input" id="resumenPvpBs_nuevo" readonly></div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span style="color: red;">( * ) Campo obligatorio</span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Producto</button>
                </div>
                <?php $crearProducto = new ControllerProducts(); $crearProducto->ctrCrearProducto(); ?>
            </form>
        </div>
    </div>
</div>

<!--================================================
    MODAL EDITAR PRODUCTO
=================================================-->
<div id="modalEditarProducto" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post" enctype="multipart/form-data" id="formEditarProducto">
                <input type="hidden" id="idProducto" name="idProducto">
                <input type="hidden" id="imagenActual" name="imagenActual">
                <!-- CORRECCIÓN CLAVE: Aseguramos que el 'name' sea el que el Controller espera -->
                <input type="hidden" name="monedaEntrada" id="monedaEntrada_editar">

                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Editar producto</h4>
                </div>

                <div class="modal-body">
                    <div class="box-body">
                        <!-- Información Básica -->
                        <div class="row">
                            <div class="form-group col-lg-4 text-center">
                                <img src="view/img/products/default/anonymous.png" class="img-thumbnail previsualizar" width="150px">
                                <input type="file" class="imagenProducto" name="editarImagen" style="margin-top: 15px;">
                                <p class="help-block">Subir nueva foto si desea cambiarla</p>
                            </div>
                            <div class="col-lg-8"><div class="row">
                                <div class="form-group col-lg-6"><label><span style="color: red;">*</span> Código:</label><input type="text" class="form-control" id="editarCodigo" name="editarCodigo" readonly></div>
                                <div class="form-group col-lg-6"><label><span style="color: red;">*</span> Nombre:</label><input type="text" class="form-control" id="editarDescripcion" name="editarDescripcion" required></div>
                                <!-- AJUSTE: El select de categoría ahora es más simple y se llenará con JS -->
                                <div class="form-group col-lg-12"><label><span style="color: red;">*</span> Categoría:</label>
                                    <select class="form-control" id="editarCategoria" name="editarCategoria" required>
                                        <?php foreach ($categorias as $value) { echo '<option value="' . $value["id_categoria"] . '">' . $value["categoria"] . '</option>'; } ?>
                                    </select>
                                </div>
                                <div class="form-group col-lg-4"><label><span style="color: red;">*</span> Marca:</label><select class="form-control select2" id="editarMarca" name="editarMarca" style="width: 100%;" required><?php foreach ($marcas as $value) { echo '<option value="' . $value["marca"] . '">' . $value["marca"] . '</option>'; } ?></select></div>
                                <div class="form-group col-lg-4"><label><span style="color: red;">*</span> Modelo:</label><select class="form-control select2" id="editarModelo" name="editarModelo" style="width: 100%;" required><?php foreach ($modelos as $value) { echo '<option value="' . $value["modelo"] . '">' . $value["modelo"] . '</option>'; } ?></select></div>
                                <div class="form-group col-lg-4"><label>Año:</label><input type="number" min="1900" class="form-control" id="editarAno" name="editarAno"></div>
                                <div class="form-group col-lg-4"><label><span style="color: red;">*</span> Stock:</label><input type="number" class="form-control" id="editarStock" name="editarStock" min="0" required></div>
                                <div class="form-group col-lg-8"><label>Ubicación:</label><input type="text" class="form-control" id="editarUbicacion" name="editarUbicacion"></div>
                            </div></div>
                        </div>
                        <hr>
                        <!-- Estructura de Precios (Sin cambios en el HTML) -->
                        <h4>Estructura de Precios (Tasa BCV: <?php echo number_format($tasaBCV, 2, ',', '.'); ?>)</h4>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-4"><label>Moneda de Costo:</label><div class="btn-group" style="width: 100%;"><button type="button" class="btn btn-flat btn-moneda-entrada" data-moneda="USD" style="width: 50%;">$ Dólares</button><button type="button" class="btn btn-flat btn-moneda-entrada" data-moneda="VES" style="width: 50%;">Bs. Bolívares</button></div></div>
                            <div class="form-group col-md-3"><label id="label-costo-editar" for="editarPrecioCosto"><span style="color: red;">*</span> Costo (Sin IVA)</label><input type="text" class="form-control" id="editarPrecioCosto" name="editarPrecioCosto" required></div>
                            <div class="form-group col-md-2"><label for="editarPorcentajeGanancia"><span style="color: red;">*</span> % Utilidad</label><input type="text" class="form-control" id="editarPorcentajeGanancia" name="editarPorcentajeGanancia" required></div>
                            <div class="form-group col-md-3"><label for="editarTipoIva"><span style="color: red;">*</span> Condición de IVA</label><select class="form-control" id="editarTipoIva" name="editarTipoIva" required><option value="gravado" data-tasa="<?php echo $ivaPorcentaje; ?>">Grava IVA (<?php echo $ivaPorcentaje; ?>%)</option><option value="exento" data-tasa="0">Exento de IVA</option></select></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12"><hr><p class="text-center" style="font-weight: bold;">Resumen de Precios Calculados</p></div>
                            <div class="col-md-6"><div class="price-summary-box"><h5 class="text-center">Resumen en Dólares ($)</h5><div class="form-group"><label>Costo Base ($)</label><input type="text" class="form-control input-sm" id="resumenCostoUsd_editar" readonly></div><div class="form-group"><label>Precio Venta sin IVA ($)</label><input type="text" class="form-control input-sm" id="resumenVentaUsd_editar" readonly></div><div class="form-group"><label>Monto IVA ($)</label><input type="text" class="form-control input-sm" id="resumenIvaUsd_editar" readonly></div><div class="form-group"><label class="final-price-label">PVP Final ($)</label><input type="text" class="form-control input-sm final-price-input" id="resumenPvpUsd_editar" readonly></div></div></div>
                            <div class="col-md-6"><div class="price-summary-box"><h5 class="text-center">Resumen en Bolívares (Bs.)</h5><div class="form-group"><label>Costo Base (Bs.)</label><input type="text" class="form-control input-sm" id="resumenCostoBs_editar" readonly></div><div class="form-group"><label>Precio Venta sin IVA (Bs.)</label><input type="text" class="form-control input-sm" id="resumenVentaBs_editar" readonly></div><div class="form-group"><label>Monto IVA (Bs.)</label><input type="text" class="form-control input-sm" id="resumenIvaBs_editar" readonly></div><div class="form-group"><label class="final-price-label">PVP Final (Bs.)</label><input type="text" class="form-control input-sm final-price-input" id="resumenPvpBs_editar" readonly></div></div></div>
                        </div>
                        <hr>
                        <span style="color: red;">( * ) Campo obligatorio</span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
                <?php $editarProducto = new ControllerProducts(); $editarProducto->ctrEditarProducto(); ?>
            </form>
        </div>
    </div>
</div>
<?php $eliminarProducto = new ControllerProducts(); $eliminarProducto->ctrEliminarProducto(); ?>