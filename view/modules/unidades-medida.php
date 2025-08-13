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
            Administrar Und. de Medidas
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar unid. de medidas</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarUnidad">
                    Agregar Unid. de Medidas
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped tablas">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Nombre</th>
                        <th>Medida</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $item = null;
                    $valor = null;
                    $unidades = ControllerUnidades::ctrMostrarUnidad($item, $valor);

                    foreach ($unidades as $key => $value) {
                        echo '<tr>
            <td>' . ($key + 1) . '</td>
            <td>' . $value["nombre"] . '</td>
            <td>' . $value["unidad"] . '</td>
            <td>
              <div class="btn-group">
                <button class="btn btn-warning btnEditarUnidad" idUnidad="' . $value["id_unidad"] . '" data-toggle="modal" data-target="#modalEditarUnidad"><i class="fa fa-pencil"></i></button>
                <button class="btn btn-danger btnEliminarUnidad" idUnidad="' . $value["id_unidad"] . '"><i class="fa fa-times"></i></button>
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
MODAL AGREGAR UNIDADES DE MEDIDAS
======================================-->
<div id="modalAgregarUnidad" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formUnidadesMedidas" role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar Unid. de Medida</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="col-lg-8 form-group divisa">
                            <div class="input-group">
                                <label for="medida" style="color: red;">* <small style="color: #000;">Unidad de
                                        Medida:</small>
                                    <input type="text" style="text-transform: uppercase;"
                                           onkeyup="javascript:this.value=this.value.toUpperCase();"
                                           class="form-control input-lg" id="medida" name="nuevoNombre"
                                           placeholder="Ingresar Unidad de Medida" required>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-4 form-group simbolo">
                            <div class="input-group">
                                <label for="unidad" style="color: red;">* <small style="color: #000;">Simbolo:</small>
                                    <input type="text" style="text-transform: uppercase;"
                                           onkeyup="javascript:this.value=this.value.toUpperCase();"
                                           class="form-control input-lg" id="unidad" name="nuevaUnidad" placeholder="Kg"
                                           required>
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
                $crearUnidad = new ControllerUnidades();
                $crearUnidad->ctrCrearUnidad();
                ?>
            </form>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR UNIDADES DE MEDIDAS
======================================-->
<div id="modalEditarUnidad" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Und. de Medida</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">

                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="col-lg-9 form-group divisa">
                            <div class="input-group">
                                <label> <small style="color: #000;">Unidad de Medida:</small>
                                    <input type="text" style="text-transform: uppercase;"
                                           onkeyup="javascript:this.value=this.value.toUpperCase();"
                                           class="form-control input-lg" name="editarNombre" id="editarNombre"
                                           required>
                                    <input type="hidden" name="idUnidad" id="idUnidad" required>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group simbolo">
                            <div class="input-group">
                                <label> <small style="color: #000;">Simbolo:</small>
                                    <input type="text" style="text-transform: uppercase;"
                                           onkeyup="javascript:this.value=this.value.toUpperCase();"
                                           class="form-control input-lg" name="editarUnidad" id="editarUnidad"
                                           required>
                                    <input type="hidden" name="idMedida" id="idUnidad" required>
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
                $editarUnidad = new ControllerUnidades();
                $editarUnidad->ctrEditarUnidad();
                ?>

            </form>
        </div>
    </div>
</div>
<?php
$borrarUnidad = new ControllerUnidades();
$borrarUnidad->ctrBorrarUnidad();
?>