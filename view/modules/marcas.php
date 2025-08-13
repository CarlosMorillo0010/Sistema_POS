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
            Administrar marcas
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar marcas</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarMarca">
                    Agregar marca
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped tablas">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Marcas</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $item = null;
                    $valor = null;
                    $marcas = ControllerMarcas::ctrMostrarMarca($item, $valor);

                    foreach ($marcas as $key => $value) {
                        echo '<tr>
                        <td>' . ($key + 1) . '</td>
                        <td>' . $value["marca"] . '</td>
                        <td>
                            <div class="btn-group">                  
                                <button class="btn btn-warning btnEditarMarca" idMarca="' . $value["id_marca"] . '" data-toggle="modal" data-target="#modalEditarMarca"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-danger btnEliminarMarca" idMarca="' . $value["id_marca"] . '"><i class="fa fa-times"></i></button>
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
MODAL AGREGAR MARCA
======================================-->
<div id="modalAgregarMarca" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formMarca" role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar marca</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="input-group">
                                <label for="marca" style="color: red;"> * <small style="color: #000;">Marca:</small>
                                    <input type="text" class="form-control input-lg" id="marca" name="nuevaMarca"
                                           placeholder="Ingresar Marca" required>
                                </label>
                            </div>
                        </div>
                    </div>
                    <span class="col-lg-12" style="color: red;">( * ) Campo obligatorio</span>
                </div>

                <!--=====================================
                PIE DEL MODAL
                ======================================-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php
                $crearMarca = new ControllerMarcas();
                $crearMarca->ctrCrearMarca();
                ?>
            </form>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR MARCA
======================================-->
<div id="modalEditarMarca" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar marca</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="input-group">
                                <label><small style="color: #000;">Marca:</small>
                                    <input type="text" class="form-control input-lg" name="editarMarca"
                                           id="editarMarca" required>
                                    <input type="hidden" name="idMarca" id="idMarca" required>
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
                $editarMarca = new ControllerMarcas();
                $editarMarca->ctrEditarMarca();
                ?>
            </form>
        </div>
    </div>
</div>
<?php
$borrarMarca = new ControllerMarcas();
$borrarMarca->ctrBorrarMarca();
?>