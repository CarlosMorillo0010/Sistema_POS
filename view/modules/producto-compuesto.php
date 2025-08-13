<?php
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value):
    if ($_SESSION["perfil"] == $value["perfil"] && $value["inventario"] == "N"){
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
            Administrar Producto Compuesto
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar producto compuesto</li>
        </ol>

    </section>
    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProductoCompuesto">
                    Agregar producto compuesto
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablaCompuestos">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Imagen</th>
                        <th>Código</th>
                        <th>Servicio</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                        <th>Precio Unit</th>
                        <th>Precio Oferta</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>

<!--=====================================
MODAL AGREGAR PRODUCTOS COMPUESTOS
======================================-->
<div id="modalAgregarProductoCompuesto" class="modal fade" role="dialog" style="padding-left: 0;">
    <div class="modal-dialog modal-lg" style="width: 95%;">
        <div class="modal-content">
            <form role="form" method="post" enctype="multipart/form-data" class="formularioCompuestos">
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Agregar Producto</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-lg-12" style="padding-top: 15px;">
                            <div class="row">
                                <div class="col-lg-4">
                                    <!-- Entrada para Subir Imagen -->
                                    <div class="form-group col-lg-12">
                                        <img src="view/img/products-compuests/default/anonymous.png"
                                             class="img-thumbnail previsualizar"
                                             width="100%">
                                        <div>Subir imagen</div>
                                        <input type="file" class="imagenCompuesto" name="imagenCompuesto">
                                        <p class="help-block">Peso máximo de la imgen 2MB</p>
                                    </div>
                                    <!-- ENTRADA PRECIO UNITARIO -->
                                    <div class="col-lg-12 form-group" style="padding: 0;">
                                        <!-- Entrada para Precio Unitario -->
                                        <div class="form-group col-lg-12">
                                            <div class="input-group">
                                                <label style="color: red;"> * <small style="color: #000;">Precio
                                                        Unit:</small>
                                                    <input type="text" class="form-control input-lg"
                                                           id="nuevoPrecioUnitario"
                                                           name="nuevoPrecioUnitario" min="0" placeholder="0,00"
                                                           required
                                                           style="font-size: 30px;text-align: right;font-family: 'ds-digital'; color: #444;">
                                                </label>
                                            </div>
                                        </div>
                                        <!-- CHECK PARA PORCENTAJE -->
                                        <div class="form-group col-lg-4" style="margin-bottom: 0 !important;padding-right: 0">
                                            <div class="input-group">
                                                <small style="color: #000;">IVA Venta:</small>
                                                <input type="text" class="form-control input-lg nuevoPorcentaje" min="0"
                                                    <?php
                                                    $item = null;
                                                    $valor = null;
                                                    $impuestos = ControllerImpuestos::ctrMostrarImpuesto($item, $valor);

                                                    foreach ($impuestos as $key => $value) {
                                                        break;
                                                    }
                                                    ?>
                                                       value="<?php echo $value["porcentaje"] ?>" required
                                                       style="font-size: 30px;text-align: right;font-family: 'ds-digital'; color: #444;">
                                            </div>

                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" class="minimal porcentaje" checked>
                                                    <small>Utilizar procentaje</small>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- Entrada para Precio Venta -->
                                        <div class="form-group col-lg-8">
                                            <div class="input-group">
                                                <label style="color: red;"> * <small style="color: #000;">Precio Venta
                                                        Unit:</small>
                                                    <input type="text" class="form-control input-lg"
                                                           id="nuevoPrecioVenta"
                                                           name="nuevoPrecioVenta" min="0" placeholder="0,00"
                                                           required
                                                           style="font-size: 30px;text-align: right;font-family: 'ds-digital'; color: #444;">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <!-- Entrada de Categoria -->
                                    <div class="form-group col-lg-4">
                                        <div class="input-group">
                                            <label style="color: red;"> * <small style="color: #000;">Categoria:</small>
                                                <select class="form-control input-lg" id="nuevaCategoria"
                                                        name="nuevaCategoria"
                                                        required>
                                                    <option value="0">Elija una categoria</option>
                                                    <?php
                                                    $item = null;
                                                    $valor = null;
                                                    $categorias = ControllerCategories::ctrMostrarCategoria($item, $valor);

                                                    foreach ($categorias as $key => $value) {
                                                        echo '<option value="' . $value["id_categoria"] . '">' . $value["categoria"] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Codigo del Producto -->
                                    <div class="form-group col-lg-4">
                                        <div class="input-group">
                                            <label> <small style="color: #000;">Codigo:</small>
                                                <input type="text" class="form-control input-lg" id="nuevoCodigo"
                                                       name="nuevoCodigo" placeholder="Código" readonly required>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada para la Servicio -->
                                    <div class="form-group col-lg-4">
                                        <div class="form-group">
                                            <label style="color: red;" class="control-label"> * <small
                                                        style="color: #000;">Servicio:</small>
                                                <select class="form-control input-lg" id="nuevoServicio"
                                                        name="nuevoServicio"
                                                        required>
                                                    <option value="0">Elija un servicio</option>
                                                    <?php
                                                    $item = null;
                                                    $valor = null;
                                                    $servicios = ControllerServices::ctrMostrarServicio($item, $valor);

                                                    foreach ($servicios as $key => $value) {
                                                        echo '<option value="' . $value["servicio"] . '">' . $value["servicio"] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada para Descripción -->
                                    <div class="form-group col-lg-4">
                                        <div class="input-group">
                                            <label style="color: red;"> * <small style="color: #000;">Nombre del
                                                    Producto:</small>
                                                <input type="text" class="form-control input-lg" id="nuevaDescripcion"
                                                       name="nuevaDescripcion"
                                                       placeholder="Nombre del Servicio" required>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada Cantidad en Inventario -->
                                    <div class="form-group col-lg-4">
                                        <div class="input-group">
                                            <label style="color: red;"> * <small style="color: #000;">Elija una
                                                    cantidad:</small>
                                                <input type="text" class="form-control input-lg" name="nuevoStock"
                                                       min="0"
                                                       value="0" required
                                                       style="font-size: 30px;text-align: right;font-family: 'ds-digital'; color: #444;">
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada Unidad -->
                                    <div class="form-group col-lg-4">
                                        <div class="input-group">
                                            <label style="color: red;"> * <small style="color: #000;">Unidad:</small>
                                                <select class="form-control input-lg" id="nuevaUnidad"
                                                        name="nuevaUnidad"
                                                        required>
                                                    <option value="0">Elija una unidad</option>
                                                    <?php
                                                    $item = null;
                                                    $valor = null;
                                                    $unidad = ControllerUnidades::ctrMostrarUnidad($item, $valor);

                                                    foreach ($unidad as $key => $value) {
                                                        echo '<option value="' . $value["unidad"] . '">' . $value["unidad"] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- ENTRADA PRECIO OFERTA -->
                                    <div class="col-lg-12 form-group" style="padding: 0;">
                                        <!-- Entrada para Precio Oferta -->
                                        <div class="form-group col-lg-4">
                                            <div class="input-group">
                                                <label style="color: red;"> * <small style="color: #000;">Precio
                                                        Oferta:</small>
                                                    <input type="text" class="form-control input-lg"
                                                           id="nuevoPrecioOferta"
                                                           name="nuevoPrecioOferta" min="0" placeholder="0,00"
                                                           required
                                                           style="font-size: 30px;text-align: right;font-family: 'ds-digital'; color: #444;">
                                                </label>
                                            </div>
                                        </div>
                                        <!-- CHECK PARA PORCENTAJE -->
                                        <div class="form-group col-lg-4" style="margin-bottom: 0 !important;">
                                            <div class="input-group">
                                                <small style="color: #000;">IVA Venta:</small>
                                                <input type="text" class="form-control input-lg nuevoPorcentajeOferta"
                                                       min="0"
                                                    <?php
                                                    $item = null;
                                                    $valor = null;
                                                    $impuestos = ControllerImpuestos::ctrMostrarImpuesto($item, $valor);

                                                    foreach ($impuestos as $key => $value) {
                                                        break;
                                                    }
                                                    ?>
                                                       value="<?php echo $value["porcentaje"]; ?>" required
                                                       style="font-size: 30px;text-align: right;font-family: 'ds-digital'; color: #444;">
                                            </div>

                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" class="minimal porcentajeOferta" checked>
                                                    <small>Utilizar procentaje</small>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- Entrada para Precio Venta -->
                                        <div class="form-group col-lg-4">
                                            <div class="input-group">
                                                <label style="color: red;"> * <small style="color: #000;">Precio Venta
                                                        Oferta:</small>
                                                    <input type="text" class="form-control input-lg"
                                                           id="nuevoPrecioVentaOferta"
                                                           name="nuevoPrecioVentaOferta" min="0" placeholder="0,00"
                                                           required
                                                           style="font-size: 30px;text-align: right;font-family: 'ds-digital'; color: #444;">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="col lg-12 form-group">
                                        <div class="box">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Agregar productos</h3>
                                            </div>
                                            <!-- /.box-header -->
                                            <div class="box-body" style="border-top: 0;height: 250px; overflow: auto;">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th style="width: 15%"></th>
                                                        <th style="width: 70%">Nombre del producto</th>
                                                        <th style="width: 15%">Cantidad</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="nuevoProductoCompuesto"></tbody>
                                                </table>
                                                <input type="hidden" id="listaCompuestos" name="listaCompuestos" required>
                                                <button type="button" class="btn btn-default btnAgregarCompuesto" style="margin: 10px 0 0 0;">Agregar productos</button>
                                            </div>
                                            <!-- /.box-body -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <span class="col-lg-12" style="color: red;">( * ) Campos obligatorios</span>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
            <?php
            $crearCompuesto = new ControllerCompuestos();
            $crearCompuesto->ctrCrearCompuesto();
            ?>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR PRODUCTO COMPUESTO
======================================-->
<div id="modalEditarProductoCompuesto" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" style="width: 95%;">
        <div class="modal-content">
            <form role="form" method="post" enctype="multipart/form-data" class="formularioCompuestos">
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Editar Producto</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-lg-12" style="padding-top: 15px;">
                            <div class="row">
                                <div class="col-lg-4">
                                    <!-- Entrada para Subir Imagen -->
                                    <div class="form-group col-lg-12">
                                        <img src="view/img/products-compuests/default/anonymous.png"
                                             class="img-thumbnail previsualizar"
                                             width="100%">
                                        <div>Subir imagen</div>
                                        <input type="file" class="imagenCompuesto" name="imagenCompuesto">
                                        <p class="help-block">Peso máximo de la imgen 2MB</p>
                                    </div>
                                    <!-- ENTRADA PRECIO UNITARIO -->
                                    <div class="col-lg-12 form-group" style="padding: 0;">
                                        <!-- Entrada para Precio Unitario -->
                                        <div class="form-group col-lg-12">
                                            <div class="input-group">
                                                <label><small style="color: #000;">Precio
                                                        Unit:</small>
                                                    <input type="text" class="form-control input-lg"
                                                           id="editarPrecioUnitario"
                                                           name="editarPrecioUnitario" placeholder="0,00"
                                                           required
                                                           style="font-size: 30px;text-align: right;font-family: 'ds-digital'; color: #444;">
                                                </label>
                                            </div>
                                        </div>
                                        <!-- CHECK PARA PORCENTAJE -->
                                        <div class="form-group col-lg-4" style="margin-bottom: 0 !important;padding-right: 0">
                                            <div class="input-group">
                                                <small style="color: #000;">IVA Venta:</small>
                                                <input type="text" class="form-control input-lg editarPorcentaje" required
                                                       style="font-size: 30px;text-align: right;font-family: 'ds-digital'; color: #444;">
                                            </div>

                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" class="minimal porcentaje" checked>
                                                    <small>Utilizar procentaje</small>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- Entrada para Precio Venta -->
                                        <div class="form-group col-lg-8">
                                            <div class="input-group">
                                                <label style="color: red;"> * <small style="color: #000;">Precio Venta
                                                        Unit:</small>
                                                    <input type="text" class="form-control input-lg"
                                                           id="editarPrecioVenta"
                                                           name="editarPrecioVenta" min="0" placeholder="0,00"
                                                           required
                                                           style="font-size: 30px;text-align: right;font-family: 'ds-digital'; color: #444;">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <!-- Entrada de Categoria -->
                                    <div class="form-group col-lg-4">
                                        <div class="input-group">
                                            <label> <small style="color: #000;">Categoria:</small>
                                                <select class="form-control input-lg" name="editarCategoria" readonly required>
                                                    <option id="editarCategoria"></option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Codigo del Producto -->
                                    <div class="form-group col-lg-4">
                                        <div class="input-group">
                                            <label> <small style="color: #000;">Codigo:</small>
                                                <input type="text" class="form-control input-lg" id="editarCodigo"
                                                    name="editarCodigo"
                                                    readonly required>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada para la Servicio -->
                                    <div class="form-group col-lg-4">
                                        <div class="form-group">
                                            <label> <small style="color: #000;">Servicio:</small>
                                                <select class="form-control input-lg" name="editarServicio" readonly required>
                                                    <option id="editarServicio"></option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada para Descripción -->
                                    <div class="form-group col-lg-4">
                                        <div class="input-group">
                                            <label><small style="color: #000;">Nombre del
                                                    Producto:</small>
                                                <input type="text" class="form-control input-lg" id="editarDescripcion"
                                                       name="editarDescripcion" required>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada Cantidad en Inventario -->
                                    <div class="form-group col-lg-4">
                                        <div class="input-group">
                                        <label> <small style="color: #000;">Cantidad:</small>
                                    <input type="text" class="form-control input-lg" id="editarStock"
                                           name="editarStock" min="0" required
                                           style="font-size: 30px;text-align: right;font-family: 'ds-digital'; color: #444;">
                                </label>
                                        </div>
                                    </div>
                                    <!-- Entrada Unidad -->
                                    <div class="form-group col-lg-4">
                                        <div class="input-group">
                                        <label> <small style="color: #000;">Unidad:</small>
                                    <select class="form-control input-lg" name="editarUnidad" required>
                                        <option id="editarUnidad"></option>
                                    </select>
                                </label>
                                        </div>
                                    </div>
                                    <!-- ENTRADA PRECIO OFERTA -->
                                    <div class="col-lg-12 form-group" style="padding: 0;">
                                        <!-- Entrada para Precio Oferta -->
                                        <div class="form-group col-lg-4">
                                            <div class="input-group">
                                            <label> <small style="color: #000;">Precio Oferta:</small>
                                        <input type="text" class="form-control input-lg" id="editarPrecioOferta"
                                               name="editarPrecioOferta" min="0" required
                                               style="font-size: 30px;text-align: right;font-family: 'ds-digital'; color: #444;">
                                    </label>
                                            </div>
                                        </div>
                                        <!-- CHECK PARA PORCENTAJE -->
                                        <div class="form-group col-lg-4" style="margin-bottom: 0 !important;">
                                        <div class="input-group">
                                                <small style="color: #000;">IVA Venta:</small>
                                                <input type="text" class="form-control input-lg editarPorcentajeOferta" required
                                                       style="font-size: 30px;text-align: right;font-family: 'ds-digital'; color: #444;">
                                            </div>

                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" class="minimal porcentajeOferta" checked>
                                                    <small>Utilizar procentaje</small>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- Entrada para Precio Venta -->
                                        <div class="form-group col-lg-4">
                                            <div class="input-group">
                                            <label> <small style="color: #000;">Precio Venta Oferta:</small>
                                        <input type="text" class="form-control input-lg" id="editarPrecioVentaOferta"
                                               name="editarPrecioVentaOferta" min="0" readonly required
                                               style="font-size: 30px;text-align: right;font-family: 'ds-digital'; color: #444;">
                                    </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="col lg-12 form-group">
                                        <div class="box">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Agregar productos</h3>
                                            </div>
                                            <!-- /.box-header -->
                                            <div class="box-body" style="border-top: 0;height: 250px; overflow: auto;">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th style="width: 15%"></th>
                                                        <th style="width: 70%">Nombre del producto</th>
                                                        <th style="width: 15%">Cantidad</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="nuevoProductoCompuesto"></tbody>
                                                </table>
                                                <input type="hidden" id="editarListaCompuestos" name="editarListaCompuestos" required>
                                                <button type="button" class="btn btn-default btnAgregarCompuesto" style="margin: 10px 0 0 0;">Agregar productos</button>
                                            </div>
                                            <!-- /.box-body -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <span class="col-lg-12" style="color: red;">( * ) Campos obligatorios</span>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
            
        </div>
    </div>
</div>
<?php
$eliminarProductoCompuesto = new ControllerCompuestos();
$eliminarProductoCompuesto->ctrEliminarProductoCompuesto();
?>