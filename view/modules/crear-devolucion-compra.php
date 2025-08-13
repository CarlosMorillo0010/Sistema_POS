<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Devolución de Compra
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Devolución de compra</li>
        </ol>

    </section>
    <section class="content">
        <form class="formularioDevolucionCompra" role="form" method="post">
            <div class="box" style="border-top-color: #3c8dbc;">
                <div class="box-body">
                    <div class="col-lg-12">
                        <div class="form-group" style="display: flex;">
                            <div class="input-group">
                                <input type="hidden" class="form-control" name="Usuario" readonly="readonly"
                                       value="<?php echo $_SESSION['nombre']; ?>">
                                <input type="hidden" name="idVendedor"
                                       value="<?php echo $_SESSION['id_usuario']; ?>">
                            </div>
                            <div class="col-lg-2 input-group" style="margin: 0 10px 0 0;">
                                <label><small style="color: #000;">Nº Nota Credito:</small></label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                <label> <small style="color: #000;">Fecha de Nota Credito:</small></label>
                                <input type="date" class="form-control">
                            </div>

                            <div class="input-group" style="margin: 0 10px 0 0;">
                                <label></label>
                                <button type="button" class="btn btn-primary btnAgregarDevolucionCompra"
                                        style="width: 100%;">
                                    Agregar proveedor
                                </button>
                            </div>
                        </div>
                        <div class="form-group nuevaDevolucionCompraProveedores" style="display: flex;">

                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="box-body" style="height: 230px; overflow: auto;">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th style="width: 10%"></th>
                                        <th style="width: 10%">Codigo</th>
                                        <th style="width: 50%">Nombre producto</th>
                                        <th style="width: 10%">Cantidad</th>
                                        <th style="width: 10%">Precio</th>
                                    </tr>
                                    </thead>
                                    <tbody class="DevolucionCompraProductos">

                                    </tbody>
                                </table>
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
                                        <input type="text" class="form-control" name="subTotalNotaEntregaCompra" id="subTotalNotaEntregaCompra"
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
                    <button class="btn btn-primary">Crear Devolución de Compra</button>
                </div>
            </div>
        </form>
    </section>
</div>
