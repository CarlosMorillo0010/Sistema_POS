<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Pedido de Venta
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Pedido de venta</li>
        </ol>
    </section>

    <section class="content">
        <form class="formularioPedido" role="form" method="post">
            <div class="box" style="border-top-color: #3c8dbc;">
                <div class="box-header with-border" style="text-align: end;display: flex;align-items: center;">
                    <h4>Número Control</h4>
                    <?php
                    $item = null;
                    $valor = null;
                    $pedido = ControllerPedidos::ctrMostrarPedidos($item, $valor);

                    if (!$pedido) {
                        $length = 10;
                        $string = "1";
                        echo '
                          <input type="text" style="color: red;font-weight: bold;font-size: 18px;width: 150px;background-color: transparent;height: 0px;border: 0;" class="form-control input-lg" name="codigo" id="codigo" readonly="readonly" value="' . str_pad($string, $length, 0, STR_PAD_LEFT) . '">';
                    } else {
                        foreach ($pedido as $key => $value) {

                        }
                        $length = 10;
                        $codigo = str_pad($value["codigo"] + 1, $length, 0, STR_PAD_LEFT);
                        echo '<input type="text" style="color: red;font-weight: bold;font-size: 18px;width: 150px;background-color: transparent;height: 0px;border: 0;" class="form-control input-lg" name="codigo" id="codigo" readonly="readonly" value="' . $codigo . '">';
                    }
                    ?>
                </div>
                
                <div class="box-body">
                    <div class="col-lg-12">
                        <div class="form-group" style="display: flex;">
                            <div class="col-lg-4 input-group" style="margin: 0 10px 0 0;">
                                <label> <small style="color: #000;">Vendedor:</small>
                                    <input type="text" class="form-control" readonly="readonly" name="vendedorPedido"
                                           value="<?php echo $_SESSION['nombre']; ?>">
                                </label>
                                <input type="hidden" name="idVendedor"
                                       value="<?php echo $_SESSION['id_usuario']; ?>">
                            </div>
                            <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                <label> <small style="color: #000;">Fecha de Emisión:</small></label>
                                <input type="date" class="form-control" readonly="readonly" name="fechaRegistro" id="fechaRegistro"
                                       value="<?php date_default_timezone_set('America/Caracas'); echo date('Y-m-d'); ?>">
                            </div>
                            <div class="input-group" style="margin: 0 10px 0 0;">
                                <label></label>
                                <button type="button" class="btn btn-primary btnClientePedido" style="width: 100%;">
                                    Agregar Cliente
                                </button>
                            </div>
                            <div class="input-group">
                                <label></label>
                                <button type="button" class="btn btn-default " data-toggle="modal"
                                        data-target="#modalAgregarCliente" data-dismiss="modal">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group clientePedido" style="display: flex;">

                        </div>
                        <input type="hidden" id="listaCliente" name="listaCliente">
                        <input type="hidden" id="clienteID" name="clienteID">
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
                                        <th style="width: 10%">Cantidad</th>
                                        <th style="width: 10%">Precio</th>
                                        <th style="width: 10%">I.V.A</th>
                                        <th style="width: 10%">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody class="nuevoProductoPedido">

                                    </tbody>
                                </table>
                                <input type="hidden" id="listaProductosPedidos" name="listaProductosPedidos">
                                <button type="button" class="btn btn-default btnProductoPedido"
                                        style="margin: 10px 0 0 0;">
                                    Agregar productos
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
                                        <input type="text" class="form-control" value="TOTAL A PAGAR" style="font-weight: bold;background-color: transparent;border: 0;" required readonly>
                                    </td>
                                    <td style="width: 15%;">
                                        <input type="text" class="form-control" name="totalPedido" id="totalPedido" value="0.00" required readonly style="font-size: 30px;font-family: 'ds-digital'; color: #444;width: 100%;">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-primary">Crear Pedido</button>
                    </div>
                </div>
            </div>
        </form>
        <?php
        $crearPedidos = new ControllerPedidos();
        $crearPedidos->ctrCrearPedido();
        ?>
    </section>
</div>

<!--=====================================
MODAL AGREGAR CLIENTE
======================================-->
<div id="modalAgregarCliente" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post">
                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar cliente</h4>
                </div>
                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- CODIGO DE CLIENTE -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Codigo</small>
                                    <?php
                                    $item = null;
                                    $valor = null;
                                    $codigo_cliente = ControllerClients::ctrMostrarClientes($item, $valor);

                                    if (!$codigo_cliente) {
                                        echo '
                                                <input type="text" class="form-control input-lg" name="codigoCliente" id="codigoCliente" readonly="readonly" value="'. mt_rand(10000000, 99999999) .'">
                                            ';
                                    } else {
                                        foreach ($codigo_cliente as $key => $value) {

                                        }
                                        $codigo = $value["codigo"] + 1;
                                        echo '<input type="text" class="form-control input-lg" name="codigoCliente" id="codigoCliente" readonly="readonly" value="' . $codigo . '">';
                                    }
                                    ?>
                                </label>
                            </div>
                        </div>
                        <!-- TIPO DE CLIENTE -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Tipo de Cliente</small>
                                    <select class="form-control input-lg" name="nuevoTipoCliente">
                                        <option value="">Tipo de Cliente</option>
                                        <option value="NATURAL">Natural</option>
                                        <option value="JURIDICA">Jurudica</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <!-- TIPO O LETRA DEL DOCUMENTO -->
                        <div class="form-group col-lg-2">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;"></small>
                                    <select class="form-control input-lg" name="nuevaNacionalidad">
                                        <option value="">-</option>
                                        <option value="V">V</option>
                                        <option value="J">J</option>
                                        <option value="G">G</option>
                                        <option value="C">C</option>
                                        <option value="E">E</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <!-- ENTRADA PARA EL DOCUMENTO DE ID -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Nº de Documento:</small>
                                    <input type="text" min="0" class="form-control input-lg" name="documentoCliente"
                                           placeholder="Nº Documento" required>
                                </label>
                            </div>
                        </div>
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <small style="color: #000;">Nombre Cliente:</small>
                                <input type="text" class="form-control input-lg" name="nuevoCliente"
                                       placeholder="Cliente" required>
                            </div>
                        </div>
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="col-lg-6 form-group">
                            <label><small style="color: #000;">Denominación Fiscal:</small></label>
                            <select class="form-control input-lg" name="nuevoContribuyente">
                                <option value="">Seleccione Contribuyente</option>
                                <option value="NoContribuyente">No Contribuyente</option>
                                <option value="Formal">Formal</option>
                                <option value="Ordinario">Ordinario</option>
                                <option value="Exonerado">Exonerado</option>
                                <option value="Gobierno">Gobierno</option>
                                <option value="Extranjero">Exportacón/Extranjero</option>
                                <option value="SVM">SVM</option>
                            </select>
                        </div>
                        <!-- ENTRADA PARA EL EMAIL -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <small style="color: #000;">Email:</small>
                                <input type="email" class="form-control input-lg" name="nuevoEmail"
                                       placeholder="Correo Electronico" required>
                            </div>
                        </div>
                        <!-- ENTRADA PARA EL TELEFONO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <small style="color: #000;">Teléfono:</small>
                                <input type="text" class="form-control input-lg" name="nuevoTelefono"
                                       placeholder="Teléfono" data-inputmask="'mask':'(9999) 999-99-99'"
                                       data-mask required>
                            </div>
                        </div>
                        <!-- ENTRADA PARA PAISES-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Pais:</small>
                                    <select class="form-control input-lg" name="nuevoPais" required>
                                        <option value="">Pais</option>
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
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Estados:</small>
                                    <select class="form-control input-lg" name="nuevoEstado" required>
                                        <option value="">Estado</option>
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
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Ciudad:</small>
                                    <select class="form-control input-lg" name="nuevaCiudad" required>
                                        <option value="">Ciudad</option>
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
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Municipio:</small>
                                    <select class="form-control input-lg" name="nuevoMunicipio"
                                            required>
                                        <option value="">Municipio</option>
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
                        <!-- ENTRADA PARA EL DIRECCIÓN -->
                        <div class="form-group col-lg-12">
                            <div class="input-group">
                                <small style="color: #000;">Dirección:</small>
                                <input type="text" class="form-control input-lg" name="nuevaDireccion"
                                       placeholder="Ingresar dirección" required>
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
            $crearCliente = new ControllerClients();
            $crearCliente->ctrCrearClientes();
            ?>
        </div>
    </div>
</div>