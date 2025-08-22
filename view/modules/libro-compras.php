<div class="content-wrapper">
    <section class="content-header">
        <h1>Libro de compras</h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Libro de compras</li>
        </ol>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarLibroCompra">
                    Agregar
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
                    <thead>
                        <tr>
                            <th class="dt-center" style="width:5%">#</th>
                            <th class="dt-center">Nº factura</th>
                            <th class="dt-center">Nº control</th>
                            <th class="dt-center">Proveedor</th>
                            <th class="dt-center">Tipo Doc.</th>
                            <th class="dt-center">Monto</th>
                            <th class="dt-center">IVA</th>
                            <th class="dt-center">Total</th>
                            <th class="dt-center">Estado</th>
                            <th class="dt-center">Fecha</th>
                            <th class="dt-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $item = null;
                        $valor = null;
                        $libroCompra = ControllerLibroCompras::ctrMostrarLibroCompras($item, $valor);

                        foreach ($libroCompra as $key => $value) {
                            echo '
                                <tr>
                                    <td class="dt-center">' . ($key + 1) . '</td>
                                    <td class="dt-center">' . $value["numfactura"] . '</td>
                                    <td class="dt-center">' . $value["numcontrol"] . '</td>
                                    <td class="dt-center">' . $value["proveedor"] . '</td>
                                    <td class="dt-center">' . $value["tipodoc"] . '</td>
                                    <td class="dt-center">' . number_format($value["monto"], 2, ',', '.') . '</td>
                                    <td class="dt-center">' . number_format($value["iva"], 2, ',', '.') . '</td>
                                    <td class="dt-center">' . number_format($value["total"], 2, ',', '.') . '</td>';

                                    if ($value["estado"] == "Pendiente") {
                                        echo '<td class="dt-center"><button class="btn btn-danger btn-xs">Pendiente</button></td>';
                                    } else {
                                        echo '<td class="dt-center"><button class="btn btn-success btn-xs">Pagada</button></td>';
                                    }

                                    echo '<td class="dt-center">' . $value["fecha"] . '</td>
                                    <td class="dt-center">
                                        <div class="btn-group">
                                            <button class="btn btn-warning btnEditarLibroCompra" idLibroCompra="' . $value["id"] . '" data-toggle="modal" data-target="#modalEditarLibroCompra"><i class="fa fa-pencil"></i></button>
                                            <button class="btn btn-danger btnEliminarLibroCompra" idLibroCompra="' . $value["id"] . '"><i class="fa fa-times"></i></button>
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

<!--=====================================
MODAL AGREGAR REGISTRO
======================================-->
<div id="modalAgregarLibroCompra" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Agregar registro</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-6 mb-2">
                                    <label for="nuevoNumFactura">Número de factura:</label>
                                    <input type="text" class="form-control input-lg" name="nuevoNumFactura"
                                        id="nuevoNumFactura" placeholder="Ingresar número de factura" required>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="nuevoNumControl">Número de control:</label>
                                    <input type="text" class="form-control input-lg" name="nuevoNumControl"
                                        id="nuevoNumControl" placeholder="Ingresar número de control">
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="nuevoProveedor">Proveedor:</label>
                                    <select class="form-control input-lg" id="nuevoProveedor" name="nuevoProveedor"
                                        required>
                                        <option value="">Seleccione el proveedor</option>
                                        <?php
                                        $proveedores = ControllerProveedores::ctrMostrarProveedores(null, null);
                                        foreach ($proveedores as $key => $value) {
                                            echo '<option value="' . $value["nombre"] . '" data-id_proveedor="' . $value["id_proveedor"] . '">' . $value["nombre"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="nuevoRif">RIF del proveedor:</label>
                                    <input type="text" class="form-control input-lg" name="nuevoRif" id="nuevoRif"
                                        placeholder="Ingresar RIF" required readonly>
                                </div>

                                <input type="hidden" id="diasCredito" name="diasCredito" value="0">

                                <div class="col-md-6 mb-2">
                                    <label for="nuevoTipoDoc">Tipo de documento:</label>
                                    <select class="form-control input-lg" name="nuevoTipoDoc" id="nuevoTipoDoc">
                                        <option value="Factura">Factura</option>
                                        <option value="Nota de Débito">Nota de Débito</option>
                                        <option value="Nota de Crédito">Nota de Crédito</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="nuevaDescripcion">Descripción:</label>
                                    <textarea class="form-control input-lg" name="nuevaDescripcion"
                                        id="nuevaDescripcion" placeholder="Ingresar descripción"></textarea>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="nuevoMonto">Monto:</label>
                                    <input type="number" step="any" class="form-control input-lg" name="nuevoMonto"
                                        id="nuevoMonto" placeholder="Ingresar monto" required>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="nuevoIva">IVA:</label>
                                    <input type="number" step="any" class="form-control input-lg" name="nuevoIva"
                                        id="nuevoIva" placeholder="Ingresar IVA" required>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="nuevoTotal">Total:</label>
                                    <input type="number" step="any" class="form-control input-lg" name="nuevoTotal"
                                        id="nuevoTotal" placeholder="Total" required>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="nuevoMetodo">Método de pago:</label>
                                    <select class="form-control input-lg" id="nuevoMetodo" name="nuevoMetodo" required>
                                        <option value="">Seleccione el metodo</option>
                                        <?php
                                        $pagos = ControllerPagos::ctrMostrarPago(null, null);
                                        foreach ($pagos as $key => $value) {
                                            echo '<option value="' . $value["forma_pago"] . '">' . $value["forma_pago"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="nuevaFecha">Fecha:</label>
                                    <input type="date" class="form-control input-lg" name="nuevaFecha" id="nuevaFecha"
                                        required>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="nuevoEstado">Estado:</label>
                                    <select class="form-control input-lg" name="nuevoEstado" id="nuevoEstado">
                                        <option value="Pagado">Pagada</option>
                                        <option value="Pendiente">Pendiente</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <label for="nuevaObservacion">Observaciones:</label>
                                    <textarea class="form-control input-lg" name="nuevaObservacion"
                                        id="nuevaObservacion" placeholder="Observaciones"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar registro</button>
                </div>
                <?php
                $crearLibroCompra = new ControllerLibroCompras();
                $crearLibroCompra->ctrCrearLibroCompra();
                ?>
            </form>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR REGISTRO
======================================-->
<div id="modalEditarLibroCompra" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Editar registro</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-6 mb-2">
                                    <label for="editarNumFactura">Número de factura:</label>
                                    <input type="text" class="form-control input-lg" name="editarNumFactura"
                                        id="editarNumFactura" required>
                                    <!-- Input oculto para guardar el ID del registro a editar -->
                                    <input type="hidden" id="idLibroCompra" name="idLibroCompra">
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="editarNumControl">Número de control:</label>
                                    <input type="text" class="form-control input-lg" name="editarNumControl"
                                        id="editarNumControl" placeholder="Ingresar número de control">
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="editarProveedor">Proveedor:</label>
                                    <select class="form-control input-lg" id="editarProveedor" name="editarProveedor"
                                        required>
                                        <option value="">Seleccione el proveedor</option>
                                        <?php
                                        $proveedores = ControllerProveedores::ctrMostrarProveedores(null, null);
                                        foreach ($proveedores as $key => $value) {
                                            echo '<option value="' . $value["nombre"] . '" data-id_proveedor="' . $value["id_proveedor"] . '">' . $value["nombre"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="editarRif">RIF del proveedor:</label>
                                    <input type="text" class="form-control input-lg" name="editarRif" id="editarRif"
                                        placeholder="Ingresar RIF" required readonly>
                                </div>

                                <input type="hidden" id="editarDiasCredito" name="editarDiasCredito" value="0">

                                <div class="col-md-6 mb-2">
                                    <label for="editarTipoDoc">Tipo de documento:</label>
                                    <select class="form-control input-lg" name="editarTipoDoc" id="editarTipoDoc"
                                        required>
                                        <option value="Factura">Factura</option>
                                        <option value="Nota de Débito">Nota de Débito</option>
                                        <option value="Nota de Crédito">Nota de Crédito</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="editarDescripcion">Descripción:</label>
                                    <textarea class="form-control input-lg" name="editarDescripcion"
                                        id="editarDescripcion"></textarea>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="editarMonto">Monto:</label>
                                    <input type="number" step="any" class="form-control input-lg" name="editarMonto"
                                        id="editarMonto" required>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="editarIva">IVA:</label>
                                    <input type="number" step="any" class="form-control input-lg" name="editarIva"
                                        id="editarIva" required>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="editarTotal">Total:</label>
                                    <input type="number" step="any" class="form-control input-lg" name="editarTotal"
                                        id="editarTotal" readonly required>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="editarMetodoSelect">Método de pago:</label>
                                    <select class="form-control input-lg" name="editarMetodo" id="editarMetodoSelect"
                                        required>
                                        <option value="" id="editarMetodo"></option>
                                        <?php
                                        $pagos = ControllerPagos::ctrMostrarPago(null, null);
                                        foreach ($pagos as $key => $value) {
                                            echo '<option value="' . $value["forma_pago"] . '">' . $value["forma_pago"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="editarFecha">Fecha:</label>
                                    <input type="date" class="form-control input-lg" name="editarFecha" id="editarFecha"
                                        required>
                                </div>


                                <div class="col-md-4 mb-2">
                                    <label for="editarEstado">Estado:</label>
                                    <select class="form-control input-lg" name="editarEstado" required>
                                        <option id="editarEstado"></option>
                                        <option value="Pagado">Pagada</option>
                                        <option value="Pendiente">Pendiente</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <label for="editarObservacion">Observaciones:</label>
                                    <textarea class="form-control input-lg" name="editarObservacion"
                                        id="editarObservacion" placeholder="Observaciones"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
                <?php
                $editarLibroCompra = new ControllerLibroCompras();
                $editarLibroCompra->ctrEditarLibroCompra();
                ?>
            </form>
        </div>
    </div>
</div>
<?php
$borrarLibroCompra = new ControllerLibroCompras();
$borrarLibroCompra->ctrBorrarLibroCompra();
?>