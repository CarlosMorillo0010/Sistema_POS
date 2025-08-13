<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Editar Orden de Compra
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Editar orden de compra</li>
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
                                $item = "id_orden_compra";
                                $valor = $_GET["idOrden"];
                                $ordenCompra = ControllerOrdenesCompras::ctrMostrarOrdenCompra($item, $valor);

                                $itemProveedor = "id_proveedor";
                                $valorProveedor = $ordenCompra["id_proveedor"];
                                $proveedor = ControllerProveedores::ctrMostrarProveedores($itemProveedor, $valorProveedor);

                                $length = 10;
                                $string = "1";
                                echo'
                                <input type="text" style="color: red;font-weight: bold;font-size: 20px;width: 150px;background-color: transparent;height: 0px;border: 0;" class="form-control input-lg" name="editarCodigo__OrdenCompra" readonly value="'. str_pad($ordenCompra["codigo"], $length, 0, STR_PAD_LEFT) .'">'
                                ?>
                            </div>
                            <div class="col-lg-2">
                                <!---------------------------------------
                                ENTRADA DE LA FECHA EMISION
                                ---------------------------------------->
                                <input type="date" class="form-control" value="<?php echo $ordenCompra["fecha"]?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 form-group" style="padding-left:0;padding-right:0;">
                                <div class="editarProveedores" style="display:flex;">
                                    <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Letra:</small></label>
                                    <input type="text" class="form-control editarTipoDocumento_OrdenCompra" id="editarTipoDocumento_OrdenCompra" name="editarTipoDocumento_OrdenCompra" value="<?php echo $proveedor["tipo_documento"] ?>" required readonly>
                                    </div>
                                    <div class="col-lg-4 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Documento:</small></label>
                                    <input type="text" class="form-control editarDocumento_OrdenCompra" id="editarDocumento_OrdenCompra" name="editarDocumento_OrdenCompra" value="<?php echo $proveedor["documento"] ?>" required readonly>
                                    </div>
                                    <div class="col-lg-7 input-group">
                                    <label><small style="color: #000;">Proveedor:</small></label>
                                    <input class="form-control editar-ProveedorOrdenCompra" idProveedor name="editar-ProveedorOrdenCompra" value="<?php echo $proveedor["nombre"] ?>" required readonly>
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
                                                data-target="#modalAgregarProveedor" disabled>
                                            Agregar Proveedor
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="editarListaProveedor" name="editarListaProveedor">
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
                                    <tbody class="editarOrdenCompra">
                                        <?php
                                            $listaProductos = json_decode($ordenCompra["productos"], true);
                                            foreach($listaProductos as $key => $value){
                                                echo' 
                                                <tr>
                                                </td>
                                                <td style="text-align: center; padding-left: 0;width: 5%;">
                                                <div class="input-group">
                                                <span class="input-group-addon" style="border: none;background-color: transparent;">
                                                <button type="button" class="btn btn-primary btn-xs agregarProducto" data-toggle="modal" data-target="#modalAgregarProducto">
                                                <i class="fa fa-plus"></i>
                                                </button>
                                                </span>
                                                </div>
                                                </td>
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
                                                <input class="form-control editarCodigoProducto" name="editarCodigoProducto" value="'.$value["codigo"].'" required readonly>
                                                </td>
                                                <td style="text-align: center;width: 40%;">
                                                <input class="form-control editarDescripcionProducto" name="editarDescripcionProducto" idProducto value="'.$value["descripcion"].'" required readonly style="text-transform: uppercase;">
                                                </td>
                                                <td style="text-align: center;width: 20%;">
                                                <input class="form-control editarUnidadMedida" name="editarUnidadMedida" value="'.$value["unidad"].'" required readonly style="text-transform: uppercase;">
                                                </td>
                                                <td style="text-align: center; padding-left: 0;width: 10%;">
                                                <input type="text" class="form-control editarCantidadProducto" name="editarCantidadProducto" value="'.$value["cantidad"].'" required style="border: none;">
                                                </td>
                                                </tr>';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <input type="hidden" id="editarListaProductos" name="editarListaProductos">
                                <button type="button" class="btn btn-default btnAgregarOrdenCompra"
                                        style="margin: 10px 0 0 0;">
                                    Agregar productos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary">Editar Orden de Compra</button>
                </div>
            </div>
        </form>
        <?php
        $editarOrdenCompra = new ControllerOrdenesCompras();
        $editarOrdenCompra->ctrEditarOrdenCompra();
        ?>
    </section>
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
                            <th>Unidad</th>
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
                            echo '<tr>
                                    <td>' . ($key + 1) . '</td>
                                    <td>' . $value["codigo"] . '</td>
                                    <td>' . $value["marca"] . ' </td>
                                    <td>' . $value["descripcion"] . '</td>
                                    <td>' . $value["unidad"] . '</td>
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