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
            Administrar Formas de Pago
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar formas de pago</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarPago">
                    Agregar forma de pago
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped tablas">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Formas de pago</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $item = null;
                        $valor = null;
                        $pagos = ControllerPagos::ctrMostrarPago($item, $valor);
                            foreach ($pagos as $key => $value) {
                                echo '<tr>
                                    <td>' . ($key + 1) . '</td>
                                    <td>' . $value["forma_pago"] . '</td>
                                    <td>
                                      <div class="btn-group">
                                        <button class="btn btn-warning btnEditarPago" idPago="' . $value["id_forma_pagos"] . '" data-toggle="modal" data-target="#modalEditarPago"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-danger btnEliminarPago" idPago="' . $value["id_forma_pagos"] . '"><i class="fa fa-times"></i></button>
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
MODAL AGREGAR PAGO
======================================-->
<div id="modalAgregarPago" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formFormasPago" role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar forma de pago</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="input-group">
                                <label for="pagos" style="color: red;">* <small style="color: #000;">Forma de Pago:</small>
                                    <input type="text" class="form-control input-lg" id="pagos" name="nuevaPago" onkeyup="javascript:this.value=this.value.toUpperCase();" placeholder="Ingresar Forma de Pago" required>
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
                $crearPago = new ControllerPagos();
                $crearPago->ctrCrearPago();
                ?>
            </form>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR PAGO
======================================-->
<div id="modalEditarPago" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar forma de pago</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="input-group">
                                <label> <small style="color: #000;">Forma de Pago:</small>
                                    <input type="text" class="form-control input-lg" name="editarPago" id="editarPago" required>
                                    <input type="hidden" name="idPago" id="idPago" required>
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
                $editarPago = new ControllerPagos();
                $editarPago->ctrEditarPago();
                ?>
            </form>
        </div>
    </div>
</div>
<?php
$borrarPago = new ControllerPagos();
$borrarPago->ctrBorrarPago();
?> 




