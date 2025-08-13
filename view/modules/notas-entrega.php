<?php
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value):
    if ($_SESSION["perfil"] == $value["perfil"] && $value["compras"] == "N"){
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
                <a href="crear-nota-entrega">
                    <button class="btn btn-primary">
                        Crear Nota de Entrega
                    </button>
                </a>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Nº Recepción</th>
                        <th>Nº Nota Entrega</th>
                        <th>Proveedor</th>
                        <th>Codigo</th>
                        <th>Descripción</th>
                        <th>Unidad</th>
                        <th>Cant</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $item = null;
                    $valor = null;
                    $notaEntregaCompra = ControllerNotasEntregas::ctrMostrarNotaEntrega($item, $valor);
                    
                    foreach ($notaEntregaCompra as $key => $value) {
                        $proveedor = json_decode($value["proveedor"], true);
                        $productos = json_decode($value["productos"], true);
                        $length = 10;
                        echo '<tr>
                            <td>' . ($key + 1) . '</td>
                            <td>' . str_pad($value["numero_recepcion"], $length, 0, STR_PAD_LEFT) . '</td>
                            <td>' . str_pad($value["numero_nota"], $length, 0, STR_PAD_LEFT) . '</td>
                            <td>' . strtoupper($proveedor[0]["nombre"]) . '</td>
                            <td>' . $productos[0]["codigo"] . '</td>
                            <td>' . strtoupper($productos[0]["descripcion"]) . '</td>
                            <td>' . $productos[0]["unidad"] . '</td>
                            <td>' . $productos[0]["cantidad"] . '</td>
                            <td>' . $productos[0]["total"] . '</td>
                            <td>' . $value["feregistro"] . '</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-dark btnVerNotaEntrega" idNotaCompra="' . $value["id_nota_entrega_compra"] . '" data-toggle="modal" data-target="#modalVerNotaEntrega"><i class="fa fa-eye"></i></button>                                  
                                    <button class="btn btn-warning btnEditarNotaEntrega" idNotaCompra="' . $value["id_nota_entrega_compra"] . '" data-toggle="modal" data-target="#modalEditarNotaEntrega"><i class="fa fa-pencil"></i></button>                                  
                                    <button class="btn btn-danger btnEliminarNotaEntrega" idNotaCompra="' . $value["id_nota_entrega_compra"] . '"><i class="fa fa-times"></i></button>
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
MODAL VISUALIZAR NOTAS DE ENTREGA
---------------------------------------->
<div id="modalVerNotaEntrega" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 95%;">
        <div class="modal-content">
            <form class="formulario__OrdenCompra" role="form" method="post">
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Nota de Entrega</h4>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <div class="box-body">
                        <div class="col-lg-12">
                            <div class="form-group" style="display: flex;">
                                <div class="col-lg-3 input-group" style="margin: 0 10px 0 0;">
                                    <label> <small style="color: #000;">Número de Recepción:</small></label>
                                    <input type="text" class="form-control" name="verNumeroRecepcion"
                                           id="verNumeroRecepcion" readonly>
                                </div>
                                <div class="col-lg-3 input-group" style="margin: 0 10px 0 0;">
                                    <label> <small style="color: #000;">Número Nota de Entrega:</small></label>
                                    <input type="text" class="form-control" name="verNumeroNota"
                                           id="verNumeroNota" readonly>
                                </div>
                                <div class="col-lg-3 input-group" style="padding-right: 10px;">
                                    <label> <small style="color: #000;">Fecha Nota de Entrega:</small></label>
                                    <input type="date" name="verFechaNotaEntrega" id="verFechaNotaEntrega"
                                           class="form-control" readonly>
                                </div>
                                <div class="col-lg-3 input-group">
                                    <label> <small style="color: #000;">Fecha de Registro:</small></label>
                                    <input type="date" name="verFechaRegistroNota" id="verFechaRegistroNota"
                                           class="form-control" readonly>
                                </div>
                            </div>
                            <div class="nuevoProveedor" style="display: flex;">
                                <div class="col-lg-3 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Proveedor:</small></label>
                                    <input class="form-control" id="verProveedor" name="verProveedorOrdenCompra" readonly>
                                </div>
                                <div class="col-lg-2 input-group" style="padding-right: 10px; width: 80px;">
                                    <label><small style="color: #000;">Letra:</small></label>
                                    <input type="text" class="form-control" id="verTipoDocumento"
                                           name="verTipoDocumento" readonly>
                                </div>
                                <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Documento:</small></label>
                                    <input type="text" class="form-control" id="verDocumento" name="verDocumento" readonly>
                                </div>
                                <div class="input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Tipo Proveedor:</small></label>
                                    <input type="text" class="form-control" id="verTipoProveedor"
                                           name="verTipoProveedor" readonly>
                                </div>
                                <div class="input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Telefono:</small></label>
                                    <input type="text" class="form-control" id="verTelefonoProveedor"
                                           name="verTelefonoProveedor" readonly>
                                </div>
                                <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Pais:</small></label>
                                    <input type="text" class="form-control" id="verPaisProveedor"
                                           name="verPaisProveedor" readonly>
                                </div>
                                <div class="col-lg-5 input-group">
                                    <label><small style="color: #000;">Direccion:</small></label>
                                    <input type="text" class="form-control" id="verDireccionProveedor"
                                           name="verDireccionProveedor" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div style="height: 230px; overflow: auto; margin-top: 20px;">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                        <th style="width: 5%"></th>
                                        <th style="width: 10%">Codigo</th>
                                        <th style="width: 25%">Nombre producto</th>
                                        <th style="width: 15%">Unidades</th>
                                        <th style="width: 10%">Precio Unitario</th>
                                        <th style="width: 10%">Cantidad</th>
                                        <th style="width: 15%">Precio Total</th>
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
                                        <input class="form-control" name="verCodigoProducto" id="verCodigo" readonly>
                                        </td>
                                        <td style="text-align: center;width: 25%;">
                                        <input class="form-control" name="verNombre" id="verNombre" readonly style="text-transform: uppercase;">
                                        </td>
                                        <td style="text-align: center;width: 15%;">
                                        <input class="form-control" name="verUnidadMedida" id="verUnidadMedida" readonly style="text-transform: uppercase;">
                                        </td>
                                        <td style="text-align: center; width: 10%;">
                                        <input type="text" class="form-control" name="verPrecioUnitario" id="verPrecioUnitario" readonly>
                                        </td>
                                        <td style="text-align: center; width: 10%;">
                                        <input type="number" class="form-control" name="verCantidad" id="verCantidad" readonly>
                                        </td>
                                        <td style="text-align: center; width: 15%;">
                                        <input type="text" class="form-control" name="verTotal" id="verTotal" readonly>
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
                                    <input type="text" class="form-control" name="verTotal_NotaEntregaCompra" id="verTotal_NotaEntregaCompra" value="0.00" required readonly style="font-size: 30px;font-family: 'ds-digital'; color: #444;width: 100%;">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
            <?php
            ?>
        </div>
    </div>
</div>

<!---------------------------------------
MODAL EDITAR NOTAS DE ENTREGA
---------------------------------------->
<div id="modalEditarNotaEntrega" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 95%;">
        <div class="modal-content">
            <form class="formulario__OrdenCompra" role="form" method="post">
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Editar Nota de Entrega</h4>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <div class="box-body">
                        <div class="col-lg-12">
                            <div class="form-group" style="display: flex;">
                                <div class="col-lg-3 input-group" style="margin: 0 10px 0 0;">
                                    <label> <small style="color: #000;">Número de Recepción:</small></label>
                                    <input type="text" class="form-control" name="editarNumeroRecepcion"
                                           id="editarNumeroRecepcion" readonly>
                                </div>
                                <div class="col-lg-3 input-group" style="margin: 0 10px 0 0;">
                                    <label> <small style="color: #000;">Número Nota de Entrega:</small></label>
                                    <input type="text" class="form-control" name="editarNumeroNota"
                                           id="editarNumeroNota" readonly>
                                </div>
                                <div class="col-lg-3 input-group" style="padding-right: 10px;">
                                    <label> <small style="color: #000;">Fecha Nota de Entrega:</small></label>
                                    <input type="date" name="editarFechaNotaEntrega" id="editarFechaNotaEntrega"
                                           class="form-control" readonly>
                                </div>
                                <div class="col-lg-3 input-group">
                                    <label> <small style="color: #000;">Fecha de Registro:</small></label>
                                    <input type="date" name="editarFechaRegistroNota" id="editarFechaRegistroNota"
                                           class="form-control" readonly>
                                </div>
                            </div>
                            <div class="nuevoProveedor" style="display: flex;">
                                <div class="col-lg-3 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Proveedor:</small></label>
                                    <input class="form-control" id="editarProveedor" name="editarProveedorOrdenCompra" readonly>
                                </div>
                                <div class="col-lg-2 input-group" style="padding-right: 10px; width: 80px;">
                                    <label><small style="color: #000;">Letra:</small></label>
                                    <input type="text" class="form-control" id="editarTipoDocumento"
                                           name="editarTipoDocumento" readonly>
                                </div>
                                <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Documento:</small></label>
                                    <input type="text" class="form-control" id="editarDocumento" name="editarDocumento" readonly>
                                </div>
                                <div class="input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Tipo Proveedor:</small></label>
                                    <input type="text" class="form-control" id="editarTipoProveedor"
                                           name="editarTipoProveedor" readonly>
                                </div>
                                <div class="input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Telefono:</small></label>
                                    <input type="text" class="form-control" id="editarTelefonoProveedor"
                                           name="editarTelefonoProveedor" readonly>
                                </div>
                                <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Pais:</small></label>
                                    <input type="text" class="form-control" id="editarPaisProveedor"
                                           name="editarPaisProveedor" readonly>
                                </div>
                                <div class="col-lg-5 input-group">
                                    <label><small style="color: #000;">Direccion:</small></label>
                                    <input type="text" class="form-control" id="editarDireccionProveedor"
                                           name="editarDireccionProveedor" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div style="height: 230px; overflow: auto; margin-top: 20px;">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                        <th style="width: 5%"></th>
                                        <th style="width: 10%">Codigo</th>
                                        <th style="width: 25%">Nombre producto</th>
                                        <th style="width: 15%">Unidades</th>
                                        <th style="width: 10%">Precio Unitario</th>
                                        <th style="width: 10%">Cantidad</th>
                                        <th style="width: 15%">Precio Total</th>
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
                                        <input class="form-control" name="editarCodigoProducto" id="editarCodigo" readonly>
                                        </td>
                                        <td style="text-align: center;width: 25%;">
                                        <input class="form-control" name="editarNombre" id="editarNombre" readonly style="text-transform: uppercase;">
                                        </td>
                                        <td style="text-align: center;width: 15%;">
                                        <input class="form-control" name="editarUnidadMedida" id="editarUnidadMedida" readonly style="text-transform: uppercase;">
                                        </td>
                                        <td style="text-align: center; width: 10%;">
                                        <input type="text" class="form-control" name="editarPrecioUnitario" id="editarPrecioUnitario" readonly>
                                        </td>
                                        <td style="text-align: center; width: 10%;">
                                        <input type="number" class="form-control" name="editarCantidad" id="editarCantidad" readonly>
                                        </td>
                                        <td style="text-align: center; width: 15%;">
                                        <input type="text" class="form-control" name="editarTotal" id="editarTotal" readonly>
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
                                    <input type="text" class="form-control" name="editarTotal_NotaEntregaCompra" id="editarTotal_NotaEntregaCompra" value="0.00" required readonly style="font-size: 30px;font-family: 'ds-digital'; color: #444;width: 100%;">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
            <?php
            ?>
        </div>
    </div>
</div>
<?php
$borrarNotaEntrega = new ControllerNotasEntregas();
$borrarNotaEntrega->ctrBorrarNotaEntrega();
?>