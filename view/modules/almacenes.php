<?php
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value):
    if ($_SESSION["perfil"] == $value["perfil"] && $value["mantenimiento"] == "N"){
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
            Administrar Almacenes
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar almacenes</li>
        </ol>

    </section>
    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarAlmacen">
                    Agregar Alamacen
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $item = null;
                        $valor = null;
                        $almacen = ControllerAlmacenes::ctrMostrarAlmacen($item, $valor);

                        foreach ($almacen as $key => $value):
                            echo
                                '<tr>
                                    <td>'.($key+1).'</td>
                                    <td class="text-uppercase">'.$value["nombre"].'</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-warning btnEditarAlmacen" idAlmacen="'.$value["id_almacen"].'" data-toggle="modal" data-target="#modalEditarAlmacen"><i class="fa fa-pencil"></i></button>
                                            <button class="btn btn-danger btnEliminarAlmacen" idAlmacen="'.$value["id_almacen"].'"><i class="fa fa-times"></i></button>
                                        </div>
                                    </td>
                                </tr>';
                        endforeach;
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!--=====================================
MODAL AGREGAR PERFIL
======================================-->
<div id="modalAgregarAlmacen" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formAlmacen" role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar almacen</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="input-group">
                                <label for="almacen" style="color: red;">* <small style="color: #000;">Nombre almacen:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoAlmacen" id="almacen" placeholder="Ingresar almacen" required>
                                </label>
                            </div>
                        </div>
                    </div>
                    <span style="color: red;">( * ) Campo obligatorio</span>
                </div>

                <!--=====================================
                PIE DEL MODAL
                ======================================-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php
                $crearAlmacen = new ControllerAlmacenes();
                $crearAlmacen -> ctrCrearAlmacen();
                ?>
            </form>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR PERFIL
======================================-->
<div id="modalEditarAlmacen" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar almacen</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="input-group">
                                <label> <small style="color: #000;">Nombre almacen:</small>
                                    <input type="text" class="form-control input-lg" name="editarAlmacen" id="editarAlmacen" required>
                                    <input type="hidden" name="idAlmacen" id="idAlmacen" required>
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
                $editarAlmacen = new ControllerAlmacenes();
                $editarAlmacen -> ctrEditarAlmacen();
                ?>
            </form>
        </div>
    </div>
</div>
<?php
$borrarAlmacen = new ControllerAlmacenes();
$borrarAlmacen -> ctrBorrarAlmacen();
?>
