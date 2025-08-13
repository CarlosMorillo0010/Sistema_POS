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
            Administrar Notas de Entrega
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar Notas de entrega</li>
        </ol>

    </section>
    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <a href="crear-nota-entrega-venta">
                    <button class="btn btn-primary">
                        Crear Nota de entrega
                    </button>
                </a>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                    <tr>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Nº Recepción</th>
                        <th>Nº Nota Entrega</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $item = null;
                    $valor = null;
                    $notaEntregaVentas = ControllerNotasEntregaVentas::ctrMostrarNotaEntregaVenta($item, $valor);
                    
                    foreach ($notaEntregaVentas as $key => $value) {
                        $cliente = json_decode($value["cliente"], true);
                        $productos = json_decode($value["productos"], true);
                        $length = 10;
                        echo '<tr>
                            <td>' . ($key + 1) . '</td>
                            <td>' . str_pad($value["numero_recepcion"], $length, 0, STR_PAD_LEFT) . '</td>
                            <td>' . str_pad($value["numero_nota"], $length, 0, STR_PAD_LEFT) . '</td>
                            <td>' . strtoupper($cliente[0]["nombre"]) . '</td>
                            <td>' . $value["feregistro"] . '</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-dark btnVerNotaEntrega" idNotaVentas="' . $value["id_nota_entrega"] . '" data-toggle="modal" data-target="#modalVerNotaEntrega"><i class="fa fa-eye"></i></button>                                  
                                    <button class="btn btn-warning btnEditarNotaEntrega" idNotaVentas="' . $value["id_nota_entrega"] . '" data-toggle="modal" data-target="#modalEditarNotaEntrega"><i class="fa fa-pencil"></i></button>                                  
                                    <button class="btn btn-danger btnEliminarNotaEntrega" idNotaVentas="' . $value["id_nota_entrega"] . '"><i class="fa fa-times"></i></button>
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
MODAL VISUALIZAR NOTA DE ENTREGA VENTA
---------------------------------------->
<div id="modalVerNotaEntrega" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 100%;padding: 0 17px">
        <div class="modal-content">
            <form class="formulario__NotaEntregaVenta" role="form" method="post">
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Nota de Entrega Venta</h4>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <!-- <div class="box-header with-border" style="text-align: end;display: flex;align-items: center;">
                        <h4>Número pedido</h4>
                        <input type="text"
                               style="color: red;font-weight: bold;font-size: 18px;width: 150px;background-color: transparent;height: 0px;border: 0;"
                               class="form-control input-lg" name="verCodigo" id="verCodigo" readonly="readonly">
                    </div> -->
                    <div class="box-body" style="padding-bottom: 0;">
                    <div class="col-lg-12">
                        <div class="form-group" style="display: flex;">
                            <div class="col-lg-3 input-group" style="margin: 0 10px 0 0;">
                                <label> <small style="color: #000;">Número de Recepción:</small></label>
                                <input type="text" class="form-control" name="verNumeroRecepcionVenta"
                                       id="verNumeroRecepcionVenta" readonly>
                            </div>
                            <div class="col-lg-3 input-group" style="margin: 0 10px 0 0;">
                                <label> <small style="color: #000;">Número Nota de Entrega:</small></label>
                                <input type="text" class="form-control" name="verNumeroNotaEntrega"
                                       id="verNumeroNotaEntrega" readonly>
                            </div>
                            <div class="col-lg-3 input-group" style="padding-right: 10px;">
                                <label> <small style="color: #000;">Fecha Nota de Entrega:</small></label>
                                <input type="date" class="form-control" name="verFechaNota"
                                       id="verFechaNota" readonly>
                            </div>
                            <div class="col-lg-3 input-group">
                                <label> <small style="color: #000;">Fecha de Registro:</small></label>
                                <input type="date" class="form-control datepicker" name="verFechaRegistro"
                                       id="verFechaRegistro" style="padding-left: 10px;" readonly>
                            </div>
                        </div>
                        <!---------------------------------------
                        ENTRADA CLIENTE
                        ---------------------------------------->
                        <div class="form-group" style="display: flex;">
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
                                    <input type="text" class="form-control" name="verTotalNotaEntregaVenta" id="verTotalNotaEntregaVenta" value="0.00" required readonly style="font-size: 30px;font-family: 'ds-digital'; color: #444;width: 100%;">
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
MODAL EDITAR NOTA DE ENTREGA VENTA
---------------------------------------->
<div id="modalEditarNotaEntrega" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 100%;padding: 0 17px">
        <div class="modal-content">
            <form class="formulario__NotaEntregaVenta" role="form" method="post">
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Nota de Entrega Venta</h4>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <!-- <div class="box-header with-border" style="text-align: end;display: flex;align-items: center;">
                        <h4>Número pedido</h4>
                        <input type="text"
                               style="color: red;font-weight: bold;font-size: 18px;width: 150px;background-color: transparent;height: 0px;border: 0;"
                               class="form-control input-lg" name="verCodigo" id="verCodigo" readonly="readonly">
                    </div> -->
                    <div class="box-body" style="padding-bottom: 0;">
                    <div class="col-lg-12">
                        <div class="form-group" style="display: flex;">
                            <div class="col-lg-3 input-group" style="margin: 0 10px 0 0;">
                                <label> <small style="color: #000;">Número de Recepción:</small></label>
                                <input type="text" class="form-control" name="editarNumeroRecepcionVenta"
                                       id="editarNumeroRecepcionVenta" readonly>
                            </div>
                            <div class="col-lg-3 input-group" style="margin: 0 10px 0 0;">
                                <label> <small style="color: #000;">Número Nota de Entrega:</small></label>
                                <input type="text" class="form-control" name="editarNumeroNotaEntrega"
                                       id="editarNumeroNotaEntrega" readonly>
                            </div>
                            <div class="col-lg-3 input-group" style="padding-right: 10px;">
                                <label> <small style="color: #000;">Fecha Nota de Entrega:</small></label>
                                <input type="date" class="form-control" name="editarFechaNota"
                                       id="editarFechaNota" readonly>
                            </div>
                            <div class="col-lg-3 input-group">
                                <label> <small style="color: #000;">Fecha de Registro:</small></label>
                                <input type="date" class="form-control datepicker" name="editarFechaRegistro"
                                       id="editarFechaRegistro" style="padding-left: 10px;" readonly>
                            </div>
                        </div>
                        <!---------------------------------------
                        ENTRADA CLIENTE
                        ---------------------------------------->
                        <div class="form-group" style="display: flex;">
                            <div class="col-lg-3 input-group" style="padding-right: 10px;">
                                <label><small style="color: #000;">Cliente:</small></label>
                                <input class="form-control" id="editarCliente" name="editarCliente" readonly>
                            </div>
                            <div class="col-lg-2 input-group" style="padding-right: 10px;width: 80px;">
                                <label><small style="color: #000;">Letra:</small></label>
                                <input type="text" class="form-control" id="editarTipoDocumento" name="editarTipoDocumento" readonly>
                            </div>
                            <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                <label><small style="color: #000;">Documento:</small></label>
                                <input type="text" class="form-control" id="editarDocumento" name="editarDocumento" readonly>
                            </div>
                            <div class="input-group" style="padding-right: 10px;">
                                <label><small style="color: #000;">Telefono:</small></label>
                                <input type="text" class="form-control" id="editarTelefonoCliente" name="editarTelefonoCliente" readonly>
                            </div>
                            <div class="col-lg-3 input-group" style="padding-right: 10px;">
                                <label><small style="color: #000;">Correo Electronico:</small></label>
                                <input type="text" class="form-control" id="editarEmailCliente" name="editarEmailCliente" readonly>
                            </div>
                            <div class="col-lg-5 input-group">
                                <label><small style="color: #000;">Direccion:</small></label>
                                <input type="text" class="form-control" id="editarDireccionCliente" name="editarDireccionCliente" readonly>
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
                                        <th style="width: 10%">Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: center; padding-left: 0;width: 5%;">
                                                <div class="input-group">
                                                <span class="input-group-addon" style="border: none;background-color: transparent;">
                                                <button type="button" class="btn btn-danger btn-xs">
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
                                    <input type="text" class="form-control" name="editarTotalNotaEntregaVenta" id="editarTotalNotaEntregaVenta" value="0.00" required readonly style="font-size: 30px;font-family: 'ds-digital'; color: #444;width: 100%;">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
            <?php
            $editarNotaEntregaVentas = new ControllerNotasEntregaVentas();
            $editarNotaEntregaVentas->ctrEditarNotaEntregaVenta();
            ?>
        </div>
    </div>
</div>
<?php
$borrarNotaEntregaVentas = new ControllerNotasEntregaVentas();
$borrarNotaEntregaVentas->ctrBorrarNotaEntregaVenta();
?>