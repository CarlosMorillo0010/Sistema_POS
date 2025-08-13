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
            Administrar Facturas de Compra
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar Factura de compra</li>
        </ol>

    </section>
    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <a href="crear-factura-compra">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarPerfil">
                        Crear Factura compra
                    </button>
                </a>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Nº Factura</th>
                        <th>Nº Pedido</th>
                        <th>Proveedor</th>
                        <th>Producto</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $item = null;
                    $valor = null;
                    $facturasCompra = ControllerFacturasCompras::ctrMostrarFacturaCompra($item, $valor);
                    
                    foreach ($facturasCompra as $key => $value) {
                        $proveedor = json_decode($value["proveedor"], true);
                        $productos = json_decode($value["productos"], true);
                        $length = 10;
                        echo '<tr>
                            <td>' . ($key + 1) . '</td>
                            <td>' . str_pad($value["codigo"], $length, 0, STR_PAD_LEFT) . '</td>
                            <td>' . str_pad($value["numero_pedido"], $length, 0, STR_PAD_LEFT) . '</td>
                            <td>' . strtoupper($proveedor[0]["nombre"]) . '</td>
                            <td>' . strtoupper($productos[0]["descripcion"]) . '</td>
                            <td>' . $value["fecha_emision"] . '</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-dark btnVerFacturaCompra" idFacturaCompra="' . $value["id_factura_compra"] . '" data-toggle="modal" data-target="#modalVerFacturaCompra"><i class="fa fa-eye"></i></button>                                   
                                    <button class="btn btn-warning btnEditarFacturaCompra" idFacturaCompra="' . $value["id_factura_compra"] . '" data-toggle="modal" data-target="#modalEditarFacturaCompra"><i class="fa fa-pencil"></i></button>                                   
                                    <button class="btn btn-danger btnEliminarFacturaCompra" idFacturaCompra="' . $value["id_factura_compra"] . '"><i class="fa fa-times"></i></button>
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
MODAL VISUALIZAR FACTURA COMPRA
---------------------------------------->
<div id="modalVerFacturaCompra" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 95%;">
        <div class="modal-content">
            <form class="formulario__OrdenCompra" role="form" method="post">
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Factura de Compra</h4>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <div class="box-header with-border" style="text-align: end;display: flex;align-items: center;">
                        <h4>Número Control</h4>
                        <!---------------------------------------
                        CODIGO DE ORDEN DE COMPRA
                        ---------------------------------------->
                        <input type="text"
                               style="color: red;font-weight: bold;font-size: 18px;width: 150px;background-color: transparent;height: 0px;border: 0;"
                               class="form-control input-lg" name="verCodigo" id="verCodigo" readonly>
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
                                               readonly
                                               value="<?php echo $respuestaUsuario["nombre"]; ?>">
                                    </label>
                                </div>
                                <!---------------------------------------
                                ENTRADA DE LA FECHA EMISION
                                ---------------------------------------->
                                <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                    <label> <small style="color: #000;">Fecha de Emisión:</small></label>
                                    <input type="input" name="verFechaEmision" id="verFechaEmision"
                                           class="form-control" value="<?php date_default_timezone_set('America/Caracas'); echo $value["feregistro"] ?>" readonly>
                                </div>
                            </div>
                            <!---------------------------------------
                            ENTRADA EDITAR PROVEEDOR
                            ---------------------------------------->
                            <div class="form-group nueva__ocp" style="display: flex;">
                                <div class="col-lg-3 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Proveedor:</small></label>
                                    <input class="form-control" id="verProveedor" name="verProveedorOrdenCompra" readonly>
                                </div>
                                <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Tipo Documento:</small></label>
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
                                <div style="height: 230px; overflow: auto;">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%"></th>
                                            <th style="width: 15%">Codigo</th>
                                            <th style="width: 40%">Nombre producto</th>
                                            <th style="width: 20%">Unidad</th>
                                            <th style="width: 20%">Cantidad</th>
                                        </tr>
                                        </thead>
                                        <tbody class="editarOrdenCompra">
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
                                        <td style="text-align: center;width: 15%;">
                                        <input class="form-control editarCodigoProducto" name="editarCodigoProducto" id="editarCodigoProducto" required>
                                        </td>
                                        <td style="text-align: center;width: 40%;">
                                        <input class="form-control editarDescripcionProducto" name="editarDescripcionProducto" id="editarDescripcionProducto" required style="text-transform: uppercase;">
                                        </td>
                                        <td style="text-align: center;width: 20%;">
                                        <input class="form-control editarUnidadProducto" name="editarUnidadProducto" id="editarUnidadProducto" required style="text-transform: uppercase;">
                                        </td>
                                        <td style="text-align: center; width: 20%;">
                                        <input type="tex" class="form-control editarCantidadProducto" name="editarCantidadProducto" id="editarCantidadProducto" required>
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
                </div>
            </form>
        </div>
    </div>
</div>

<!---------------------------------------
MODAL EDITAR FACTURA COMPRA
---------------------------------------->
<div id="modalEditarFacturaCompra" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 95%;">
        <div class="modal-content">
            <form class="formulario__OrdenCompra" role="form" method="post">
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Editar Factura de Compra</h4>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <div class="box-header with-border" style="text-align: end;display: flex;align-items: center;">
                    <h4>Número Control</h4>
                        <!---------------------------------------
                        CODIGO DE ORDEN DE COMPRA
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
                                           class="form-control" value="<?php date_default_timezone_set('America/Caracas'); echo $value["feregistro"] ?>" readonly="readonly">
                                </div>
                            </div>
                            <!---------------------------------------
                            ENTRADA EDITAR PROVEEDOR
                            ---------------------------------------->
                            <div class="form-group nueva__ocp" style="display: flex;">
                                <div class="col-lg-3 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Proveedor:</small></label>
                                    <input class="form-control" id="editarProveedor" name="editarProveedorOrdenCompra">
                                </div>
                                <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Tipo Documento:</small></label>
                                    <input type="text" class="form-control" id="editarTipoDocumento"
                                           name="editarTipoDocumento">
                                </div>
                                <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Documento:</small></label>
                                    <input type="text" class="form-control" id="editarDocumento" name="editarDocumento">
                                </div>
                                <div class="input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Tipo Proveedor:</small></label>
                                    <input type="text" class="form-control" id="editarTipoProveedor"
                                           name="editarTipoProveedor">
                                </div>
                                <div class="input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Telefono:</small></label>
                                    <input type="text" class="form-control" id="editarTelefonoProveedor"
                                           name="editarTelefonoProveedor" value="">
                                </div>
                                <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Pais:</small></label>
                                    <input type="text" class="form-control" id="editarPaisProveedor"
                                           name="editarPaisProveedor" value="">
                                </div>
                                <div class="col-lg-5 input-group">
                                    <label><small style="color: #000;">Direccion:</small></label>
                                    <input type="text" class="form-control" id="editarDireccionProveedor"
                                           name="editarDireccionProveedor" value="">
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
                                            <th style="width: 15%">Codigo</th>
                                            <th style="width: 40%">Nombre producto</th>
                                            <th style="width: 20%">Unidad</th>
                                            <th style="width: 20%">Cantidad</th>
                                        </tr>
                                        </thead>
                                        <tbody class="editarOrdenCompra">
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
                                        <td style="text-align: center;width: 15%;">
                                        <input class="form-control editarCodigoProducto" name="editarCodigoProducto" id="editarCodigoProducto" required>
                                        </td>
                                        <td style="text-align: center;width: 40%;">
                                        <input class="form-control editarDescripcionProducto" name="editarDescripcionProducto" id="editarDescripcionProducto" required style="text-transform: uppercase;">
                                        </td>
                                        <td style="text-align: center;width: 20%;">
                                        <input class="form-control editarUnidadProducto" name="editarUnidadProducto" id="editarUnidadProducto" required style="text-transform: uppercase;">
                                        </td>
                                        <td style="text-align: center; width: 20%;">
                                        <input type="tex" class="form-control editarCantidadProducto" name="editarCantidadProducto" id="editarCantidadProducto" required>
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
                    <button class="btn btn-primary">Guardar</button>
                </div>
            </form>
            <?php
            $editarFacturaCompra = new ControllerFacturasCompras();
            $editarFacturaCompra->ctrEditarFacturaCompra();
            ?>
        </div>
    </div>
</div>
<?php
$borrarFacturaCompra = new ControllerFacturasCompras();
$borrarFacturaCompra->ctrBorrarFacturaCompra();
?>