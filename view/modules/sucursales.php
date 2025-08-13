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
            Administrar Sucursales
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar sucursales</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarSucursal">
                    Agregar Sucursal
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped tablas">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $item = null;
                    $valor = null;
                    $sucursales = ControllerSucursales::ctrMostrarSucursal($item, $valor);

                    foreach ($sucursales as $key => $value) {
                        echo '<tr>
                        <td>' . ($key + 1) . '</td>
                        <td>' . $value["codigo"] . '</td>
                        <td>' . $value["nombre"] . '</td>
                        <td>
                          <div class="btn-group">
                            <button class="btn btn-warning btnEditarSucursal" idSucursal="' . $value["id_sucursal"] . '" data-toggle="modal" data-target="#modalEditarSucursal"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger btnEliminarSucursal" idSucursal="' . $value["id_sucursal"] . '"><i class="fa fa-times"></i></button>
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
MODAL AGREGAR DIVISA
======================================-->

<div id="modalAgregarSucursal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formSucursales" role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar sucursal</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA SUCURSALES -->
                        <div class="col-lg-4 form-group">
                            <div class="input-group">
                                <label style="color: red;">* <small style="color: #000;">Codigo:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoCodigo" maxlength="4"
                                           placeholder="Codigo" required>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-8 form-group">
                            <div class="input-group">
                                <label for="sucursales" style="color: red;">* <small
                                            style="color: #000;">Sucursal:</small>
                                    <input type="text" class="form-control input-lg" id="sucursal" name="nuevoNombre"
                                           placeholder="Ingresar Sucursal" required>
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
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>

                <?php
                $crearSucursal = new ControllerSucursales();
                $crearSucursal->ctrCrearSucursal();
                ?>
            </form>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR DIVISA
======================================-->
<div id="modalEditarSucursal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar sucursal</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">

                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="col-lg-3 form-group izquierda">
                            <div class="input-group">
                                <label> <small style="color: #000;">Codigo:</small>
                                    <input type="text" class="form-control input-lg" name="editarCodigo"
                                           id="editarCodigo"
                                           required>
                                    <input type="hidden" name="idSucursal" id="idSucursal" required>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-9 form-group derecha">
                            <div class="input-group">
                                <label> <small style="color: #000;">Nombre:</small>
                                    <input type="text" class="form-control input-lg" name="editarNombre"
                                           id="editarNombre" required>
                                    <input type="hidden" name="idNombre" id="idSucursal" required>
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
                $editarSucursal = new ControllerSucursales();
                $editarSucursal->ctrEditarSucursal();
                ?>

            </form>
        </div>
    </div>
</div>
<?php
$borrarSucursal = new ControllerSucursales();
$borrarSucursal->ctrBorrarSucursal();
?>