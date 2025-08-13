<?php
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value):
    if ($_SESSION["perfil"] == $value["perfil"] && $value["ventas"] == "N"){
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
            Administrar clientes
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar cliente</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarCliente">
                    Agregar cliente
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Codigo</th>
                        <th>Cliente</th>
                        <th>Documento</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Dirección</th>
                        <th>Total compras</th>
                        <th>Fecha registro</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $item = null;
                    $valor = null;
                    $clientes = ControllerClients::ctrMostrarClientes($item, $valor);
                    foreach ($clientes as $key => $value) {
                        echo '<tr>    
                    <td>' . ($key + 1) . '</td>    
                    <td>' . $value["codigo"] . '</td>   
                    <td>' . $value["nombre"] . '</td>   
                    <td>' . $value["tipo_documento"] . $value["documento"] . '</td>        
                    <td>' . $value["telefono"] . '</td>    
                    <td>' . $value["email"] . '</td>    
                    <td>' . $value["direccion"] . '</td>    
                    <td>' . $value["compras"] . '</td>  
                    <td>' . $value["feregistro"] . '</td>   
                    <td>    
                      <div class="btn-group">

                        <button class="btn btn-dark btnVerCliente" data-toggle="modal" data-target="#modalVerCliente" idCliente="' . $value["id"] . '"><i class="fa fa-eye"></i></button>

                        <button class="btn btn-warning btnEditarCliente" data-toggle="modal" data-target="#modalEditarCliente" idCliente="' . $value["id"] . '"><i class="fa fa-pencil"></i></button>

                        <button class="btn btn-danger btnEliminarCliente" idCliente="' . $value["id"] . '"><i class="fa fa-times"></i></button>

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
MODAL AGREGAR CLIENTE
======================================-->
<div id="modalAgregarCliente" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post">
                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar cliente</h4>
                </div>
                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">

                        <!-- CODIGO DE CLIENTE -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Codigo:</small>
                                    <?php
                                    $item = null;
                                    $valor = null;
                                    $codigo_cliente = ControllerClients::ctrMostrarClientes($item, $valor);

                                    if (!$codigo_cliente) {
                                        echo '
                                                <input type="text" class="text-center form-control input-lg" name="codigoCliente" id="codigoCliente" readonly="readonly" value="'. mt_rand(10000000, 99999999) .'">
                                            ';
                                    } else {
                                        foreach ($codigo_cliente as $key => $value) {

                                        }
                                        $codigo = $value["codigo"] + 1;
                                        echo '<input type="text" class="text-center form-control input-lg" name="codigoCliente" id="codigoCliente" readonly="readonly" value="' . $codigo . '">';
                                    }
                                    ?>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Nombre y apellido:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoNombre"
                                        placeholder="Nombre y apellido" required>
                                </label>
                            </div>
                        </div>

                        <!-- TIPO O LETRA DEL DOCUMENTO -->
                        <div class="form-group col-lg-2">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Nacionalidad:</small>
                                    <select class="form-control input-lg" name="nuevaNacionalidad" required>
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

                        <!-- ENTRADA PARA EL DOCUMENTO DE ID -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Documento:</small>
                                    <input type="text" min="0" class="form-control input-lg" name="nuevoDocumento"
                                        placeholder="Documento" required>
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

                        <!-- ENTRADA PARA EL EMAIL -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Email:</small>
                                    <input type="email" class="form-control input-lg" name="nuevoEmail"
                                        placeholder="Correo Electronico">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA LA DIRECCIÓN -->
                        <div class="form-group col-lg-12">
                            <div class="input-group">
                                <label><small style="color: #000;">Dirección:</small>
                                    <input type="text" class="form-control input-lg" name="nuevaDireccion"
                                        placeholder="Ingresar dirección">
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
                
            </form>
            <?php
            $crearCliente = new ControllerClients();
            $crearCliente->ctrCrearClientes();
            ?>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR CLIENTE
======================================-->
<div id="modalEditarCliente" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post">
                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar cliente</h4>
                </div>
                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- CODIGO DE CLIENTE -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Codigo:</small>
                                    <input type="text" class="text-center form-control input-lg" id="editarCodigo"
                                           name="editarCodigo"
                                           readonly required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Nombre y apellido:</small>
                                <input type="text" class="form-control input-lg" id="editarNombre" name="editarNombre" required>
                                <input type="hidden" id="idCliente"name="idCliente">
                            </div>
                        </div>
                        
                        <!-- TIPO O LETRA DEL DOCUMENTO -->
                        <div class="form-group col-lg-2">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Nacionalidad:</small>
                                    <select class="form-control input-lg" name="editarNacionalidad" required>
                                        <option id="editarNacionalidad"></option>
                                        <option value="V">V</option>
                                        <option value="J">J</option>
                                        <option value="G">G</option>
                                        <option value="C">C</option>
                                        <option value="E">E</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL DOCUMENTO DE ID -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Documento:</small>
                                    <input type="text" class="form-control input-lg" id="editarDocumento"
                                           name="editarDocumento"
                                           required>
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
            
                        <!-- ENTRADA PARA EL EMAIL -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <small style="color: #000;">Email:</small>
                                <input type="text" class="form-control input-lg" id="editarEmail"
                                       name="editarEmail">
                            </div>
                        </div>
         
                        <!-- ENTRADA PARA EL DIRECCIÓN -->
                        <div class="form-group col-lg-12">
                            <div class="input-group">
                                <small style="color: #000;">Dirección:</small>
                                <input type="text" class="form-control input-lg" id="editarDireccion"
                                       name="editarDireccion">
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
            </form>
            <?php
            $editarCliente = new ControllerClients();
            $editarCliente->ctrEditarCliente();
            ?>
        </div>
    </div>
</div>

<!---------------------------------------
MODAL VISUALIZAR CLIENTE
---------------------------------------->
<div id="modalVerCliente" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="verCliente" role="form" method="post">
                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Cliente</h4>
                </div>
                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body" style="padding: 0;">
                    <div class="box-body">

                         <!-- CODIGO DE CLIENTE -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Codigo:</small>
                                    <input type="text" class="text-center form-control input-lg" id="verCodigo"
                                           name="verCodigo"
                                           readonly required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <small style="color: #000;">Nombre y apellido:</small>
                                <input type="text" class="form-control input-lg" id="verNombre" name="verNombre" readonly required>
                            </div>
                        </div>
                        
                        <!-- TIPO O LETRA DEL DOCUMENTO -->
                        <div class="form-group col-lg-2">
                            <div class="input-group">
                                <label><small style="color: #000;">Nacionalidad:</small>
                                    <input type="text" class="text-center form-control input-lg" id="verNacionalidad"
                                           name="verNacionalidad"
                                           readonly required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL DOCUMENTO DE ID -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Documento:</small>
                                    <input type="text" class="form-control input-lg" id="verDocumento"
                                           name="verDocumento"
                                           readonly required>
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
                                <small style="color: #000;">Email:</small>
                                <input type="text" class="form-control input-lg" id="verEmail"
                                       name="verEmail" readonly>
                            </div>
                        </div>
         
                        <!-- ENTRADA PARA EL DIRECCIÓN -->
                        <div class="form-group col-lg-12">
                            <div class="input-group">
                                <small style="color: #000;">Dirección:</small>
                                <input type="text" class="form-control input-lg" id="verDireccion"
                                       name="verDireccion" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$eliminarCliente = new ControllerClients();
$eliminarCliente->ctrEliminarCliente();
?>



