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
$estados = ["Amazonas", "Anzoátegui", "Apure", "Aragua", "Barinas", "Bolívar", "Carabobo", "Cojedes", "Delta Amacuro", "Distrito Capital", "Falcón", "Guárico", "Lara", "Mérida", "Miranda", "Monagas", "Nueva Esparta", "Portuguesa", "Sucre", "Táchira", "Trujillo", "Vargas", "Yaracuy", "Zulia"];
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
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar cliente</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label style="color: red;"> * <small style="color: #000;">Nombre o Razón Social:</small></label>
                                <input type="text" class="form-control input-lg" name="nuevoNombre" placeholder="Nombre o Razón Social" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label style="color: red;"> * <small style="color: #000;" class="document-label">Cédula de Identidad:</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="padding: 0; border: none;">
                                        <select class="form-control input-lg nacionalidad-select" name="nuevaNacionalidad" required style="width: 80px;">
                                            <option value="V">V</option>
                                            <option value="E">E</option>
                                            <option value="P">P</option>
                                            <option value="J">J</option>
                                            <option value="G">G</option>
                                            <option value="C">C</option>
                                        </select>
                                    </span>
                                    <input type="text" class="form-control input-lg" name="nuevoDocumento" placeholder="Número de Documento" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Teléfono Celular:</small></label>
                                <input type="text" class="form-control input-lg" name="nuevoTelefono1" placeholder="Ej: (0414)-123.45.67" data-inputmask="'mask': '(9999)-999.99.99'" data-mask>
                            </div>
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Teléfono Fijo:</small></label>
                                <input type="text" class="form-control input-lg" name="nuevoTelefono2" placeholder="Ej: (0212)-987.65.43" data-inputmask="'mask': '(9999)-999.99.99'" data-mask>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Estado:</small></label>
                                <select class="form-control input-lg" name="nuevoEstado">
                                    <option value="">Seleccionar Estado</option>
                                    <?php
                                    foreach($estados as $estado){
                                        echo "<option value=\"$estado\">$estado</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Ciudad:</small></label>
                                <input type="text" class="form-control input-lg" name="nuevaCiudad" placeholder="Ciudad">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label><small style="color: #000;">Dirección Específica:</small></label>
                                <input type="text" class="form-control input-lg" name="nuevaDireccion" placeholder="Av. Principal, Edificio, Piso, etc.">
                            </div>
                        </div>
                    </div>
                    <span style="color: red;">( * ) Campo obligatorio</span>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar cliente</button>
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
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar cliente</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label style="color: red;"> * <small style="color: #000;">Nombre o Razón Social:</small></label>
                                <input type="text" class="form-control input-lg" id="editarNombre" name="editarNombre" required>
                                <input type="hidden" id="idCliente" name="idCliente">
                            </div>
                            <div class="form-group col-lg-6">
                                <label style="color: red;"> * <small style="color: #000;" class="document-label">Cédula de Identidad:</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="padding: 0; border: none;">
                                        <select class="form-control input-lg nacionalidad-select" name="editarNacionalidad" id="editarNacionalidadSelect" required style="width: 80px;">
                                            <option id="editarNacionalidadOption"></option>
                                            <option value="V">V</option>
                                            <option value="E">E</option>
                                            <option value="P">P</option>
                                            <option value="J">J</option>
                                            <option value="G">G</option>
                                            <option value="C">C</option>
                                        </select>
                                    </span>
                                    <input type="text" class="form-control input-lg" id="editarDocumento" name="editarDocumento" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Teléfono Celular:</small></label>
                                <input type="text" class="form-control input-lg" id="editarTelefono1" name="editarTelefono1" data-inputmask="'mask': '(9999)-999.99.99'" data-mask>
                            </div>
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Teléfono Fijo:</small></label>
                                <input type="text" class="form-control input-lg" id="editarTelefono2" name="editarTelefono2" data-inputmask="'mask': '(9999)-999.99.99'" data-mask>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Estado:</small></label>
                                <select class="form-control input-lg" name="editarEstado" id="editarEstado">
                                    <option id="editarEstadoOption"></option>
                                    <?php
                                    foreach($estados as $estado){
                                        echo "<option value=\"$estado\">$estado</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Ciudad:</small></label>
                                <input type="text" class="form-control input-lg" id="editarCiudad" name="editarCiudad">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label><small style="color: #000;">Dirección Específica:</small></label>
                                <input type="text" class="form-control input-lg" id="editarDireccion" name="editarDireccion">
                            </div>
                        </div>
                    </div>
                    <span style="color: red;">( * ) Campo obligatorio</span>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
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
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Cliente</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Nombre o Razón Social:</small></label>
                                <input type="text" class="form-control input-lg" id="verNombre" readonly>
                            </div>
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;" class="document-label">Documento:</small></label>
                                <input type="text" class="form-control input-lg" id="verDocumento" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Teléfono Celular:</small></label>
                                <input type="text" class="form-control input-lg" id="verTelefono1" readonly>
                            </div>
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Teléfono Fijo:</small></label>
                                <input type="text" class="form-control input-lg" id="verTelefono2" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Estado:</small></label>
                                <input type="text" class="form-control input-lg" id="verEstado" readonly>
                            </div>
                            <div class="form-group col-lg-6">
                                <label><small style="color: #000;">Ciudad:</small></label>
                                <input type="text" class="form-control input-lg" id="verCiudad" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label><small style="color: #000;">Dirección Específica:</small></label>
                                <input type="text" class="form-control input-lg" id="verDireccion" readonly>
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

<script>
$(document).on('change', '.nacionalidad-select', function() {
    var selectedType = $(this).val();
    var formGroup = $(this).closest('.form-group');
    var label = formGroup.find('.document-label');
    var input = $(this).closest('.input-group').find('input[type="text"]');

    // Clear existing mask and value
    input.inputmask('remove');
    input.val('');

    if (selectedType === 'J' || selectedType === 'G' || selectedType === 'C') {
        label.text('RIF:');
        input.attr('placeholder', 'Número de RIF');
        input.inputmask({ 'mask': '99999999-9' });
    } else { // V, E, P
        label.text('Cédula de Identidad:');
        input.attr('placeholder', 'Número de Cédula');
    }
});
</script>