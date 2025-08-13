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
            Administrar bancos
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar bancos</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarBanco">
                    Agregar banco
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped tablas">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>banco</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $item = null;
                    $valor = null;
                    $bancos = ControllerBancos::ctrMostrarBanco($item, $valor);

                    foreach ($bancos as $key => $value) {
                        echo '<tr>
            <td>' . ($key + 1) . '</td>
            <td>' . $value["banco"] . '</td>
            <td>
              <div class="btn-group">
                <button class="btn btn-warning btnEditarBanco" idBanco="' . $value["id_banco"] . '" data-toggle="modal" data-target="#modalEditarBanco"><i class="fa fa-pencil"></i></button>
                <button class="btn btn-danger btnEliminarBanco" idBanco="' . $value["id_banco"] . '"><i class="fa fa-times"></i></button>
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
MODAL AGREGAR BANCO
======================================-->
<div id="modalAgregarBanco" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formBanco" role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar banco</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="input-group">
                                <label for="banco" style="color: red;">* <small style="color: #000;">Banco:</small>
                                    <input type="text" class="form-control input-lg" id="banco" name="nuevaBanco" placeholder="Ingresar banco" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
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
                $crearBanco = new ControllerBancos();
                $crearBanco->ctrCrearBanco();
                ?>
            </form>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR BANCO
======================================-->
<div id="modalEditarBanco" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar banco</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="input-group">
                                <label> <small style="color: #000;">Banco:</small>
                                    <input type="text" class="form-control input-lg" name="editarBanco" id="editarBanco" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                    <input type="hidden" name="idBanco" id="idBanco" required>
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
                $editarBanco = new ControllerBancos();
                $editarBanco->ctrEditarBanco();
                ?>
            </form>
        </div>
    </div>
</div>
<?php
$borrarBanco = new ControllerBancos();
$borrarBanco->ctrBorrarBanco();
?> 




