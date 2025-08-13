<?php
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value):
    if ($_SESSION["perfil"] == $value["perfil"] && $value["compras"] == "N"){
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
            Administrar proveedores
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar proveedor</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProveedor">
                    Agregar proveedor
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Codigo</th>
                        <th>Tipo Persona</th>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $item = null;
                    $valor = null;
                    $proveedores = ControllerProveedores::ctrMostrarProveedores($item, $valor);

                    foreach ($proveedores as $key => $value) {
                        $length = 10;
                        echo '<tr>
                                <td>' . ($key + 1) . '</td>
                                <td>' . str_pad($value["codigo"], $length, 0, STR_PAD_LEFT) . '</td>
                                <td>' . $value["tipo_persona"] . '</td>
                                <td>' . $value["tipo_documento"] . $value["documento"] . ' </td>
                                <td>' . $value["nombre"] . '</td>
                                <td>' . $value["telefono"] . '</td>
                                <td>
                                <div class="btn-group">
                                    <button class="btn bg-dark btnVerProveedor" idProveedor="' . $value["id_proveedor"] . '" data-toggle="modal" data-target="#modalVerProveedor"><i class="fa fa-eye"></i></button>
                                    <button class="btn btn-warning btnEditarProveedor" idProveedor="' . $value["id_proveedor"] . '" data-toggle="modal" data-target="#modalEditarProveedor"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-danger btnEliminarProveedor" idProveedor="' . $value["id_proveedor"] . '"><i class="fa fa-times"></i></button>
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
MODAL AGREGAR PROVEEDOR
======================================-->
<div id="modalAgregarProveedor" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post">
                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar proveedor</h4>
                </div>
                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">

                        <!-- ENTRADA PARA EL CODIGO-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Código:</small>
                                    <?php
                                    $item = null;
                                    $valor = null;
                                    $proveedores = ControllerProveedores::ctrMostrarProveedores($item, $valor);

                                    if (!$proveedores) {
                                        $length = 10;
                                        $string = "1";
                                        echo '
                                                    <input type="text" class="text-center form-control input-lg" name="nuevoCodigo" id="nuevoCodigo" readonly="readonly" value="' . str_pad($string, $length, 0, STR_PAD_LEFT) . '">
                                                ';
                                    } else {
                                        foreach ($proveedores as $key => $value) {

                                        }
                                        $length = 10;
                                        $codigo = str_pad($value["codigo"] + 1, $length, 0, STR_PAD_LEFT);
                                        echo '
                                                <input type="text" class="text-center form-control input-lg" name="nuevoCodigo" id="nuevoCodigo" readonly="readonly" value="' . $codigo . '">
                                            ';
                                    }
                                    ?>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL TIPO DE PERSONA-->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Tipo de persona:</small>
                                    <select class="form-control input-lg" name="tipoPersona" id="tipoPersona" required>
                                        <option value=""></option>
                                        <option value="Natural">Natural</option>
                                        <option value="Juridica">Juridica</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- Tipo de Documento -->
                        <div class="form-group col-lg-2">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Nacionalidad:</small>
                                    <select class="form-control input-lg" name="tipoDocumento"
                                            id="tipoDocumento" required>
                                        <option value=""></option>
                                        <option value="V">V</option>
                                        <option value="J">J</option>
                                        <option value="G">G</option>
                                        <option value="C">C</option>
                                        <option value="E">E</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL DOCUMENTO-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Documento:</small>
                                    <input type="text" class="form-control input-lg" placeholder="Documento"
                                           name="numeroDocumento" id="numeroDocumento" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Ingresar nombre o razón social:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoProveedor"
                                           id="nuevoProveedor"
                                           placeholder="Ingresar nombre o razón social" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL TELEFONO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Teléfono:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoTelefono"
                                           placeholder="Teléfono" data-inputmask="'mask':'(9999) 999-99-99'"
                                           data-mask>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA DIAS DE CREDITO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Dias de Credito:</small>
                                    <input type="number" class="form-control input-lg" name="nuevoDiasCredito"
                                           placeholder="Dias de Credito">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL EMAIL -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Email:</small>
                                    <input type="email" class="form-control input-lg" name="nuevoEmail"
                                        placeholder="Correo electronico">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL DIRECION -->
                        <div class="form-group col-lg-12">
                            <div class="input-group">
                                <label><small style="color: #000;">Dirección:</small>
                                    <input type="text" class="form-control input-lg" name="nuevaDireccion"
                                           id="nuevaDireccion"
                                           placeholder="Ingresar dirección">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA NOTA -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <label><small style="color: #000;">Nota:</small>
                                        <textarea type="text" class="form-control input-lg" name="nuevaNota"
                                                  id="nuevaNota"
                                                  placeholder="Ingresar nota" style="resize: none;"></textarea>
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
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
            <?php
            $crearProveedor = new ControllerProveedores();
            $crearProveedor->ctrCrearProveedor();
            ?>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR PROVEEDOR
======================================-->
<div id="modalEditarProveedor" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post">
                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar proveedor</h4>
                </div>
                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">

                        <!-- ENTRADA PARA EL CODIGO-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Codigo:</small>
                                    <input type="text" class="text-center form-control input-lg" name="editarCodigo"
                                           id="editarCodigo"
                                           readonly required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL TIPO DE PERSONA-->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Tipo de persona:</small>
                                    <select class="form-control input-lg" name="editarTipoPersona" required>
                                        <option id="editarTipoPersona" value=""></option>
                                        <option value="Natural">Natural</option>
                                        <option value="Juridica">Juridica</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- Tipo de Documento -->
                        <div class="form-group col-lg-2">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Nacionalidad:</small>
                                    <select class="form-control input-lg" name="editarTipoDocumento" required>
                                        <option id="editarTipoDocumento" value=""></option>
                                        <option value="V">V</option>
                                        <option value="J">J</option>
                                        <option value="G">G</option>
                                        <option value="C">C</option>
                                        <option value="E">E</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL DOCUMENTO-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Documento:</small>
                                    <input type="text" class="form-control input-lg" placeholder="Documento"
                                           name="editarNumeroDocumento" id="editarNumeroDocumento" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Ingresar nombre o razón social:</small>
                                    <input type="text" class="form-control input-lg" name="editarProveedor"
                                           id="editarProveedor"
                                           placeholder="Ingresar nombre o razón social" required>
                                           <input type="hidden" id="idProveedor" name="idProveedor">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL TELEFONO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Teléfono:</small>
                                    <input type="text" class="form-control input-lg" id="editarTelefono" name="editarTelefono"
                                           placeholder="Teléfono" data-inputmask="'mask':'(9999) 999-99-99'"
                                           data-mask>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA DIAS DE CREDITO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Dias de Credito:</small>
                                    <input type="number" class="form-control input-lg" name="editarDiasCredito" id="editarDiasCredito"
                                           placeholder="Dias de Credito">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL EMAIL -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Email:</small>
                                    <input type="email" class="form-control input-lg" id="editarEmail" name="editarEmail"
                                        placeholder="Correo electronico">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL DIRECION -->
                        <div class="form-group col-lg-12">
                            <div class="input-group">
                                <label><small style="color: #000;">Dirección:</small>
                                    <input type="text" class="form-control input-lg" name="editarDireccion"
                                           id="editarDireccion"
                                           placeholder="Ingresar dirección">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA NOTA -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <label><small style="color: #000;">Nota:</small>
                                        <textarea type="text" class="form-control input-lg" name="editarNota"
                                                  id="editarNota"
                                                  placeholder="Ingresar nota" style="resize: none;"></textarea>
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
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
            <?php
            $editarProveedor = new ControllerProveedores();
            $editarProveedor->ctrEditarProveedor();
            ?>
        </div>
    </div>
</div>

<!--=====================================
MODAL VISUALIZAR PROVEEDOR
======================================-->
<div id="modalVerProveedor" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post">
                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Proveedor</h4>
                </div>
                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">

                        <!-- ENTRADA PARA EL CODIGO-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Codigo:</small>
                                    <input type="text" class="text-center form-control input-lg" name="verCodigo"
                                           id="verCodigo"
                                           readonly required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL TIPO DE PERSONA-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Tipo de persona:</small>
                                    <input type="text" class="form-control input-lg" name="verTipoPersona"
                                           id="verTipoPersona"
                                           readonly required>
                                </label>
                            </div>
                        </div>

                        <!-- Tipo de Documento -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Nacionalidad:</small>
                                    <input type="text" class="form-control input-lg" name="verTipoDocumento"
                                           id="verTipoDocumento"
                                           readonly required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL DOCUMENTO-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Documento:</small>
                                    <input type="text" class="form-control input-lg" placeholder="Documento"
                                           name="verNumeroDocumento" id="verNumeroDocumento" readonly required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Ingresar nombre o razón social:</small>
                                    <input type="text" class="form-control input-lg" name="verProveedor"
                                           id="verProveedor"
                                           placeholder="Ingresar nombre o razón social" readonly required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL TELEFONO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Teléfono:</small>
                                    <input type="text" class="form-control input-lg" id="verTelefono" name="verTelefono"
                                           placeholder="Teléfono" data-inputmask="'mask':'(9999) 999-99-99'"
                                           data-mask readonly>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL EMAIL -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Email:</small>
                                    <input type="email" class="form-control input-lg" id="verEmail" name="verEmail"
                                        placeholder="Correo electronico" readonly>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL DIRECION -->
                        <div class="form-group col-lg-12">
                            <div class="input-group">
                                <label><small style="color: #000;">Dirección:</small>
                                    <input type="text" class="form-control input-lg" name="verDireccion"
                                           id="verDireccion"
                                           placeholder="Ingresar dirección" readonly>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA NOTA -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <label><small style="color: #000;">Nota:</small>
                                        <textarea type="text" class="form-control input-lg" name="verNota"
                                                  id="verNota"
                                                  placeholder="Ingresar nota" style="resize: none;" readonly></textarea>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>

<?php
$eliminarProveedor = new ControllerProveedores();
$eliminarProveedor->ctrEliminarProveedor();
?>