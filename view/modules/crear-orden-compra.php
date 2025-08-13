<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Orden de Compra
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Orden de compra</li>
        </ol>

    </section>
    <section class="content">
        <form class="formulario__OrdenCompra" role="form" method="post">
            <div class="box" style="border-top-color: #3c8dbc;">
                <div class="box-header with-border">
                    <div class="col lg-12">
                        <div class="row">
                            <div class="col-lg-10" style="display: flex;align-items: center;">
                            <h4 style="text-transform: uppercase;">Nº de Orden</h4>
                                <!---------------------------------------
                                CODIGO DE ORDEN DE COMPRA
                                ---------------------------------------->
                                <?php
                                $item = null;
                                $valor = null;
                                $ordenCompra = ControllerOrdenesCompras::ctrMostrarOrdenCompra($item, $valor);

                                if (!$ordenCompra) {
                                    $length = 10;
                                    $string = "1";
                                    echo '
                                    <input type="text" style="color: red;font-weight: bold;font-size: 20px;width: 150px;background-color: transparent;height: 0px;border: 0;" class="form-control input-lg" name="nuevoCodigo__OrdenCompra" id="nuevoCodigo__OrdenCompra" readonly="readonly" value="' . str_pad($string, $length, 0, STR_PAD_LEFT) . '">';
                                } else {
                                    foreach ($ordenCompra as $key => $value) {

                                    }
                                    $length = 10;
                                    $codigo = str_pad($value["codigo"] + 1, $length, 0, STR_PAD_LEFT) ;
                                    echo '<input type="text" style="color: red;font-weight: bold;font-size: 20px;width: 150px;background-color: transparent;height: 0px;border: 0;" class="form-control input-lg" name="nuevoCodigo__OrdenCompra" id="nuevoCodigo__OrdenCompra" readonly="readonly" value="' . $codigo . '">';
                                }
                                ?>
                            </div>
                            <div class="col-lg-2">
                                <!---------------------------------------
                                ENTRADA DE LA FECHA EMISION
                                ---------------------------------------->
                                <input name="fechaEmision__OrdenCompra" id="fechaEmision__OrdenCompra" type="date" class="form-control" readonly="readonly"
                                    value="<?php date_default_timezone_set('America/Caracas'); echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 form-group" style="padding-left:0;padding-right:0;">
                                <div class="agregarProveedores" style="display:flex;">
                                    <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Letra:</small></label>
                                    <input type="text" class="form-control nuevoTipoDocumento_OrdenCompra" id="nuevoTipoDocumento_OrdenCompra" name="nuevoTipoDocumento_OrdenCompra" required readonly>
                                    </div>
                                    <div class="col-lg-4 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Documento:</small></label>
                                    <input type="text" class="form-control nuevoDocumento_OrdenCompra" id="nuevoDocumento_OrdenCompra" name="nuevoDocumento_OrdenCompra" required readonly>
                                    </div>
                                    <div class="col-lg-7 input-group">
                                    <label><small style="color: #000;">Proveedor:</small></label>
                                    <input class="form-control nuevo-ProveedorOrdenCompra" idProveedor name="nuevo-ProveedorOrdenCompra" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group" style="display: flex;justify-content:end;">
                                    <!---------------------------------------
                                    ENTRADA AGREGAR PROVEEDOR
                                    ---------------------------------------->
                                    <div class="input-group" style="margin: 0 10px 0 0;">
                                        <label></label>
                                        <button type="button" class="btn btn-primary btnBuscarProveedores" data-toggle="modal" data-target="#modalBuscarProveedores">Buscar Proveedores</button>
                                    </div>
                                    <div class="input-group">
                                        <label></label>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#modalAgregarProveedor">
                                            Agregar Proveedor
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="listaProveedor" name="listaProveedor">
                        <input type="hidden" id="proveedorId" name="proveedorId">
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="box-body" style="height: 230px; overflow: auto;">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th style="width: 5%"></th>
                                        <th style="width: 5%"></th>
                                        <th style="width: 15%">Codigo</th>
                                        <th style="width: 40%">Nombre producto</th>
                                        <th style="width: 20%">Unidad</th>
                                        <th style="width: 10%">Cantidad</th>
                                    </tr>
                                    </thead>
                                    <tbody class="nuevaOrdenCompra">

                                    </tbody>
                                </table>
                                <input type="hidden" id="listarProductos_OrdenCompra" name="listarProductos_OrdenCompra">
                                <button type="button" class="btn btn-default btnAgregarOrdenCompra"
                                        style="margin: 10px 0 0 0;">
                                    Agregar productos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary">Crear Orden de Compra</button>
                </div>
            </div>
        </form>
        <?php
        $crearOrdenCompra = new ControllerOrdenesCompras();
        $crearOrdenCompra->ctrCrearOrdenCompra();
        ?>
    </section>
</div>

<!--=====================================
MODAL AGREGAR PROVEEDOR
======================================-->
<div id="modalAgregarProveedor" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post">
                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar proveedor</h4>
                </div>
                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">

                        <!-- ENTRADA PARA EL CODIGO-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Código:</small>
                                    <?php
                                        $item = null;
                                        $valor = null;
                                        $proveedores = ControllerProveedores::ctrMostrarProveedores($item, $valor);

                                        if (!$proveedores) {
                                            $length = 10;
                                            $string = "1";
                                            echo '
                                                    <input type="text" class="form-control input-lg" name="nuevoCodigo" id="nuevoCodigo" readonly="readonly" value="' . str_pad($string, $length, 0, STR_PAD_LEFT) . '">
                                                ';
                                        } else {
                                            foreach ($proveedores as $key => $value) {

                                            }
                                            $length = 10;
                                            $codigo = str_pad($value["codigo"] + 1, $length, 0, STR_PAD_LEFT) ;
                                            echo '
                                                <input type="text" class="form-control input-lg" name="nuevoCodigo" id="nuevoCodigo" readonly="readonly" value="' . $codigo . '">
                                            ';
                                        }
                                    ?>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL TIPO DE EMPRESA-->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Tipo de Persona:</small>
                                    <select class="form-control input-lg" name="tipoPersona" id="tipoPersona">
                                        <option value="">Tipo de Persona</option>
                                        <option value="NATURAL">Natural</option>
                                        <option value="JURIDICA">Juridica</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- Tipo de Documento -->
                        <div class="form-group col-lg-2">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Letra:</small>
                                    <select class="form-control input-lg" name="tipoDocumento"
                                            id="tipoDocumento">
                                        <option value=""></option>
                                        <option value="V">V</option>
                                        <option value="J">J</option>
                                        <option value="G">G</option>
                                        <option value="C">C</option>
                                        <option value="E">E</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL TIPO DE DOCUMENTO-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Documento:</small>
                                    <input type="text" class="form-control input-lg" placeholder="Documento"
                                           name="numeroDocumento" id="numeroDocumento" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group col-lg-5">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Nombre o Razón Social:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoProveedor"
                                           id="nuevoProveedor"
                                           placeholder="Ingresar Nombre o Razón Social" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL TIPO DE PROVEEDOR -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Tipo de Proveedor:</small>
                                    <select class="form-control input-lg" name="tipoProveedor" id="tipoProveedor">
                                        <option value="">Proveedor</option>
                                        <option value="Nacional">Nacional</option>
                                        <option value="Internacional">Internacional</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL TELEFONO -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label><small style="color: #000;">Teléfono:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoTelefono"
                                        placeholder="Teléfono" data-inputmask="'mask':'(9999) 999-99-99'"
                                        data-mask required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA PAISES-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Pais:</small>
                                    <select class="form-control select2" name="nuevoPais" id="nuevoPais"
                                            style="width: 100%;" required>
                                        <option value="">Elija un pais</option>
                                        <option value="Venezuela">Venezuela</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA ESTADOS-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Estados:</small>
                                    <select class="form-control select2" name="nuevoEstado" id="nuevoEstado"
                                            style="width: 100%;" required>
                                        <option value="">Elija un estado</option>
                                        <?php
                                        $item = null;
                                        $valor = null;

                                        $paises = ControllerPaises::ctrMostrarEstados($item, $valor);
                                        foreach ($paises as $key => $value) {
                                            echo '<option value="' . $value["estado"] . '">' . $value["estado"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </label>
                            </div>
                        </div>
                        
                        <!-- ENTRADA PARA EL DIRECION -->
                        <div class="form-group col-lg-12">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Dirección:</small>
                                    <input type="text" class="form-control input-lg" name="nuevaDireccion"
                                           id="nuevaDireccion"
                                           placeholder="Ingresar Dirección" required>
                                </label>
                            </div>
                        </div>
                        
                        <!-- ENTRADA PARA LA RETENCION -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Retención:</small>
                                    <select class="form-control input-lg" name="porcentajeRetencion" id="porcentajeRetencion">
                                        <option value=""></option>
                                        <option value="Exonerado">Exonerado</option>
                                        <option value="75%">Retención 75%</option>
                                        <option value="100%">Retención 100%</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA CALIFICACION -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <label><small style="color: #000;">Clasificación:</small></label>
                                    <p class="clasificacion">
                                        <input id="radio1" type="radio" id="estrellasA" name="estrellas" value="5">
                                        <label for="radio1">★</label>
                                        <input id="radio2" type="radio" id="estrellasB" name="estrellas" value="4">
                                        <label for="radio2">★</label>
                                        <input id="radio3" type="radio" id="estrellasC" name="estrellas" value="3">
                                        <label for="radio3">★</label>
                                        <input id="radio4" type="radio" id="estrellasD" name="estrellas" value="2">
                                        <label for="radio4">★</label>
                                        <input id="radio5" type="radio" id="estrellasE" name="estrellas" value="1">
                                        <label for="radio5">★</label>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- ENTRADA PARA NOTA -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <label><small style="color: #000;">Nota:</small>
                                        <textarea type="text" class="form-control input-lg" name="nuevaNota"
                                                  id="nuevaNota"
                                                  placeholder="Ingresar nota" style="resize: none;"></textarea>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!--=====================================
                PIE DEL MODAL
                ======================================-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
            <?php
            $crearProveedor = new ControllerProveedores();
            $crearProveedor->ctrCrearProveedor();
            ?>
        </div>
    </div>
</div>

<!--=====================================
MODAL BUSCAR PROVEEDORES
======================================-->
<div id="modalBuscarProveedores" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!--=====================================
            CABEZA DEL MODAL
            ======================================-->
            <div class="modal-header" style="background:#3c8dbc; color:white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Proveedores</h4>
            </div>
            <!--=====================================
            CUERPO DEL MODAL
            ======================================-->
            <div class="modal-body">
                <div class="box-body">
                    <table class="table table-bordered table-striped tablas">
                        <thead>
                        <tr>
                            <th style="width:10px">#</th>
                            <th>Letra</th>
                            <th>Documento</th>
                            <th>Nombreo Razón Social</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $item = null;
                        $valor = null;
                        $proveedores = ControllerProveedores::ctrMostrarProveedores($item, $valor);

                        foreach ($proveedores as $key => $value) {
                            echo '<tr>
                                    <td>' . ($key + 1) . '</td>
                                    <td>' . $value["tipo_documento"] . '</td>
                                    <td>' . $value["documento"] . ' </td>
                                    <td>' . $value["nombre"] . '</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-primary btnTraerProveedor" idProveedor="' . $value["id_proveedor"] . '"><i class="fa fa-arrow-down"></i></button>
                                        </div>  
                                    </td>
                                </tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--=====================================
MODAL AGREGAR PRODUCTOS
======================================-->
<div id="modalAgregarProducto" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!--=====================================
            CABEZA DEL MODAL
            ======================================-->
            <div class="modal-header" style="background:#3c8dbc; color:white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregar Productos</h4>
            </div>
            <!--=====================================
            CUERPO DEL MODAL
            ======================================-->
            <div class="modal-body">
                <div class="box-body">
                    <table class="table table-bordered table-striped tablas">
                        <thead>
                        <tr>
                            <th style="width:10px">#</th>
                            <th>Codigo</th>
                            <th>Marca</th>
                            <th>Nombre producto</th>
                            
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $item = null;
                        $valor = null;
                        $orden = "codigo";
                        $proveedores = ControllerProducts::ctrMostrarProductos($item, $valor, $orden);

                        foreach ($proveedores as $key => $value) {
                            //var_dump($value);
                            echo '<tr>
                                    <td>' . ($key + 1) . '</td>
                                    <td>' . $value["codigo"] . '</td>
                                    <td>' . $value["marca"] . ' </td>
                                    <td>' . $value["descripcion"] . '</td>
                                    
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-primary btnTraerProducto" idProducto="' . $value["id_producto"] . '"><i class="fa fa-arrow-down"></i></button>
                                        </div>  
                                    </td>
                                </tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>