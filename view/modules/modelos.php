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
            Administrar modelos
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar modelos</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarModelo">
                    Agregar modelo
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped tablas">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Modelo</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $item = null;
                    $valor = null;
                    $modelos = ControllerModelos::ctrMostrarModelos($item, $valor);

                    foreach ($modelos as $key => $value) {
                        echo '<tr>
                        <td>' . ($key + 1) . '</td>
                        <td>' . $value["modelo"] . '</td>
                        <td>
                            <div class="btn-group">                  
                                <button class="btn btn-warning btnEditarModelo" idModelo="' . $value["id_modelo"] . '" data-toggle="modal" data-target="#modalEditarModelo"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-danger btnEliminarModelo" idModelo="' . $value["id_modelo"] . '"><i class="fa fa-times"></i></button>
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
MODAL AGREGAR MODELO
======================================-->
<div id="modalAgregarModelo" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formModelo" role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar modelo</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="input-group">

                                <label for="modelo" style="color: red;"> * <small style="color: #000;">Modelo:</small>
                                    <input type="text" class="form-control input-lg" id="modelo" name="nuevoModelo"
                                           placeholder="Ingresar el modelo" required>
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
                $crearModelo = new ControllerModelos();
                $crearModelo -> ctrCrearModelo();
                ?>
            </form>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR MODELO
======================================-->
<div id="modalEditarModelo" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar modelo</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="input-group">
                                <label><small style="color: #000;">Modelo:</small>
                                    <input type="text" class="form-control input-lg" name="editarModelo"
                                           id="editarModelo" required>
                                    <input type="hidden" name="idModelo" id="idModelo" required>
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
                $editarModelo = new ControllerModelos();
                $editarModelo -> ctrEditarModelo();
                ?>
            </form>
        </div>
    </div>
</div>
<?php
$borrarModelo = new ControllerModelos();
$borrarModelo -> ctrBorrarModelo();
?>