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
            Administrar Servicios
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar servicio</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarServicio">
                    Agregar Servicio
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped tablas">
                    <thead>
                    <tr>
                        <th style="width: 10px;">#</th>
                        <th>Imagen</th>
                        <th>Servicio</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $item = null;
                    $valor = null;
                    $servicios = ControllerServices::ctrMostrarServicio($item, $valor);

                    foreach ($servicios as $key => $value) {
                        echo '<tr>
                        <td>' . ($key + 1) . '</td>
                        <td><img src="' . $value["imagen"] . '" class="img-thumbnail" width="40px"></td>
                        <td>' . $value["servicio"] . '</td>
                        <td>
                            <div class="btn-group">                  
                                <button class="btn btn-warning btnEditarServicio" idServicio="' . $value["id_servicio"] . '" data-toggle="modal" data-target="#modalEditarServicio"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-danger btnEliminarServicio" idServicio="' . $value["id_servicio"] . '"><i class="fa fa-times"></i></button>
                            </div>  
                        </td>
                    </tr>';
                    }
                    ?>
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

<!--=====================================
MODAL AGREGAR SERVICIO
======================================-->
<div id="modalAgregarServicio" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formServicio" role="form" method="post" enctype="multipart/form-data">
                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Agregar Servicio</h4>
                </div>
                <!--=====================================
               CUERPO DEL MODAL
               ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- Entrada para Nombre del Servicio -->
                        <div class="form-group">
                            <div class="input-group">
                                <label for="servicio" style="color: red;"> * <small
                                            style="color: #000;">Servicio:</small>
                                    <input type="text" class="form-control input-lg" id="service" name="nuevoServicio"
                                           placeholder="Servico" required>
                                </label>
                            </div>
                        </div>
                        <!-- Entrada para Subir Imagen -->
                        <div class="form-group">
                            <div class="panel">SUBIR IMAGEN</div>
                            <input type="file" class="nuevaImagen" name="nuevaImagen">
                            <p class="help-block">Peso máximo de la imgen 2MB</p>
                            <img src="view/img/services/default/anonymous.png" class="img-thumbnail mostrar"
                                 width="100px">
                        </div>
                        <span class="col-lg-12" style="color: red;">( * ) Campo obligatorio</span>
                    </div>
                </div>
                <!--=====================================
                PIE DEL MODAL
                ======================================-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
            <?php
            $crearServicio = new ControllerServices();
            $crearServicio->ctrCrearServicio();
            ?>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR SERVICIO
======================================-->
<div id="modalEditarServicio" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post">
                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar servicio</h4>
                </div>
                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="input-group">
                                <label><small style="color: #000;">Servicio:</small>
                                    <input type="text" class="form-control input-lg" name="editarServicio"
                                           id="editarServicio" required>
                                    <input type="hidden" name="idServicio" id="idServicio" required>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!--=====================================
                PIE DEL MODAL
                ======================================-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php
                $editarServicio = new ControllerServices();
                $editarServicio->ctrEditarServicio();
                ?>
            </form>
        </div>
    </div>
</div>
<?php
$borrarServicio = new ControllerServices();
$borrarServicio->ctrBorrarServicio();
?>