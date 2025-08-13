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
            Administrar Anticipos
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li>Administrar anticipos</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarAnticipo">
                    Crear Anticipo
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped tablas">
                    <thead>
                        <tr>
                            <th style="width:10px">#</th>
                            <th>Codigo</th>
                            <th>proveedor</th>
                            <th>Monto</th>
                            <th>Saldo</th>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                            $item = null;
                            $valor = null;
                            $anticipos = ControllerAnticipos::ctrMostrarAnticipos($item, $valor);

                            foreach ($anticipos as $key => $value) {
                                echo '<tr>
                                <td>' . ($key + 1) . '</td>
                                <td>' . $value["codigo"] . '</td>
                                <td>' . $value["proveedor"] . '</td>
                                <td>' . $value["monto"] . '</td>
                                <td>' . $value["saldo"] . '</td>
                                <td>' . $value["fecha"] . '</td>
                                <td>' . $value["descripcion"] . '</td>
                                <td>' . $value["estatus"] . '</td>
                                <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning btnEditarAnticipo" idAnticipo="' . $value["id_anticipo"] . '" data-toggle="modal" data-target="#modalEditarAnticipo"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-danger btnEliminarAnticipo" idAnticipo="' . $value["id_anticipo"] . '"><i class="fa fa-times"></i></button>
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
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!--=====================================
MODAL AGREGAR ANTICIPO
======================================-->
<div id="modalAgregarAnticipo" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar anticipo</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        
                        <!-- ENTRADA PARA DEL CODIGO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Codigo:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoCodigo"
                                        placeholder="Codigo" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL PROVEEDOR -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Proveedor:</small>
                                    <select class="form-control input-lg" id="nuevoProveedor" name="nuevoProveedor">
                                        <option></option>
                                            <?php
                                                $item = null;
                                                $valor = null;
                                                $proveedores = ControllerProveedores::ctrMostrarProveedores($item, $valor);

                                                foreach ($proveedores as $key => $value) {
                                                    echo '<option value="'.$value["proveedor"].'">'.$value["proveedor"].'</option>';
                                                }
                                            ?>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL MONTO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Monto:</small>
                                    <input type="number" class="form-control input-lg" name="nuevoMonto" min="0" step="any" placeholder="Monto"
                                            required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL SALDO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Saldo:</small>
                                    <input type="number" class="form-control input-lg" name="nuevoSaldo" min="0" step="any" placeholder="Saldo">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL FECHA-->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Fecha:</small>
                                    <input type="text" class="form-control input-lg" name="nuevaFecha"
                                        placeholder="Ingresar fecha" data-inputmask="'alias': 'yyyy/mm/dd'"
                                        data-mask required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL ESTATUS -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Estatus:</small>
                                    <select class="form-control input-lg" name="nuevoEstatus">
                                        <option value=""></option>
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Pagado">Pagado</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA LA DESCRIPCION -->
                        <div class="form-group col-lg-12">
                            <div class="input-group">
                                <small style="color: #000;">Descripcion:</small>
                                <input type="text" class="form-control input-lg" name="nuevaDescripcion"
                                        placeholder="Ingresar descripcion">
                            </div>
                        </div>

                    </div>

                    <span class="col-lg-12" style="color: red;">( * ) Campos obligatorios</span>

                </div>

                <!--=====================================
                PIE DEL MODAL
                ======================================-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>

            </form>

            <?php
                $crearAnticipo = new ControllerAnticipos();
                $crearAnticipo->ctrCrearAnticipo();
            ?>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR ANTICIPO
======================================-->
<div id="modalEditarAnticipo" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar anticipo</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        
                        <!-- ENTRADA PARA DEL CODIGO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Codigo:</small>
                                    <input type="text" class="form-control input-lg" id="editarCodigo" name="editarCodigo" readonly required>
                                </label>
                                <input type="hidden" name="idAnticipo" id="idAnticipo">
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL MONTO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Monto:</small>
                                    <input type="number" class="form-control input-lg" id="editarMonto"
                                        name="editarMonto" min="0" step="any" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL SALDO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Saldo:</small>
                                    <input type="number" class="form-control input-lg" id="editarSaldo"
                                        name="editarSaldo" min="0" step="any">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL ESTATUS -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Estatus:</small>
                                    <select class="form-control input-lg" name="editarEstatus">
                                        <option value=" " id="editarEstatus"></option>
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Pagado">Pagado</option>
                                    </select>
                                </label>
                            </div>
                        </div> 

                        <!-- ENTRADA PARA EL FECHA-->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Fecha:</small>
                                    <input type="text" class="form-control input-lg" id="editarFecha" name="editarFecha"
                                        placeholder="Ingresar fecha" data-inputmask="'alias': 'yyyy/mm/dd'"
                                        data-mask required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA LA DESCRIPCIÓN -->
                        <div class="form-group col-lg-12">
                            <div class="input-group">
                                <small style="color: #000;">Descripcion:</small>
                                <input type="text" class="form-control input-lg" name="editarDescripcion" id="editarDescripcion">
                            </div>
                        </div>

                    </div>

                    <span class="col-lg-12" style="color: red;">( * ) Campos obligatorios</span>

                </div>

                <!--=====================================
                PIE DEL MODAL
                ======================================-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
            <?php
                $editarAnticipo = new ControllerAnticipos();
                $editarAnticipo->ctrEditarAnticipo();
            ?>
        </div>
    </div>
</div>
<?php
    $eliminarAnticipo = new ControllerAnticipos();
    $eliminarAnticipo->ctrEliminarAnticipo();
?>



