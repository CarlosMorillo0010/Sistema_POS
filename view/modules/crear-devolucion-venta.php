<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Crear Devolución de Venta
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Crear Devolución de Venta</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <!-- El formulario -->
            <div class="col-lg-7 col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border"></div>
                    <form role="form" method="post" class="formularioDevolucionVenta">
                        <div class="box-body">
                            <div class="box">
                                <input type="hidden" name="idVenta" id="idVenta">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input type="text" class="form-control" value="<?php echo $_SESSION['nombre']; ?>" readonly>
                                        <input type="hidden" name="idVendedor" value="<?php echo $_SESSION['id_usuario']; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                        <input type="text" class="form-control" name="nuevaDevolucion" placeholder="Código de Devolución" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                        <select class="form-control select2" id="seleccionarCliente" name="idCliente" required>
                                            <option value="">Seleccionar Cliente</option>
                                            <?php
                                            $clientes = ControllerClients::ctrMostrarClientes(null, null);
                                            foreach ($clientes as $key => $value) {
                                                echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row nuevoProducto">
                                    <!-- Aquí se cargarán los productos de la venta seleccionada -->
                                </div>

                                <input type="hidden" id="listaProductosDevolucion" name="listaProductosDevolucion">

                                <hr>

                                <div class="row">
                                    <div class="col-xs-12 pull-right">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Total Devolución</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                                                            <input type="text" class="form-control" id="montoTotalDevolucion" name="montoTotalDevolucion" placeholder="0.00" readonly required>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right">Guardar Devolución</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- La tabla de ventas -->
            <div class="col-lg-5 col-xs-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Seleccionar Venta</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped dt-responsive tablaVentas" width="100%">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>N° Venta</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>