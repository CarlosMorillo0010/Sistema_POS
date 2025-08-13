<?php
    if ($_SESSION["perfil"] != "ADMINISTRADOR"){
        echo '
                <script>
                window.location = "inicio";
                </script>
            ';
        return;
    }
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Usuarios
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i>Inicio</a></li>
            <li><a href="configuracion"></i>Configuración</a>
            <li class="active">Usuarios</li>
        </ol>
    </section>
    <section class="content">

        <div class="box box-primary">
            <ul class="nav nav-tabs nav-justified">
                <li role="presentation"><a href="config-empresa">Empresa e Impuestos</a></li>
                <li role="presentation"><a href="config-perfil">Perfiles</a></li>
                <li role="presentation" class="active"><a href="config-usuarios">Usuarios</a></li>
                <li role="presentation"><a href="config-divisas">Divisas y Tasa de Cambio</a></li>
            </ul>
                
                <!-- Default box -->
                <div class="box-header with-border">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarUsuario">
                        Agregar usuario
                    </button>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped dt-responsive tablas">
                        <thead>
                        <tr>
                            <th style="width: 10px;">#</th>
                            <th>Nombre</th>
                            <th>Cedula</th>
                            <th>Perfil</th>
                            <th>Estado</th>
                            <th>Ultimo Login</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $item = null;
                        $valor = null;
                        $usuarios = ControllerUsers::ctrMostrarUsuario($item, $valor);

                        foreach ($usuarios as $key => $value) {
                            echo '<tr>
                                <td>' . ($key + 1) . '</td>
                                <td>' . $value["nombre"] . '</td>
                                <td>' . $value["documento"] . '</td>
                                <td>' . $value["perfil"] . '</td>';

                            if ($value["status"] != 0) {
                                echo '<td><button class="btn btn-success btn-xs btnActivar" idUsuario=' . $value["id_usuario"] . '" estadoUsuario="0">Activo</button></td>';
                            } else {
                                echo '<td><button class="btn btn-danger btn-xs btnActivar" idUsuario="' . $value["id_usuario"] . '" estadoUsuario="1">Inactivo</button></td>';
                            }

                            echo '<td>' . $value["festamp"] . '</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-warning btnEditarUsuario" idUsuario="' . $value["id_usuario"] . '" data-toggle="modal" data-target="#modalEditarUsuario"><i class="fa fa-pencil"></i></button>                                   
                                        <button class="btn btn-danger btnEliminarUsuario" idUsuario="' . $value["id_usuario"] . '"><i class="fa fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->

            <!-- Modal Registrar Usuario -->
            <div id="modalAgregarUsuario" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="formUsuarios" role="form" method="post" enctype="multipart/form-data">
                            <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4>Agregar usuario</h4>
                            </div>
                            <div class="modal-body">
                                <div class="box-body">
                                    <!-- Tipo de Documento -->
                                    <div class="form-group col-lg-2">
                                        <div class="input-group">
                                            <label style="color: red;"> * <small style="color: #000;"></small>
                                                <select class="form-control input-lg" name="nuevaNacionalidad">
                                                    <option value="V">V</option>
                                                    <option value="E">E</option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Documento -->
                                    <div class="form-group col-lg-4">
                                        <div class="input-group">
                                            <label for="documento" style="color: red;"> * <small style="color: #000;">Cédula de
                                                    identidad:</small>
                                                <input type="text" class="form-control input-lg" name="nuevoDocumento"
                                                       id="documento"
                                                       placeholder="Número de cedula" required>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Nombre de Usuario -->
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <label for="nombre" style="color: red;">* <small style="color: #000;">Nombre y
                                                    apellido:</small></label>
                                            <input type="text" class="form-control input-lg" name="nuevoNombre" id="nombre"
                                                   placeholder="Ingresar nombres y apellido" required>
                                        </div>
                                    </div>
                                    <!-- Entrada Password -->
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <label for="password" style="color: red;">* <small
                                                        style="color: #000;">Contraseña:</small>
                                                <input type="password" class="form-control input-lg" name="password"
                                                       id="password"
                                                       placeholder="Ingresar contraseña" required>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Repetir Password -->
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <label for="repassword" style="color: red;">* <small style="color: #000;">Repetir
                                                    contraseña:</small>
                                                <input type="password" class="form-control input-lg" name="repassword"
                                                       id="repassword"
                                                       placeholder="Repetir Contraseña">
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada para TELEFONO -->
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <label for="telefono" style="color: red;">* <small
                                                        style="color: #000;">Teléfono:</small>
                                                <input type="tel" class="form-control input-lg" id="telefono"
                                                       name="nuevoTelefono"
                                                       placeholder="Ingresar Teléfono" required
                                                       data-inputmask="'mask':'(9999) 999-9999'" data-mask>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada para CORREO ELECTRONICO -->
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <label for="email" style="color: red;">* <small style="color: #000;">Correo
                                                    Electronico:</small>
                                                <input type="text" class="form-control input-lg" id="email" name="nuevoEmail"
                                                       placeholder="Ingresar correo electronico" required>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada de Genero -->
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <label style="color: red;">* <small style="color: #000;">Genero:</small>
                                                <select class="form-control input-lg" name="nuevoGenero">
                                                    <option value="">Seleccione genero</option>
                                                    <option value="Masculino">Masculino</option>
                                                    <option value="Femenino">Femenino</option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada de Perfil -->
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <label style="color: red;">* <small style="color: #000;">Perfil:</small>
                                                <select class="form-control input-lg" id="nuevoPerfil" name="nuevoPerfil">
                                                    <option value="">Seleccione un perfil</option>
                                                    <?php

                                                    $item = null;
                                                    $valor = null;
                                                    $perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);

                                                    foreach ($perfil as $key => $value) {
                                                        echo '<option value="' . $value["perfil"] . '">' . $value["perfil"] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <span class="col-lg-12" style="color: red;">( * ) Campos obligatorios</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                            <?php
                            $crearUsuario = new ControllerUsers();
                            $crearUsuario->ctrCrearUsuario();
                            ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Editar Usuario -->
            <div id="modalEditarUsuario" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form role="form" method="post" enctype="multipart/form-data">
                            <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4>Editar usuario</h4>
                            </div>
                            <div class="modal-body">
                                <div class="box-body">
                                    <!-- Tipo de Documento -->
                                    <div class="form-group col-lg-2">
                                        <div class="input-group">
                                            <label for="editarNacionalidad"> <small style="color: #000;">Nacionalidad:</small>
                                                <select class="form-control input-lg" name="editarNacionalidad" required>
                                                    <option value="-" id="editarNacionalidad">-</option>
                                                    <option value="V">V</option>
                                                    <option value="E">E</option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Documento -->
                                    <div class="form-group col-lg-4">
                                        <div class="input-group">
                                            <label for="editarDocumento"> <small style="color: #000;">Cédula de
                                                    identidad:</small>
                                                <input type="text" class="form-control input-lg" id="editarDocumento"
                                                       name="editarDocumento"
                                                       placeholder="Cedula de identidad" required>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Nombre de Nombre de Usuario -->
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <label for="editarNombre"> <small style="color: #000;">Nombre y apellido:</small>
                                                <input type="text" class="form-control input-lg" id="editarNombre"
                                                       name="editarNombre"
                                                       value="" required>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada Password -->
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <label> <small style="color: #000;">Nueva contraseña:</small>
                                                <input type="password" class="form-control input-lg" name="editarPassword"
                                                       placeholder="Contraseña">
                                            </label>
                                            <input type="hidden" id="passwordActual" name="passwordActual">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <label> <small style="color: #000;">Repetir contraseña:</small>
                                                <input type="password" class="form-control input-lg" name="editarPassword2"
                                                       placeholder="Repetir contraseña">
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada para Telefono -->
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <label> <small style="color: #000;">Teléfono:</small>
                                                <input type="tel" class="form-control input-lg" id="editarTelefono"
                                                       name="editarTelefono"
                                                       placeholder="Ingresar Teléfono" required
                                                       data-inputmask="'mask':'(9999) 999-9999'" data-mask>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada para Correo Electronico -->
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <label> <small style="color: #000;">Correo electronico:</small>
                                                <input type="text" class="form-control input-lg" id="editarEmail"
                                                       name="editarEmail"
                                                       placeholder="Ingresar Correo Electronico" required>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada de Genero -->
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <label> <small style="color: #000;">Genero:</small>
                                                <select class="form-control input-lg" name="editarGenero" required>
                                                    <option value="" id="editarGenero"></option>
                                                    <option value="Masculino">Masculino</option>
                                                    <option value="Femenino">Femenino</option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Entrada de Perfil -->
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <label> <small style="color: #000;">Perfil:</small>
                                                <select class="form-control input-lg" name="editarPerfil" required>
                                                    <option value="" id="editarPerfil"></option>
                                                    <?php

                                                    $item = null;
                                                    $valor = null;
                                                    $perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);

                                                    foreach ($perfil as $key => $value) {
                                                        echo '<option value="' . $value["perfil"] . '">' . $value["perfil"] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                            <?php
                            $editarUsuario = new ControllerUsers();
                            $editarUsuario->ctrEditarUsuario();
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            $borrarUsuario = new ControllerUsers();
            $borrarUsuario->ctrBorrarUsuario();
            ?>

        </div>
    </section>
</div>