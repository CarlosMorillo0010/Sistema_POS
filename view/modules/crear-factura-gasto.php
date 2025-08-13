<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Factura de Gastos
        </h1>
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Factura de gastos</h1></li>
        </ol>

    </section>
    <section class="content">
        <form class="formularioFacturaGastos" role="form" method="post">
            <div class="box" style="border-top-color: #3c8dbc;">
                <div class="box-header with-border" style="text-align: end;display: flex;align-items: center;">
                    <h4>Número Control</h1></h4>
                    <?php
                    $item = null;
                    $valor = null;
                    $facturaGastos = ControllerFacturasGastos::ctrMostrarFacturasGastos($item, $valor);
                    if (!$facturaGastos) {
                        $length = 10;
                        $string = "1";
                        echo '
                          <input type="text" style="color: red;font-weight: bold;font-size: 18px;width: 150px;background-color: transparent;height: 0px;border: 0;" class="form-control input-lg" name="nuevoCodigo" id="nuevoCodigo" readonly="readonly" value="' . str_pad($string, $length, 0, STR_PAD_LEFT) . '">';
                    } else {
                        foreach ($facturaGastos as $key => $value) {

                        }
                        $codigo = $value["codigo"] + 1;
                        echo '<input type="text" style="color: red;font-weight: bold;font-size: 18px;width: 150px;background-color: transparent;height: 0px;border: 0;" class="form-control input-lg" name="nuevoCodigo" id="nuevoCodigo" readonly="readonly" value="' . $codigo . '">';
                    }
                    ?>
                </div>
                <div class="box-body">
                    <div class="col-lg-12">
                        <div class="form-group" style="display: flex;">
                            <div class="col-lg-3 input-group" style="margin: 0 10px 0 0;">
                                <label> <small style="color: #000;">Vendedor:</small>
                                    <input type="text" class="form-control" readonly="readonly"
                                           value="<?php echo $_SESSION['nombre']; ?>">
                                </label>
                                <input type="hidden" name="idVendedor"
                                       value="<?php echo $_SESSION['id_usuario']; ?>">
                            </div>
                            <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                <label> <small style="color: #000;">Fecha de Emisión:</small></label>
                                <input type="date" class="form-control" readonly="readonly"
                                       value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-lg-2 input-group" style="margin: 0 10px 0 0;">
                                <label> <small style="color: #000;">Fecha de Registro:</small></label>
                                <input type="date" class="form-control"
                                       value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-lg-2 input-group" style="margin: 0 10px 0 0;">
                                <label><small style="color: #000;">Nº Factura:</small></label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-lg-2 input-group" style="margin: 0 10px 0 0;">
                                <label><small style="color: #000;">Nº Control:</small></label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="input-group" style="margin: 0 10px 0 0;">
                                <label></label>
                                <button type="button" class="btn btn-primary btnFacturaGastos" style="width: 100%;">
                                    Agregar proveedor
                                </button>
                            </div>
                            <div class="input-group">
                                <label></label>
                                <button type="button" class="btn btn-default " data-toggle="modal"
                                        data-target="#modalAgregarProveedor" data-dismiss="modal">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group nuevaFacturaGastos" style="display: flex;">

                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="box-body" style="height: 230px; overflow: auto;">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th style="width: 5%"></th>
                                        <th style="width: 10%">Codigo</th>
                                        <th style="width: 25%">Nombre producto</th>
                                        <th style="width: 10%">Unidades</th>
                                        <th style="width: 10%">Precio</th>
                                        <th style="width: 10%">I.V.A</th>
                                        <th style="width: 10%">Cantidad</th>
                                        <th style="width: 10%">Precio Total</th>
                                    </tr>
                                    </thead>
                                    <tbody class="nuevoProductoFacturaGastos">

                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-default btnAgregarProductos_FacturaGastos"
                                        style="margin: 10px 0 0 0;">
                                    Agregar Descripción
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12" style="width: 100%;">
                        <div>
                            <table class="table" border="0" style="margin-bottom: 0 !important;">
                                <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="width: 15%;">
                                        <input type="text" class="form-control" value="TOTAL A PAGAR"
                                               style="font-weight: bold;background-color: transparent;border: 0;"
                                               required readonly>
                                    </td>
                                    <td style="width: 15%;">
                                        <input type="text" class="form-control" name="subTotalFacturaCompra" id="subTotalFacturaCompra"
                                               value="0.00" required readonly
                                               style="font-size: 30px;font-family: 'ds-digital'; color: #444;width: 100%;">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary">Crear Factura de Gastos</button>
                </div>
            </div>
        </form>
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
                        <div class="form-group col-lg-3 izquierda">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Código:</small>
                                    <?php
                                    $item = null;
                                    $valor = null;
                                    $proveedores = ControllerProveedores::ctrMostrarProveedores($item, $valor);

                                    if (!$proveedores) {
                                        echo '
                                                <input type="text" class="form-control input-lg" name="nuevoCodigo" id="nuevoCodigo" readonly="readonly" value="' . mt_rand(10000000, 99999999) . '">
                                            ';
                                    } else {
                                        foreach ($proveedores as $key => $value) {

                                        }
                                        $codigo = $value["codigo"] + 1;
                                        echo '<input type="text" class="form-control input-lg" name="nuevoCodigo" id="nuevoCodigo" readonly="readonly" value="' . $codigo . '">';
                                    }
                                    ?>
                                </label>
                            </div>
                        </div>
                        <!-- ENTRADA PARA EL TIPO DE EMPRESA-->
                        <div class="form-group col-lg-4 izquierda">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Tipo de Persona:</small>
                                    <select class="form-control input-lg" name="tipoPersona" id="tipoPersona">
                                        <option value="-">Tipo de Persona</option>
                                        <option value="Natural">Natural</option>
                                        <option value="Juridica">Juridica</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <!-- Tipo de Documento -->
                        <div class="form-group col-lg-2 izquierda">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Documento:</small>
                                    <select class="form-control input-lg" name="tipoDocumento"
                                            id="tipoDocumento">
                                        <option value="-">-</option>
                                        <option value="CI">CI</option>
                                        <option value="RIF">RIF</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <!-- ENTRADA PARA EL TIPO DE DOCUMENTO-->
                        <div class="form-group col-lg-3 izquierda">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Numero Documento:</small>
                                    <input type="text" class="form-control input-lg" placeholder="Nº Documento"
                                           name="numeroDocumento" id="numeroDocumento" required>
                                </label>
                            </div>
                        </div>
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group col-lg-6 izquierda">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Nombre o Razón Social:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoProveedor"
                                           id="nuevoProveedor"
                                           placeholder="Ingresar Nombre o Razón Social" required>
                                </label>
                            </div>
                        </div>
                        <!-- ENTRADA PARA EL TIPO DE PROVEEDOR -->
                        <div class="form-group col-lg-3 izquierda">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Tipo de Proveedor:</small>
                                    <select class="form-control input-lg" name="tipoProveedor" id="tipoProveedor">
                                        <option value="-">Proveedor</option>
                                        <option value="Nacional">Nacional</option>
                                        <option value="Internacional">Internacional</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <!-- ENTRADA PARA EL TELEFONO -->
                        <div class="form-group col-lg-3 izquierda">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Teléfono:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoTelefono"
                                           id="nuevoTelefono"
                                           placeholder="Ingresar teléfono" data-inputmask="'mask':'(9999) 999-99-99'"
                                           data-mask required>
                                </label>
                            </div>
                        </div>
                        <!-- ENTRADA PARA EL DIRECION -->
                        <div class="form-group col-lg-12 izquierda">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Dirección:</small>
                                    <input type="text" class="form-control input-lg" name="nuevaDireccion"
                                           id="nuevaDireccion"
                                           placeholder="Ingresar Dirección" required>
                                </label>
                            </div>
                        </div>
                        <!-- ENTRADA PARA PAISES-->
                        <div class="form-group col-lg-3 izquierda">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Pais:</small>
                                    <select class="form-control input-lg" name="nuevoPais" id="nuevoPais" required>
                                        <option value="-">Elija un pais</option>
                                        <?php
                                        $item = null;
                                        $valor = null;

                                        $paises = ControllerPaises::ctrMostrarPaises($item, $valor);
                                        foreach ($paises as $key => $value) {
                                            echo '<option value="' . $value["pais"] . '">' . $value["pais"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <!-- ENTRADA PARA ESTADOS-->
                        <div class="form-group col-lg-3 izquierda">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Estados:</small>
                                    <select class="form-control input-lg" name="nuevoEstado" id="nuevoEstado" required>
                                        <option value="-">Elija un estado</option>
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
                        <!-- ENTRADA PARA CIUDAD-->
                        <div class="form-group col-lg-3 izquierda">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Ciudad:</small>
                                    <select class="form-control input-lg" name="nuevaCiudad" id="nuevaCiudad" required>
                                        <option value="-">Elija una ciudad</option>
                                        <?php
                                        $item = null;
                                        $valor = null;

                                        $paises = ControllerPaises::ctrMostrarCiudades($item, $valor);
                                        foreach ($paises as $key => $value) {
                                            echo '<option value="' . $value["ciudad"] . '">' . $value["ciudad"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <!-- ENTRADA PARA MUNICIPIO-->
                        <div class="form-group col-lg-3 izquierda">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Municipio:</small>
                                    <select class="form-control input-lg" name="nuevoMunicipio" id="nuevoMunicipio"
                                            required>
                                        <option value="-">Elija un municipio</option>
                                        <?php
                                        $item = null;
                                        $valor = null;

                                        $paises = ControllerPaises::ctrMostrarMunicipios($item, $valor);
                                        foreach ($paises as $key => $value) {
                                            echo '<option value="' . $value["municipio"] . '">' . $value["municipio"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-6 izquierda">
                            <!-- ENTRADA PARA LA RATENCION -->
                            <div class="form-group">
                                <div class="input-group">
                                    <label style="color: red;"> * <small style="color: #000;">Retencion:</small></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="porcentajeRetencion" id="porcentajeUno" value="75"
                                                   checked required>Reteción 75%
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="porcentajeRetencion" id="porcentajeDos"
                                                   value="100" required>Reteción 100%
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 izquierda">
                            <div class="form-group">
                                <div class="input-group">
                                    <label style="color: red;"> * <small
                                                style="color: #000;">Clasificación:</small></label>
                                    <p class="clasificacion">
                                        <input id="radio1" type="radio" name="estrellas" value="5">
                                        <label for="radio1">★</label>
                                        <input id="radio2" type="radio" name="estrellas" value="4">
                                        <label for="radio2">★</label>
                                        <input id="radio3" type="radio" name="estrellas" value="3">
                                        <label for="radio3">★</label>
                                        <input id="radio4" type="radio" name="estrellas" value="2">
                                        <label for="radio4">★</label>
                                        <input id="radio5" type="radio" name="estrellas" value="1">
                                        <label for="radio5">★</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 izquierda">
                            <!-- ENTRADA PARA NOTA -->
                            <div class="form-group">
                                <div class="input-group">
                                    <label style="color: red;"> * <small style="color: #000;">Nota:</small>
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