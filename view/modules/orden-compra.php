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
            Administrar orden de compra
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar orden de compra</li>
        </ol>

    </section>
    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <a href="crear-orden-compra">
                    <button class="btn btn-primary">
                        Crear orden de compra
                    </button>
                </a>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Nº de Orden</th>
                        <th>Proveedor</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    date_default_timezone_set('America/Caracas');
                    $item = null;
                    $valor = null;
                    $ordenCompra = ControllerOrdenesCompras::ctrMostrarOrdenCompra($item, $valor);

                    foreach ($ordenCompra as $key => $value) {
                        $length = 10;
                        echo '<tr>
                            <td>' . ($key + 1) . '</td>
                            <td>' . str_pad($value["codigo"], $length, 0, STR_PAD_LEFT) . '</td>';
                        $itemProveedor = "id_proveedor";
                        $valorProveedor = $value["id_proveedor"];
                        $respuestaProveedor = ControllerProveedores::ctrMostrarProveedores($itemProveedor, $valorProveedor);
                        if (is_array($respuestaProveedor) && isset($respuestaProveedor["nombre"])) {
                            echo '<td>' . strtoupper($respuestaProveedor["nombre"]) . '</td>';
                        } else {
                            echo '<td>NO ENCONTRADO</td>';
                        }
                        echo '<td>' . $value["fecha"] . '</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-info btnImprimir_ODC" codigo="' . $value["codigo"] . '" style="background-color: #00c0ef !important;"><i class="fa fa-print"></i></button>
                                    <button class="btn bg-dark btnVerOrdenCompra" idOrden="' . $value["id_orden_compra"] . '" data-toggle="modal" data-target="#modalVerOrdenCompra"><i class="fa fa-search"></i></button>                              
                                    <button class="btn btn-warning btnEditarOrdenCompra" idOrden="' . $value["id_orden_compra"] . '"><i class="fa fa-pencil"></i></button>                              
                                    <button class="btn btn-danger btnEliminarOrdenCompra" idOrden="' . $value["id_orden_compra"] . '"><i class="fa fa-times"></i></button>
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
MODAL VISUALIZAR DE ORDEN DE COMPRA
---------------------------------------->
<div id="modalVerOrdenCompra" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 95%;">
        <div class="modal-content">
            <form class="formulario__OrdenCompra" role="form" method="post">
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Orden de Compra</h4>
                </div>
                <div class="modal-body" style="padding: 0;">
                <div class="box-header with-border">
                    <div class="col lg-12">
                        <div class="row">
                            <div class="col-lg-10" style="display: flex;align-items: center;">
                            <h4 style="text-transform: uppercase;">Nº de Orden</h4>
                                <!---------------------------------------
                                CODIGO DE ORDEN DE COMPRA
                                ---------------------------------------->
                                <input id="verCodigo_OrdenCompra" type="text" class="form-control input-lg" readonly="readonly" style="color: red;font-weight: bold;font-size: 20px;width: 150px;background-color: transparent;height: 0px;border: 0;">
                            </div>
                            <div class="col-lg-2">
                                <!---------------------------------------
                                ENTRADA DE LA FECHA EMISION
                                ---------------------------------------->
                                <input id="verFechaEmision" type="date" class="form-control" readonly="readonly">
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
                                    <input type="text" class="form-control" id="verTipoDocumento" readonly>
                                    </div>
                                    <div class="col-lg-4 input-group" style="padding-right: 10px;">
                                    <label><small style="color: #000;">Documento:</small></label>
                                    <input type="text" class="form-control" id="verDocumento" readonly>
                                    </div>
                                    <div class="col-lg-7 input-group">
                                    <label><small style="color: #000;">Proveedor:</small></label>
                                    <input class="form-control" id="verProveedor" readonly style="text-transform: uppercase;">
                                    </div>
                                </div>
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
                                            <th style="width: 5%"></th>
                                            <th style="width: 15%">Codigo</th>
                                            <th style="width: 40%">Nombre producto</th>
                                            <th style="width: 20%">Unidad</th>
                                            <th style="width: 20%">Cantidad</th>
                                        </tr>
                                        </thead>
                                        <tbody id="verOrdenDeCompra">
                                        
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
<?php
$borrarOrdenCompra = new ControllerOrdenesCompras();
$borrarOrdenCompra->ctrBorrarOrdenCompra();
?>