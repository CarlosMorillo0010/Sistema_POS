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
            Administrar Impuestos
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar impuestos</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <!-- <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarImpuesto">
                    Agregar Impuesto
                </button>
            </div> -->
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Nombre</th>
                        <th>Porcentaje</th>
                        <th>Porcentaje adicional</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $item = null;
                    $valor = null;
                    $impuestos = ControllerImpuestos::ctrMostrarImpuesto($item, $valor);

                    foreach ($impuestos as $key => $value) {
                        echo '<tr>
                                <td>' . ($key + 1) . '</td>
                                <td>' . $value["impuesto"] . '</td>
                                <td>' . $value["tasa"] . ' % ' . '</td>
                                <td>' . $value["tasa_adicional"] . ' % ' . '</td>
                                <td>
                                      <div class="btn-group">
                                        <button class="btn btn-warning btnEditarImpuesto" idImpuesto="' . $value["id_impuesto"] . '" data-toggle="modal" data-target="#modalEditarImpuesto"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-danger btnEliminarImpuesto" idImpuesto="' . $value["id_impuesto"] . '"><i class="fa fa-times"></i></button>
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
MODAL AGREGAR IMPUESTO
======================================-->
<div id="modalAgregarImpuesto" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formImpuestos" role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar impuesto</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="col-lg-9 form-group impuesto">
                            <div class="input-group">
                                <label for="impuestos" style="color: red;">* <small style="color: #000;">Impuesto:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoImpuesto" id="impuestos" placeholder="Ingresar impuesto" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group porcentaje">
                            <div class="input-group">
                                <label for="porcentaje" style="color: red;">* <small style="color: #000;">Porcentaje:</small>
                                    <input type="number" id="porcentaje" step="any" class="form-control input-lg" name="nuevoPorcentaje" step="0.1" placeholder="1.0" required>
                                </label>
                            </div>
                        </div>
                        <span style="color: red;">( * ) Campo obligatorio</span>
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
            $crearImpuesto = new ControllerImpuestos();
            $crearImpuesto->ctrCrearImpuesto();
            ?>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR IMPUESTO
======================================-->
<div id="modalEditarImpuesto" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar impuesto</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="col-lg-9 form-group impuesto">
                                <div class="input-group">
                                    <label> <small style="color: #000;">Impuesto:</small>
                                        <input type="text" class="form-control input-lg" name="editarImpuesto"
                                               id="editarImpuesto" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                        <input type="hidden" name="idImpuesto" id="idImpuesto" required>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-3 form-group porcentaje">
                                <div class="input-group">
                                    <label> <small style="color: #000;">Porcentaje:</small>
                                        <input type="number" class="form-control input-lg" name="editarPorcentaje"
                                               id="editarPorcentaje" step="0.1" required>
                                        <input type="hidden" name="idPorcentaje" id="idImpuesto" required>
                                    </label>
                                </div>
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
                $editarImpuesto = new ControllerImpuestos();
                $editarImpuesto->ctrEditarImpuesto();
                ?>
            </form>
        </div>
    </div>
</div>
<?php
$borrarImpuesto = new ControllerImpuestos();
$borrarImpuesto->ctrBorrarImpuesto();
?>