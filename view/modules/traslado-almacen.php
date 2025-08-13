<?php
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value):
    if ($_SESSION["perfil"] == $value["perfil"] && $value["inventario"] == "N"){
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
            Administrar Traslados Almacén
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar traslados almacén</li>
        </ol>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalTrasladoAlmacen">
                    Agregar Traslado Almacén
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Fecha</th>
                        <th>Almacén</th>
                        <th>Total ajuste</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>21/12/2021</td>
                        <td>Alpinos</td>
                        <td>15000</td>
                        <td>Mal estado</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-warning"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-danger"><i class="fa fa-times"></i></button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!--=====================================
MODAL AGREGAR TRASLADO ALMACEN
======================================-->
<div id="modalTrasladoAlmacen" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 95%;">
        <div class="modal-content">
            <form role="form" method="post" class="formularioTrasladoAlmacen">
                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar traslado almacén</h4>
                </div>
                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA LA FECHA -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Fecha:</small>
                                    <input type="date" class="form-control input-lg" name="nuevaFecha"
                                           value="<?php echo date("Y-m-d"); ?>">
                                </label>
                            </div>
                        </div>
                        <!-- ENTRADA PARA LA DESCRIPCION -->
                        <div class="form-group col-lg-9">
                            <div class="input-group">
                                <small style="color: #000;">Descripcion:</small>
                                <input type="text" class="form-control input-lg" name="nuevaDescripcion"
                                       placeholder="Ingresar descripcion">
                            </div>
                        </div>
                        <!-- ENTRADA PARA EL ALMACEN -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Almacén que Envia:</small>
                                    <select class="form-control input-lg" id="nuevoAlmacenEnvio"
                                            name="nuevoAlmacenEnvio">
                                        <option>Seleccionar almacén</option>
                                        <?php
                                        $item = null;
                                        $valor = null;
                                        $almacenes = ControllerAlmacenes::ctrMostrarAlmacen($item, $valor);

                                        foreach ($almacenes as $key => $value) {
                                            echo '<option value="' . $value["id_almacen"] . '">' . $value["nombre"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <!-- ENTRADA PARA EL ALMACEN -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Almacén que Recibe:</small>
                                    <select class="form-control input-lg" id="nuevoAlmacenRecibe"
                                            name="nuevoAlmacenRecibe">
                                        <option>Seleccionar almacén</option>
                                        <?php
                                        $item = null;
                                        $valor = null;
                                        $almacenes = ControllerAlmacenes::ctrMostrarAlmacen($item, $valor);

                                        foreach ($almacenes as $key => $value) {
                                            echo '<option value="' . $value["id_almacen"] . '">' . $value["nombre"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div>
                                <h4>Productos en venta</h4>
                            </div>
                            <div class="box box-primary">
                                <br>
                                <form role="form" metohd="post" style="margin-top: -15px;">
                                    <div>
                                        <div class="with-border">
                                            <!--=====================================
                                            ENTRADA PARA AGREGAR TRASLADO ALMACEN
                                            ======================================-->
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th class="col-lg-3" style="text-align: center">Producto</th>
                                                    <th class="col-lg-2" style="text-align: center">Cantidad actual</th>
                                                    <th class="col-lg-3" style="text-align: center">Tipo de ajuste</th>
                                                    <th class="col-lg-2" style="text-align: center">Cantidad</th>
                                                    <th class="col-lg-2" style="text-align: center">Cantidad final</th>
                                                </tr>
                                                </thead>
                                            </table>
                                            <!--=====================================
                                            ENTRADA PARA AGREGAR PRODUCTO
                                            ======================================-->
                                            <div class="form-group row nuevoProductoTraslado">

                                            </div>
                                            <!--=====================================
                                            BOTÓN PARA AGREGAR PRODUCTO
                                            ======================================-->
                                            <button type="button" class="btn btn-default btnTrasladoAlmacen"
                                                    style="margin-bottom: 15px;">Agregar producto
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--=====================================
                    PIE DEL MODAL
                    ======================================-->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

