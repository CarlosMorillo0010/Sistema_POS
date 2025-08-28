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
                        <th>Identificación</th>
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
                                <td>' . htmlspecialchars($value["tipo_persona"]) . '</td>
                                <td>' . htmlspecialchars($value["tipo_documento"]) . htmlspecialchars($value["documento"]) . ' </td>
                                <td>' . htmlspecialchars($value["nombre"]) . '</td>
                                <td>' . htmlspecialchars($value["telefono"]) . '</td>
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
                            <label for="nuevoCodigo"><small style="color: #000;">Código:</small></label>
                            <?php
                            $item = null;
                            $valor = null;
                            $proveedores = ControllerProveedores::ctrMostrarProveedores($item, $valor);

                            if (!$proveedores) {
                                $length = 10;
                                $string = "1";
                                echo '
                                    <input type="text" class="text-center form-control input-lg" id="nuevoCodigo" name="nuevoCodigo" readonly="readonly" value="' . str_pad($string, $length, 0, STR_PAD_LEFT) . '">
                                ';
                            } else {
                                foreach ($proveedores as $key => $value) {

                                }
                                $length = 10;
                                $codigo = str_pad($value["codigo"] + 1, $length, 0, STR_PAD_LEFT);
                                echo '
                                    <input type="text" class="text-center form-control input-lg" id="nuevoCodigo" name="nuevoCodigo" readonly="readonly" value="' . $codigo . '">
                                ';
                            }
                            ?>
                        </div>

                        <!-- ENTRADA PARA EL TIPO DE PERSONA-->
                        <div class="form-group col-lg-4">
                            <label for="tipoPersona" style="color: red;">* <small style="color: #000;">Tipo de persona:</small></label>
                            <select class="form-control input-lg" name="tipoPersona" id="tipoPersona" required>
                                <option value=""></option>
                                <option value="Natural">Natural</option>
                                <option value="Juridica">Juridica</option>
                            </select>
                        </div>

                        <!-- Tipo de Documento -->
                        <div class="form-group col-lg-2">
                            <label for="tipoDocumento" style="color: red;">* <small style="color: #000;">Tipo Doc.:</small></label>
                            <select class="form-control input-lg" name="tipoDocumento" id="tipoDocumento" required>
                                <option value=""></option>
                                <!-- Opciones se llenan con JS -->
                            </select>
                        </div>

                        <!-- ENTRADA PARA EL DOCUMENTO-->
                        <div class="form-group col-lg-3">
                            <label for="numeroDocumento" style="color: red;">* <small style="color: #000;">Documento:</small></label>
                            <input type="text" class="form-control input-lg" placeholder="Documento" name="numeroDocumento" id="numeroDocumento" required>
                        </div>

                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group col-lg-6">
                            <label for="nuevoProveedor" style="color: red;">* <small style="color: #000;">Ingresar nombre o razón social:</small></label>
                            <input type="text" class="form-control input-lg" name="nuevoProveedor" id="nuevoProveedor" placeholder="Ingresar nombre o razón social" required>
                        </div>

                        <!-- ENTRADA PARA EL TELEFONO -->
                        <div class="form-group col-lg-6">
                            <label for="nuevoTelefono"><small style="color: #000;">Teléfono:</small></label>
                            <input type="text" class="form-control input-lg" id="nuevoTelefono" name="nuevoTelefono" placeholder="Teléfono" data-inputmask="'mask':'(9999) 999-99-99'" data-mask>
                        </div>

                        <!-- ENTRADA PARA DIAS DE CREDITO -->
                        <div class="form-group col-lg-6">
                            <label for="nuevoDiasCredito"><small style="color: #000;">Dias de Credito:</small></label>
                            <input type="number" class="form-control input-lg" id="nuevoDiasCredito" name="nuevoDiasCredito" placeholder="Dias de Credito">
                        </div>

                        <!-- ENTRADA PARA EL EMAIL -->
                        <div class="form-group col-lg-6">
                            <label for="nuevoEmail"><small style="color: #000;">Email:</small></label>
                            <input type="email" class="form-control input-lg" id="nuevoEmail" name="nuevoEmail" placeholder="Correo electronico">
                        </div>
                        
                        <!-- ENTRADA PARA EL ESTADO -->
                        <div class="form-group col-lg-6">
                            <label for="nuevoEstado"><small style="color: #000;">Estado:</small></label>
                            <select class="form-control input-lg" name="nuevoEstado" id="nuevoEstado">
                                <option value="">Seleccionar Estado</option>
                                <option value="Amazonas">Amazonas</option>
                                <option value="Anzoátegui">Anzoátegui</option>
                                <option value="Apure">Apure</option>
                                <option value="Aragua">Aragua</option>
                                <option value="Barinas">Barinas</option>
                                <option value="Bolívar">Bolívar</option>
                                <option value="Carabobo">Carabobo</option>
                                <option value="Cojedes">Cojedes</option>
                                <option value="Delta Amacuro">Delta Amacuro</option>
                                <option value="Distrito Capital">Distrito Capital</option>
                                <option value="Falcón">Falcón</option>
                                <option value="Guárico">Guárico</option>
                                <option value="Lara">Lara</option>
                                <option value="Mérida">Mérida</option>
                                <option value="Miranda">Miranda</option>
                                <option value="Monagas">Monagas</option>
                                <option value="Nueva Esparta">Nueva Esparta</option>
                                <option value="Portuguesa">Portuguesa</option>
                                <option value="Sucre">Sucre</option>
                                <option value="Táchira">Táchira</option>
                                <option value="Trujillo">Trujillo</option>
                                <option value="Vargas">Vargas (La Guaira)</option>
                                <option value="Yaracuy">Yaracuy</option>
                                <option value="Zulia">Zulia</option>
                            </select>
                        </div>

                        <!-- ENTRADA PARA LA CIUDAD -->
                        <div class="form-group col-lg-6">
                            <label for="nuevaCiudad"><small style="color: #000;">Ciudad:</small></label>
                            <input type="text" class="form-control input-lg" name="nuevaCiudad" id="nuevaCiudad" placeholder="Ingresar ciudad">
                        </div>

                        <!-- ENTRADA PARA LA DIRECCION -->
                        <div class="form-group col-lg-12">
                            <label for="nuevaDireccion"><small style="color: #000;">Dirección Detallada:</small></label>
                            <textarea class="form-control input-lg" name="nuevaDireccion" id="nuevaDireccion" placeholder="Ingresar dirección detallada (calle, edificio, etc.)" style="resize: none;"></textarea>
                        </div>

                        <!-- ENTRADA PARA NOTA -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="nuevaNota"><small style="color: #000;">Nota:</small></label>
                                <textarea class="form-control input-lg" name="nuevaNota" id="nuevaNota" placeholder="Ingresar nota" style="resize: none;"></textarea>
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
                            <label for="editarCodigo"><small style="color: #000;">Codigo:</small></label>
                            <input type="text" class="text-center form-control input-lg" name="editarCodigo" id="editarCodigo" readonly required>
                        </div>

                        <!-- ENTRADA PARA EL TIPO DE PERSONA-->
                        <div class="form-group col-lg-4">
                            <label for="editarTipoPersona" style="color: red;">* <small style="color: #000;">Tipo de persona:</small></label>
                            <select class="form-control input-lg" name="editarTipoPersona" id="editarTipoPersona" required>
                                <option value=""></option>
                                <option value="Natural">Natural</option>
                                <option value="Juridica">Juridica</option>
                            </select>
                        </div>

                        <!-- Tipo de Documento -->
                        <div class="form-group col-lg-2">
                            <label for="editarTipoDocumento" style="color: red;">* <small style="color: #000;">Tipo Doc.:</small></label>
                            <select class="form-control input-lg" name="editarTipoDocumento" id="editarTipoDocumento" required>
                                <option value=""></option>
                                <!-- Opciones se llenan con JS -->
                            </select>
                        </div>

                        <!-- ENTRADA PARA EL DOCUMENTO-->
                        <div class="form-group col-lg-3">
                            <label for="editarNumeroDocumento" style="color: red;">* <small style="color: #000;">Documento:</small></label>
                            <input type="text" class="form-control input-lg" placeholder="Documento" name="editarNumeroDocumento" id="editarNumeroDocumento" required>
                        </div>

                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group col-lg-6">
                            <label for="editarProveedor" style="color: red;">* <small style="color: #000;">Ingresar nombre o razón social:</small></label>
                            <input type="text" class="form-control input-lg" name="editarProveedor" id="editarProveedor" placeholder="Ingresar nombre o razón social" required>
                            <input type="hidden" id="idProveedor" name="idProveedor">
                        </div>

                        <!-- ENTRADA PARA EL TELEFONO -->
                        <div class="form-group col-lg-6">
                            <label for="editarTelefono"><small style="color: #000;">Teléfono:</small></label>
                            <input type="text" class="form-control input-lg" id="editarTelefono" name="editarTelefono" placeholder="Teléfono" data-inputmask="'mask':'(9999) 999-99-99'" data-mask>
                        </div>

                        <!-- ENTRADA PARA DIAS DE CREDITO -->
                        <div class="form-group col-lg-6">
                            <label for="editarDiasCredito"><small style="color: #000;">Dias de Credito:</small></label>
                            <input type="number" class="form-control input-lg" name="editarDiasCredito" id="editarDiasCredito" placeholder="Dias de Credito">
                        </div>

                        <!-- ENTRADA PARA EL EMAIL -->
                        <div class="form-group col-lg-6">
                            <label for="editarEmail"><small style="color: #000;">Email:</small></label>
                            <input type="email" class="form-control input-lg" id="editarEmail" name="editarEmail" placeholder="Correo electronico">
                        </div>

                        <!-- ENTRADA PARA EL ESTADO -->
                        <div class="form-group col-lg-6">
                            <label for="editarEstado"><small style="color: #000;">Estado:</small></label>
                            <select class="form-control input-lg" name="editarEstado" id="editarEstado">
                                <option value="">Seleccionar Estado</option>
                                <option value="Amazonas">Amazonas</option>
                                <option value="Anzoátegui">Anzoátegui</option>
                                <option value="Apure">Apure</option>
                                <option value="Aragua">Aragua</option>
                                <option value="Barinas">Barinas</option>
                                <option value="Bolívar">Bolívar</option>
                                <option value="Carabobo">Carabobo</option>
                                <option value="Cojedes">Cojedes</option>
                                <option value="Delta Amacuro">Delta Amacuro</option>
                                <option value="Distrito Capital">Distrito Capital</option>
                                <option value="Falcón">Falcón</option>
                                <option value="Guárico">Guárico</option>
                                <option value="Lara">Lara</option>
                                <option value="Mérida">Mérida</option>
                                <option value="Miranda">Miranda</option>
                                <option value="Monagas">Monagas</option>
                                <option value="Nueva Esparta">Nueva Esparta</option>
                                <option value="Portuguesa">Portuguesa</option>
                                <option value="Sucre">Sucre</option>
                                <option value="Táchira">Táchira</option>
                                <option value="Trujillo">Trujillo</option>
                                <option value="Vargas">Vargas (La Guaira)</option>
                                <option value="Yaracuy">Yaracuy</option>
                                <option value="Zulia">Zulia</option>
                            </select>
                        </div>

                        <!-- ENTRADA PARA LA CIUDAD -->
                        <div class="form-group col-lg-6">
                            <label for="editarCiudad"><small style="color: #000;">Ciudad:</small></label>
                            <input type="text" class="form-control input-lg" name="editarCiudad" id="editarCiudad" placeholder="Ingresar ciudad">
                        </div>

                        <!-- ENTRADA PARA LA DIRECCION -->
                        <div class="form-group col-lg-12">
                            <label for="editarDireccion"><small style="color: #000;">Dirección Detallada:</small></label>
                            <textarea class="form-control input-lg" name="editarDireccion" id="editarDireccion" placeholder="Ingresar dirección detallada" style="resize: none;"></textarea>
                        </div>

                        <!-- ENTRADA PARA NOTA -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="editarNota"><small style="color: #000;">Nota:</small></label>
                                <textarea class="form-control input-lg" name="editarNota" id="editarNota" placeholder="Ingresar nota" style="resize: none;"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <!--=====================================
                PIE DEL MODAL
                ======================================-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
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
            <div class="modal-header" style="background:#3c8dbc; color:white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Ver proveedor</h4>
            </div>
            <div class="modal-body">
                <div class="box-body">

                    <!-- ENTRADA PARA EL CODIGO-->
                    <div class="form-group col-lg-3">
                        <label><small style="color: #000;">Codigo:</small></label>
                        <input type="text" class="text-center form-control input-lg" id="verCodigo" readonly>
                    </div>

                    <!-- ENTRADA PARA EL TIPO DE PERSONA-->
                    <div class="form-group col-lg-3">
                        <label><small style="color: #000;">Tipo de persona:</small></label>
                        <input type="text" class="form-control input-lg" id="verTipoPersona" readonly>
                    </div>

                    <!-- Tipo de Documento -->
                    <div class="form-group col-lg-3">
                        <label><small style="color: #000;">Tipo Doc.:</small></label>
                        <input type="text" class="form-control input-lg" id="verTipoDocumento" readonly>
                    </div>

                    <!-- ENTRADA PARA EL DOCUMENTO-->
                    <div class="form-group col-lg-3">
                        <label><small style="color: #000;">Documento:</small></label>
                        <input type="text" class="form-control input-lg" id="verNumeroDocumento" readonly>
                    </div>

                    <!-- ENTRADA PARA EL NOMBRE -->
                    <div class="form-group col-lg-6">
                        <label><small style="color: #000;">Nombre o razón social:</small></label>
                        <input type="text" class="form-control input-lg" id="verProveedor" readonly>
                    </div>

                    <!-- ENTRADA PARA EL TELEFONO -->
                    <div class="form-group col-lg-6">
                        <label><small style="color: #000;">Teléfono:</small></label>
                        <input type="text" class="form-control input-lg" id="verTelefono" readonly>
                    </div>

                    <!-- ENTRADA PARA EL EMAIL -->
                    <div class="form-group col-lg-6">
                        <label><small style="color: #000;">Email:</small></label>
                        <input type="email" class="form-control input-lg" id="verEmail" readonly>
                    </div>

                    <!-- ENTRADA PARA EL ESTADO -->
                    <div class="form-group col-lg-6">
                        <label><small style="color: #000;">Estado:</small></label>
                        <input type="text" class="form-control input-lg" id="verEstado" readonly>
                    </div>

                    <!-- ENTRADA PARA LA CIUDAD -->
                    <div class="form-group col-lg-6">
                        <label><small style="color: #000;">Ciudad:</small></label>
                        <input type="text" class="form-control input-lg" id="verCiudad" readonly>
                    </div>

                    <!-- ENTRADA PARA LA DIRECCION -->
                    <div class="form-group col-lg-12">
                        <label><small style="color: #000;">Dirección Detallada:</small></label>
                        <textarea class="form-control input-lg" id="verDireccion" readonly style="resize: none;"></textarea>
                    </div>

                    <!-- ENTRADA PARA NOTA -->
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label><small style="color: #000;">Nota:</small></label>
                            <textarea class="form-control input-lg" id="verNota" readonly style="resize: none;"></textarea>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php
$eliminarProveedor = new ControllerProveedores();
$eliminarProveedor->ctrEliminarProveedor();
?>

<script>
$(document).ready(function(){

    const docOptions = {
        "Natural": [
            { value: "V", text: "V" },
            { value: "E", text: "E" },
            { value: "P", text: "P" }
        ],
        "Juridica": [
            { value: "J", text: "J" },
            { value: "G", text: "G" },
            { value: "C", text: "C" }
        ]
    };

    function updateTipoDocumento(tipoPersona, tipoDocumentoSelect) {
        const select = $(tipoDocumentoSelect);
        const currentValue = select.val();
        select.empty().append('<option value=""></option>');
        
        if (docOptions[tipoPersona]) {
            docOptions[tipoPersona].forEach(function(option) {
                select.append($('<option>', { value: option.value, text: option.text }));
            });
        }
        select.val(currentValue);
    }

    function applyInputMask(documentoSelect, numeroDocumentoInput) {
        const docType = $(documentoSelect).val();
        const input = $(numeroDocumentoInput);
        
        // Remover máscaras existentes
        input.inputmask('remove');

        if (docType === 'J' || docType === 'G' || docType === 'C') {
            input.inputmask({ "mask": "99999999-9" });
        } else if (docType === 'V' || docType === 'E') {
            input.inputmask({ "mask": "9{1,8}", "greedy": false });
        } else {
            // Sin máscara para pasaporte u otros
        }
    }

    // --- Modal Agregar Proveedor ---
    $('#tipoPersona').on('change', function() {
        updateTipoDocumento($(this).val(), '#tipoDocumento');
        applyInputMask('#tipoDocumento', '#numeroDocumento');
    });

    $('#tipoDocumento').on('change', function() {
        applyInputMask(this, '#numeroDocumento');
    });

    // --- Modal Editar Proveedor ---
    $('#editarTipoPersona').on('change', function() {
        updateTipoDocumento($(this).val(), '#editarTipoDocumento');
        applyInputMask('#editarTipoDocumento', '#editarNumeroDocumento');
    });
    
    $('#editarTipoDocumento').on('change', function() {
        applyInputMask(this, '#editarNumeroDocumento');
    });

    // --- Lógica para poblar el modal de edición ---
    // Es necesario sobreescribir o ajustar la función que puebla los datos
    // para que la lógica de selects dinámicos funcione correctamente al abrir el modal.
    $(document).on("click", ".btnEditarProveedor", function(){
        var idProveedor = $(this).attr("idProveedor");
        var datos = new FormData();
        datos.append("idProveedor", idProveedor);

        $.ajax({
            url: "ajax/proveedores.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success: function(respuesta){
                $("#idProveedor").val(respuesta["id_proveedor"]);
                $("#editarCodigo").val(respuesta["codigo"]);
                $("#editarProveedor").val(respuesta["nombre"]);
                
                // Lógica para selects dinámicos
                $("#editarTipoPersona").val(respuesta["tipo_persona"]);
                updateTipoDocumento(respuesta["tipo_persona"], '#editarTipoDocumento');
                $("#editarTipoDocumento").val(respuesta["tipo_documento"]);
                
                // Aplicar máscara
                applyInputMask('#editarTipoDocumento', '#editarNumeroDocumento');
                $("#editarNumeroDocumento").val(respuesta["documento"]);

                $("#editarTelefono").val(respuesta["telefono"]);
                $("#editarEmail").val(respuesta["email"]);
                $("#editarDiasCredito").val(respuesta["dias_credito"]);
                
                // Nuevos campos de dirección
                $("#editarEstado").val(respuesta["estado"]);
                $("#editarCiudad").val(respuesta["ciudad"]);
                $("#editarDireccion").val(respuesta["direccion"]);
                
                $("#editarNota").val(respuesta["nota"]);
            }
        });
    });
    
    // Lógica para poblar el modal de ver
    $(document).on("click", ".btnVerProveedor", function(){
        var idProveedor = $(this).attr("idProveedor");
        var datos = new FormData();
        datos.append("idProveedor", idProveedor);

        $.ajax({
            url: "ajax/proveedores.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success: function(respuesta){
                $("#verCodigo").val(respuesta["codigo"]);
                $("#verProveedor").val(respuesta["nombre"]);
                $("#verTipoPersona").val(respuesta["tipo_persona"]);
                $("#verTipoDocumento").val(respuesta["tipo_documento"]);
                $("#verNumeroDocumento").val(respuesta["documento"]);
                $("#verTelefono").val(respuesta["telefono"]);
                $("#verEmail").val(respuesta["email"]);
                $("#verEstado").val(respuesta["estado"]);
                $("#verCiudad").val(respuesta["ciudad"]);
                $("#verDireccion").val(respuesta["direccion"]);
                $("#verNota").val(respuesta["nota"]);
            }
        });
    });
});
</script>
