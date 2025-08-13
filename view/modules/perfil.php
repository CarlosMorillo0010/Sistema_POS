<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Administrar Perfiles
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar perfiles</li>
        </ol>

    </section>
    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarPerfil">
                    Agregar Perfiles
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Perfiles</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $item = null;
                    $valor = null;
                    $perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);

                    foreach ($perfil as $key => $value):
                        echo '
                            <tr>
                        <td>'.($key+1).'</td>
                        <td class="text-uppercase">'.$value["perfil"].'</td>
                        
                    <td>
                            <div class="btn-group">
                                <button class="btn btn-warning btnEditarPerfil" idPerfil="'.$value["id_perfil"].'" data-toggle="modal" data-target="#modalEditarPerfil"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-danger btnEliminarPerfil" idPerfil="'.$value["id_perfil"].'"><i class="fa fa-times"></i></button>
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

<div id="modalAgregarPerfil" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->

                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar perfil</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->

                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="input-group">
                                <label style="color: red;">* <small style="color: #000;">Perfil:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoPerfil" placeholder="Ingresar perfil" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
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
                    $crearPerfil = new ControllerPerfiles();
                    $crearPerfil -> ctrCrearPerfil();
                ?>
            </form>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR PERFIL
======================================-->

<div id="modalEditarPerfil" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar perfil</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->

                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="input-group">
                                <label> <small style="color: #000;">Perfil:</small>
                                    <input type="text" class="form-control input-lg" name="editarPerfil" id="editarPerfil" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                <input type="hidden" name="idPerfil" id="idPerfil" required>
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
                    $editarPerfil = new ControllerPerfiles();
                    $editarPerfil -> ctrEditarPerfil();
                ?>
            </form>
        </div>
    </div>
</div>

<?php
$borrarPerfil = new ControllerPerfiles();
$borrarPerfil -> ctrBorrarPerfil();
?>





