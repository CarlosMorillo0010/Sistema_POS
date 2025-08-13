<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Presupuesto de Venta
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Presupuesto de Venta</li>
        </ol>

    </section>
    <section class="content">
        <form class="formularioPresupuesto" role="form" method="post">
            <div class="box" style="border-top-color: #3c8dbc;">
                <div class="box-header with-border" style="text-align: end;display: flex;align-items: center;">
                    <h4>Número de Control</h4>
                    <?php
                    $item = null;
                    $valor = null;
                    $presupuestos = ControllerPresupuestos::ctrMostrarPresupuesto($item, $valor);

                    if (!$presupuestos) {
                        $length = 10;
                        $string = "1";
                        echo '
                            <input type="text" style="color: red;font-weight: bold;font-size: 18px;width: 150px;background-color: transparent;height: 0px;border: 0;" class="form-control input-lg" name="nuevoCodigoPresupuesto" id="nuevoCodigoPresupuesto" readonly="readonly" value="' . str_pad($string, $length, 0, STR_PAD_LEFT) . '">
                        ';
                    } else {
                        foreach ($presupuestos as $key => $value) {

                        }
                        $length = 10;
                        $codigo = str_pad($value["codigo"] + 1, $length, 0, STR_PAD_LEFT);
                        echo '<input type="text" style="color: red;font-weight: bold;font-size: 18px;width: 150px;background-color: transparent;height: 0px;border: 0;" class="form-control input-lg" name="nuevoCodigoPresupuesto" id="nuevoCodigoPresupuesto" readonly="readonly" value="' . $codigo . '">';
                    }
                    ?>
                </div>
                <div class="box-body">
                    <div class="col-lg-12">
                        <div class="form-group" style="display: flex;">
                            <div class="col-lg-4 input-group" style="margin: 0 10px 0 0;">
                                <label> <small style="color: #000;">Vendedor:</small>
                                    <input type="text" class="form-control" readonly="readonly" name="vendedorPresupuesto"
                                           value="<?php echo $_SESSION['nombre']; ?>">
                                </label>
                                <input type="hidden" name="idVendedor"
                                       value="<?php echo $_SESSION['id_usuario']; ?>">
                            </div>
                            <div class="col-lg-2 input-group" style="padding-right: 10px;">
                                <label> <small style="color: #000;">Fecha de Registro:</small></label>
                                <input type="date" class="form-control" readonly name="fechaRegistro" id="fechaRegistro"
                                       value="<?php date_default_timezone_set('America/Caracas'); echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-lg-2 input-group" style="margin: 0 10px 0 0;">
                                <label> <small style="color: #000;">Fecha de Vencimiento:</small></label>
                                <input type="text" class="form-control datepicker" name="nuevaFechaVencimiento" id="nuevaFechaVencimiento" placeholder="Ingresar Fecha">
                            </div>
                            <div class="input-group" style="margin: 0 10px 0 0;">
                                <label></label>
                                <button type="button" class="btn btn-primary btnClientePresupuesto" style="width: 100%;">
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
                        <div class="form-group nuevoCliente" style="display: flex;">

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
                                        <th style="width: 10%">Precio</th>
                                        <th style="width: 10%">I.V.A</th>
                                        <th style="width: 10%">Cantidad</th>
                                        <th style="width: 10%">Precio Total</th>
                                    </tr>
                                    </thead>
                                    <tbody class="nuevoProductoPresupuesto">

                                    </tbody>
                                </table>
                                <input type="hidden" id="listarProductos" name="listarProductos">
                                <button type="button" class="btn btn-default btnAgregarPresupuesto"
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
                                        <input type="text" class="form-control" name="total__Presupuesto" id="total__Presupuesto" value="0.00" required readonly style="font-size: 30px;font-family: 'ds-digital'; color: #444;width: 100%;">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary">Crear Presupuesto</button>
                </div>
            </div>
        </form>
    </section>
</div>
<?php
$crearPresupuesto = new ControllerPresupuestos();
$crearPresupuesto->ctrCrearPresupuesto();
?>