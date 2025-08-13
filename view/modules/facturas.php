<?php
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value):
    if ($_SESSION["perfil"] == $value["perfil"] && $value["ventas"] == "N"){
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
            Administrar Factura
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar factura</li>
        </ol>

    </section>
    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <a href="crear-factura-venta">
                    <button class="btn btn-primary"">
                        Crear Factura
                    </button>
                </a>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Nº de Factura</th>
                        <th>Vendedor</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    date_default_timezone_set('America/Caracas');
                    $item = null;
                    $valor = null;
                    $facturaVentas = ControllerFacturaVentas::ctrMostrarFacturaVenta($item, $valor);

                    foreach ($facturaVentas as $key => $value){
                        $length = 10;
                        echo '<tr>
                            <td>' . ($key + 1) . '</td>
                            <td>' . str_pad($value["codigo"], $length, 0, STR_PAD_LEFT) . '</td>
                            <td>' . strtoupper($value["vendedor"]) . '</td>';
                            $itemCliente = "id";
                            $valorCliente = $value["id_cliente"];
                            $respuestaCliente = ControllerClients::ctrMostrarClientes($itemCliente, $valorCliente);
                            echo '<td>' . strtoupper($respuestaCliente["nombre"]) . '</td>';
                            echo '<td>' . $value["fecha_registro"] . '</td>
                            <td>
                            <div class="btn-group">
                            <button class="btn bg-dark btnVerFactura" idFactura="' . $value["id_factura_venta"] . '" data-toggle="modal" data-target="#modalVerFactura"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-warning btnEditarFactura" idFactura="' . $value["id_factura_venta"] . '" data-toggle="modal" data-target="#modalEditarFactura"><i class="fa fa-pencil"></i></button>                              
                            <button class="btn btn-danger btnEliminarFactura" idFactura="' . $value["id_factura_venta"] . '"><i class="fa fa-times"></i></button>
                            </div>
                            </td>
                        </tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!---------------------------------------
MODAL VISUALIZAR FACTURAS DE VENTAS
---------------------------------------->
<div id="modalVerFactura" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 100%;padding-right: 17px;">
        <div class="modal-content">
            <form class="formularioFacturaVenta" role="form" method="post">
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Factura de Ventas</h4>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <div class="box-header with-border" style="text-align: end;display: flex;align-items: center;">
                        <h4>Número de Control</h4>
                        <input type="text"
                               style="color: red;font-weight: bold;font-size: 18px;width: 150px;background-color: transparent;height: 0px;border: 0;"
                               class="form-control input-lg" name="verCodigo" id="verCodigo" readonly="readonly">
                    </div>
                    <div class="box-body">
                        <div class="col-lg-12">
                            <div class="form-group" style="display: flex;">
                                <!---------------------------------------
                                ENTRADA DEL VENDEDOR
                                ---------------------------------------->
                                <div class="col-lg-3 input-group" style="margin: 0 10px 0 0;">
                                    <label> <small style="color: #000;">Usuario:</small>
                                        <?php
                                        $itemUsuario = "id_usuario";
                                        $valorUsuario = $value["id_usuario"];
                                        $respuestaUsuario = ControllerUsers::ctrMostrarUsuario($itemUsuario, $valorUsuario);
                                        ?>
                                        <input name="verUsuario" id="verUsuario" type="text" class="form-control"
                                               readonly="readonly"
                                               value="<?php echo $respuestaUsuario["nombre"]; ?>">
                                    </label>
                                </div>
                                <!---------------------------------------
                                ENTRADA DE LA FECHA EMISION
                                ---------------------------------------->
                                <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                    <label> <small style="color: #000;">Fecha de Emisión:</small></label>
                                    <input type="input" name="verFechaEmision" id="verFechaEmision"
                                           class="form-control" value="<?php date_default_timezone_set('America/Caracas'); echo $value["fecha_registro"] ?>" readonly="readonly">
                                </div>
                            </div>
                            <!---------------------------------------
                            ENTRADA CLIENTE
                            ---------------------------------------->
                            <div class="form-group nueva__ocp" style="display: flex;">
                                <div class="col-lg-3 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Cliente:</small></label>
                                    <input class="form-control" id="verCliente" name="verCliente" readonly>
                                </div>
                                <div class="col-lg-2 input-group" style="padding-right: 10px;width: 80px;">
                                    <label><small style="color: #000;">Letra:</small></label>
                                    <input type="text" class="form-control" id="verTipoDocumento" name="verTipoDocumento" readonly>
                                </div>
                                <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Documento:</small></label>
                                    <input type="text" class="form-control" id="verDocumento" name="verDocumento" readonly>
                                </div>
                                <div class="input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Telefono:</small></label>
                                    <input type="text" class="form-control" id="verTelefonoCliente" name="verTelefonoCliente" readonly>
                                </div>
                                <div class="col-lg-3 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Correo Electronico:</small></label>
                                    <input type="text" class="form-control" id="verEmailCliente" name="verEmailCliente" readonly>
                                </div>
                                <div class="col-lg-5 input-group">
                                    <label><small style="color: #000;">Direccion:</small></label>
                                    <input type="text" class="form-control" id="verDireccionCliente" name="verDireccionCliente" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div style="height: 230px; overflow: auto;">
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
                                        <tbody>
                                            <tr>
                                                <td style="text-align: center; padding-left: 0;width: 5%;">
                                                <div class="input-group">
                                                <span class="input-group-addon" style="border: none;background-color: transparent;">
                                                <button type="button" class="btn btn-danger btn-xs disabled">
                                                <i class="fa fa-times"></i>
                                                </button>
                                                </span>
                                                </div>
                                                </td>
                                                <td style="text-align: center;width: 10%;">
                                                <input class="form-control verCodigoProducto" name="verCodigoProducto" id="verCodigoProducto" readonly>
                                                </td>
                                                <td style="text-align: center;width: 25%;">
                                                <input class="form-control verDescripcionProducto" name="verDescripcionProducto" id="verDescripcionProducto" readonly style="text-transform: uppercase;">
                                                </td>
                                                <td style="text-align: center;width: 10%;">
                                                <input class="form-control verUnidadProducto" name="verUnidadProducto" id="verUnidadProducto" readonly style="text-transform: uppercase;">
                                                </td>
                                                <td style="text-align: center; width: 10%;">
                                                <input type="tex" class="form-control verCantidadProducto" name="verCantidadProducto" id="verCantidadProducto" readonly>
                                                </td>
                                                <td style="text-align: center; width: 10%;">
                                                <input type="tex" class="form-control verPrecioProducto" name="verPrecioProducto" id="verPrecioProducto" readonly>
                                                </td>
                                                <td style="text-align: center; width: 10%;">
                                                <input type="tex" class="form-control verImpuestoProducto" name="verImpuestoProducto" id="verImpuestoProducto" readonly>
                                                </td>
                                                <td style="text-align: center; width: 10%;">
                                                <input type="tex" class="form-control verTotalProducto" name="verTotalProducto" id="verTotalProducto" readonly>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
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
                                    <input type="text" class="form-control" name="verTotalFactura" id="verTotalFactura" value="0.00" required readonly style="font-size: 30px;font-family: 'ds-digital'; color: #444;width: 100%;">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<!---------------------------------------
MODAL EDITAR FACTURA DE VENTA
---------------------------------------->
<div id="modalEditarFactura" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 95%;">
        <div class="modal-content">
            <form class="formularioFacturaVenta" role="form" method="post">
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Editar Factura de Venta</h4>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <div class="box-header with-border" style="text-align: end;display: flex;align-items: center;">
                        <h4>Número de Control</h4>
                        <!---------------------------------------
                        CODIGO DE PEDIDOS
                        ---------------------------------------->
                        <input type="text"
                               style="color: red;font-weight: bold;font-size: 18px;width: 150px;background-color: transparent;height: 0px;border: 0;"
                               class="form-control input-lg" name="editarCodigo" id="editarCodigo" readonly="readonly">
                    </div>
                    <div class="box-body">
                        <div class="col-lg-12">
                            <div class="form-group" style="display: flex;">
                                <!---------------------------------------
                                ENTRADA DEL VENDEDOR
                                ---------------------------------------->
                                <div class="col-lg-3 input-group" style="margin: 0 10px 0 0;">
                                    <label> <small style="color: #000;">Usuario:</small>
                                        <?php
                                        $itemUsuario = "id_usuario";
                                        $valorUsuario = $value["id_usuario"];
                                        $respuestaUsuario = ControllerUsers::ctrMostrarUsuario($itemUsuario, $valorUsuario);
                                        ?>
                                        <input name="editarUsuario" id="editarUsuario" type="text" class="form-control"
                                               readonly="readonly"
                                               value="<?php echo $respuestaUsuario["nombre"]; ?>">
                                    </label>
                                </div>
                                <!---------------------------------------
                                ENTRADA DE LA FECHA EMISION
                                ---------------------------------------->
                                <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                    <label> <small style="color: #000;">Fecha de Emisión:</small></label>
                                    <input type="input" name="editarFechaEmision" id="editarFechaEmision"
                                           class="form-control" value="<?php date_default_timezone_set('America/Caracas'); echo $value["fecha_registro"] ?>" readonly="readonly">
                                </div>
                            </div>
                            <!---------------------------------------
                            ENTRADA EDITAR CLIENTE
                            ---------------------------------------->
                            <div class="form-group nueva__ocp" style="display: flex;">
                                <div class="col-lg-3 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Cliente:</small></label>
                                    <input class="form-control" id="editarCliente" name="editarClienteOrdenCompra" readonly>
                                </div>
                                <div class="col-lg-2 input-group" style="padding-right: 10px;width: 80px;">
                                    <label><small style="color: #000;">Letra:</small></label>
                                    <input type="text" class="form-control" id="editarTipoDocumento"
                                           name="editarTipoDocumento" minlength="1" maxlength="1" readonly>
                                </div>
                                <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Documento:</small></label>
                                    <input type="text" class="form-control" id="editarDocumento" name="editarDocumento" readonly>
                                </div>
                                <div class="input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Telefono:</small></label>
                                    <input type="text" class="form-control" id="editarTelefonoCliente"
                                           name="editarTelefonoCliente" value="" readonly>
                                </div>
                                <div class="col-lg-3 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Correo Electronico:</small></label>
                                    <input type="text" class="form-control" id="editarEmailCliente"
                                           name="editarEmailCliente" value="" readonly>
                                </div>
                                <div class="col-lg-5 input-group">
                                    <label><small style="color: #000;">Direccion:</small></label>
                                    <input type="text" class="form-control" id="editarDireccionCliente"
                                           name="editarDireccionCliente" value="" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div style="height: 230px; overflow: auto;">
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
                                        <tbody>
                                        <tr>
                                        <td style="text-align: center; padding-left: 0;width: 5%;">
                                        <div class="input-group">
                                        <span class="input-group-addon" style="border: none;background-color: transparent;">
                                        <button type="button" class="btn btn-danger btn-xs quitarProducto">
                                        <i class="fa fa-times"></i>
                                        </button>
                                        </span>
                                        </div>
                                        </td>
                                        <td style="text-align: center;width: 10%;">
                                        <input class="form-control editarCodigoProducto" name="editarCodigoProducto" id="editarCodigoProducto" required>
                                        </td>
                                        <td style="text-align: center;width: 25%;">
                                        <input class="form-control editarDescripcionProducto" name="editarDescripcionProducto" id="editarDescripcionProducto" required style="text-transform: uppercase;">
                                        </td>
                                        <td style="text-align: center;width: 10%;">
                                        <input class="form-control editarUnidadProducto" name="editarUnidadProducto" id="editarUnidadProducto" required style="text-transform: uppercase;">
                                        </td>
                                        <td style="text-align: center; width: 10%;">
                                        <input type="tex" class="form-control editarCantidadProducto" name="editarCantidadProducto" id="editarCantidadProducto" required>
                                        </td>
                                        <td style="text-align: center; width: 10%;">
                                        <input type="tex" class="form-control editarPrecioProducto" name="editarPrecioProducto" id="editarPrecioProducto" required>
                                        </td>
                                        <td style="text-align: center; width: 10%;">
                                        <input type="tex" class="form-control editarImpuestoProducto" name="editarImpuestoProducto" id="editarImpuestoProducto" required>
                                        </td>
                                        <td style="text-align: center; width: 10%;">
                                        <input type="tex" class="form-control editarTotalProducto" name="editarTotalProducto" id="editarTotalProducto" required>
                                        </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12" style="width: 100%;">
                        <div>
                        <table class="table" border="0" style="margin-bottom: 0 !important;">
                            <tbody>
                                <tr>
                                    <td><button class="btn btn-primary">Guardar</button></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="width: 15%;">
                                        <input type="text" class="form-control" value="TOTAL A PAGAR" style="font-weight: bold;background-color: transparent;border: 0;" required readonly>
                                    </td>
                                    <td style="width: 15%;">
                                        <input type="text" class="form-control" name="editarTotalFactura" id="editarTotalFactura" value="0.00" required readonly style="font-size: 30px;font-family: 'ds-digital'; color: #444;width: 100%;">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                <div class="modal-footer">
                    
                </div>
            </form>
            <?php
            $editarFactura = new ControllerFacturaVentas();
            $editarFactura->ctrEditarFactura();
            ?>
        </div>
    </div>
</div>
<?php
$borrarFactura = new ControllerFacturaVentas();
$borrarFactura->ctrBorrarFactura();
?>