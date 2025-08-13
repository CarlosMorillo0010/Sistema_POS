<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Administrar Merma
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar merma</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarMerma">
                    Agregar Merma
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped tablas">
                    <thead>
                    <tr>
                        <th style="width: 10px;">#</th>
                        <th>Código</th>
                        <th>Pérdida Total</th>
                        <th>Recuento de ingredientes</th>
                        <th>Persona responsable</th>
                        <th>Nota</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>00000001</td>
                        <td>20.00</td>
                        <td>1</td>
                        <td>Administrador</td>
                        <td>Lorem Ipsum dolor sit amet Lorem Ipsum dolor sit amet Lorem Ipsum dolor sit amet</td>
                        <td>2020-09-30 17:24:32</td>
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
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal Registrar Producto -->
<div id="modalAgregarMerma" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formMerma" role="form" method="post">
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Agregar Merma</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <!-- Codigo -->
                        <div class="form-group col-lg-4 izquierda">
                            <div class="input-group">
                                <label for="codigo" style="color: red;">* <small
                                            style="color: #000;">Codigo:</small></label>
                                <input type="text" class="form-control input-lg" name="nuevoCodigo" id="codigo"
                                       placeholder="Ingresar Codigo" required>
                            </div>
                        </div>
                        <!-- Fecha -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label for="fecha" style="color: red;">* <small style="color: #000;">Fecha:</small>
                                    <input type="date" class="form-control input-lg" required readonly
                                           value="<?php echo date('Y-m-d'); ?>">
                                </label>
                            </div>
                        </div>
                        <!-- Responsable -->
                        <div class="form-group col-lg-4 derecha">
                            <div class="input-group">
                                <label for="responsable" style="color: red;">* <small style="color: #000;">Persona
                                        responsable:</small>
                                    <select class="form-control input-lg" name="nuevoGenero">
                                        <option value="">Seleccione una opción</option>
                                        <option value="">Carlos Morillo</option>
                                        <option value="">Fabian Ferreira</option>
                                        <option value="">Jhon Sandoval</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <!-- NOTA -->
                        <div class="form-group col-lg-12 izquierda derecha">
                            <div class="input-group">
                                <label for="nota" style="color: red;">* <small style="color: #000;">Nota:</small>
                                    <textarea class="form-control input-lg" style="resize: none;" placeholder="Nota"></textarea>
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-12 form-group izquierda derecha">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Agregar ingredientes</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body" style="border-top: 0;height: 200px; overflow: auto;">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%"></th>
                                            <th style="width: 50%">Ingrediente</th>
                                            <th style="width: 15%">Cantidad</th>
                                            <th style="width: 15%">Pérdida</th>
                                            <th style="width: 15%">Pérdida Total</th>
                                        </tr>
                                        </thead>
                                        <tbody class="nuevaTablaMerma"></tbody>
                                    </table>
                                    <input type="hidden" id="listaMerma" name="listaMerma" required>
                                    <button type="button" class="btn btn-default btnAgregarMerma"
                                            style="margin: 10px 0 0 0;">Agregar ingredientes
                                    </button>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                        <span class="col-lg-12" style="color: red;">( * ) Campos obligatorios</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php
                $crearUsuario = new ControllerUsers();
                $crearUsuario->ctrCrearUsuario();
                ?>
            </form>
        </div>
    </div>
</div>
