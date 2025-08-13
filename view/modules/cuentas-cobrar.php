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
            Administrar cuentas por cobrar
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li>Administrar cuentas por cobrar</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarCuentaCobrar">
                    Agregar cuenta por cobrar
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped tablas">
                    <thead>
                        <tr>
                            <th style="width:10px">#</th>
                            <th>Codigo</th>
                            <th>proveedor</th>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Monto</th>
                            <th>Saldo</th>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                            $item = null;
                            $valor = null;
                            $cuentas_cobrar = ControllerCuentasCobrar::ctrMostrarCuentasCobrar($item, $valor);

                            foreach ($cuentas_cobrar as $key => $value){

                                echo '<tr>
                                    <td>' . ($key + 1) . '</td>
                                    <td>' . str_pad($value["codigo"], 8, "0", STR_PAD_LEFT) . '</td>
                                    <td>' . $value["proveedor"] . '</td>
                                    <td>' . $value["nombre"] . '</td>
                                    <td>' . $value["tipo_documento"] . $value["documento"] . '</td>
                                    <td>' . $value["monto"] . '</td>
                                    <td>' . $value["saldo"] . '</td>
                                    <td>' . $value["fecha_cuenta"] . '</td>
                                    <td>' . $value["descripcion"] . '</td>
                                    <td>' . $value["estatus"] . '</td>
                                    <td>
                                        <div class="btn-group">

                                            <button class="btn btn-dark btnVerCuentaCobrar" data-toggle="modal" data-target="#modalVerCuentaCobrar" idCuentaCobrar="' . $value["id_cuentas_cobrar"] . '"><i class="fa fa-eye"></i></button>

                                            <button class="btn btn-warning btnEditarCuentaCobrar" idCuentaCobrar="' . $value["id_cuentas_cobrar"] . '" data-toggle="modal" data-target="#modalEditarCuentaCobrar"><i class="fa fa-pencil"></i></button>

                                            <button class="btn btn-danger btnEliminarCuentaCobrar" idCuentaCobrar="' . $value["id_cuentas_cobrar"] . '"><i class="fa fa-times"></i></button>
                                            
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
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!--=====================================
MODAL AGREGAR CUENTA POR COBRAR
======================================-->
<div id="modalAgregarCuentaCobrar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar cuenta</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">

                        <!-- ENTRADA PARA EL CODIGO-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                            <label style="color: red;"> * <small style="color: #000;">Codigo</small>
                                    <?php
                                    $item = null;
                                    $valor = null;
                                    $codigo = ControllerCuentasCobrar::ctrMostrarCuentasCobrar($item, $valor);

                                    if (!$codigo) {
                                        echo '
                                                <input type="text" class="form-control input-lg" name="nuevoCodigo" id="nuevoCodigo" readonly="readonly" value="'. mt_rand(10000000, 99999999) .'">
                                            ';
                                    } else {
                                        foreach ($codigo as $key => $value) {

                                        }
                                        $codigo = $value["codigo"] + 1;
                                        echo '<input type="text" class="form-control input-lg" name="nuevoCodigo" id="nuevoCodigo" readonly="readonly" value="' . $codigo . '">';
                                    }
                                    ?>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL PROVEEDOR -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Proveedor:</small>
                                    <select class="form-control input-lg" id="nuevoProveedor" name="nuevoProveedor">
                                        <option value="-">-</option>
                                            <?php
                                                $item = null;
                                                $valor = null;
                                                $proveedores = ControllerProveedores::ctrMostrarProveedores($item, $valor);

                                                foreach ($proveedores as $key => $value) {
                                                    echo '<option value="'.$value["nombre"].'">'.$value["nombre"].'</option>';
                                                }
                                            ?>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL TIPO DE DOCUMENTO -->
                        <div class="form-group col-lg-2">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Tipo documento:</small>
                                    <select class="form-control input-lg" name="nuevoTipoDocumento"
                                            id="nuevotipoDocumento">
                                        <option value="-">-</option>
                                        <option value="CI">CI</option>
                                        <option value="RIF">RIF</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL DOCUMENTO-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Numero documento:</small>
                                    <input type="text" class="form-control input-lg" placeholder="Nº Documento"
                                           name="nuevoDocumento" id="nuevoDocumento" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL NOMBRE O RAZON SOCIAL -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Empresa:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoNombre"
                                        placeholder="Nombre o Razon Social" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA LA FECHA-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Fecha:</small>
                                    <input type="text" class="form-control input-lg" name="nuevaFechaCuenta" value="<?php echo date("d-m-Y");?>">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL AÑO-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Año:</small>
                                    <input type="text" class="form-control input-lg" name="nuevaFechaAno" value="<?php echo strftime("%Y");?>">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL MONTO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Monto:</small>
                                    <input type="number" class="form-control input-lg" name="nuevoMonto" min="0" step="any" placeholder="Monto"
                                            required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL SALDO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Saldo:</small>
                                    <input type="number" class="form-control input-lg" name="nuevoSaldo" min="0" step="any" placeholder="Saldo">
                                </label>
                            </div>
                        </div>
                        
                        <!-- ENTRADA PARA LA FECHA DE LA FACTURA-->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Fecha de factura:</small>
                                    <input type="date" class="form-control input-lg" name="nuevaFechaFactura" value="<?php echo date("Y-m-d");?>">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL ESTATUS -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Estatus:</small>
                                    <select class="form-control input-lg" name="nuevoEstatus">
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Pagado">Pagado</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                         <!-- ENTRADA PARA DESCRIPCÍON -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <small style="color: #000;">Descripcíon:</small>
                                        <textarea type="text" class="form-control input-lg" name="nuevaDescripcion"
                                                  id="nuevaDescripcion"
                                                  placeholder="Descripción" style="resize: none;"></textarea>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <span class="col-lg-12" style="color: red;">( * ) Campos obligatorios</span>

                </div>

                <!--=====================================
                PIE DEL MODAL
                ======================================-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>

            </form>

            <?php
                $crearCuentaCobrar = new ControllerCuentasCobrar();
                $crearCuentaCobrar->ctrCrearCuentaCobrar();
            ?>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR CUENTA POR COBRAR
======================================-->
<div id="modalEditarCuentaCobrar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar cuenta</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        
                        <!-- ENTRADA PARA DEL CODIGO -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Codigo:</small>
                                    <input type="text" class="form-control input-lg" id="editarCodigo" name="editarCodigo" readonly required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL PROVEEDOR -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Proveedor:</small>
                                    <select class="form-control input-lg" id="editarProveedor" name="editarProveedor">
                                        <option value="-">-</option>
                                            <?php
                                                $item = null;
                                                $valor = null;
                                                $proveedores = ControllerProveedores::ctrMostrarProveedores($item, $valor);

                                                foreach ($proveedores as $key => $value) {
                                                    echo '<option value="'.$value["nombre"].'">'.$value["nombre"].'</option>';
                                                }
                                            ?>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL TIPO DE DOCUMENTO -->
                        <div class="form-group col-lg-2">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Tipo documento:</small>
                                    <select class="form-control input-lg" id="editarTipoDocumento" name="editarTipoDocumento">
                                        <option value="-">-</option>
                                        <option value="CI">CI</option>
                                        <option value="RIF">RIF</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL DOCUMENTO-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Numero documento:</small>
                                    <input type="text" class="form-control input-lg" placeholder="Nº Documento"
                                           name="editarDocumento" id="editarDocumento" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL NOMBRE O RAZON SOCIAL -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Empresa:</small>
                                    <input type="text" class="form-control input-lg" id="editarNombre" name="editarNombre"
                                        placeholder="Nombre o Razon Social" required>
                                    <input type="hidden" name="idCuentaCobrar" id="idCuentaCobrar">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA LA FECHA-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Fecha:</small>
                                    <input type="text" class="form-control input-lg" id="editarFechaCuenta" name="editarFechaCuenta">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL AÑO-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Año:</small>
                                    <input type="text" class="form-control input-lg" id="editarFechaAno" name="editarFechaAno">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL MONTO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Monto:</small>
                                    <input type="number" class="form-control input-lg" id="editarMonto" name="editarMonto" min="0" step="any" placeholder="Monto"
                                            required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL SALDO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Saldo:</small>
                                    <input type="number" class="form-control input-lg" id="editarSaldo" name="editarSaldo" min="0" step="any" placeholder="Saldo">
                                </label>
                            </div>
                        </div>
                        
                        <!-- ENTRADA PARA LA FECHA DE LA FACTURA-->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Fecha de factura:</small>
                                    <input type="date" class="form-control input-lg" id="editarFechaFactura" name="editarFechaFactura">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL ESTATUS -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Estatus:</small>
                                    <select class="form-control input-lg" id="editarEstatus" name="editarEstatus">
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Pagado">Pagado</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA DESCRIPCÍON -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <small style="color: #000;">Descripcíon:</small>
                                        <textarea type="text" class="form-control input-lg" name="editarDescripcion"
                                                  id="editarDescripcion"
                                                  placeholder="Descripción" style="resize: none;"></textarea>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <span class="col-lg-12" style="color: red;">( * ) Campos obligatorios</span>

                </div>

                <!--=====================================
                PIE DEL MODAL
                ======================================-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
            <?php
                $editarCuentaCobrar = new ControllerCuentasCobrar();
                $editarCuentaCobrar->ctrEditarCuentaCobrar();
            ?>
        </div>
    </div>
</div>

<!---------------------------------------
MODAL VISUALIZAR CUENTA COBRAR
---------------------------------------->
<div id="modalVerCuentaCobrar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="verCuentaCobrar" role="form" method="post">
                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Cuenta por cobrar</h4>
                </div>
                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body" style="padding: 0;">
                    <div class="box-body">

                        <!-- ENTRADA PARA DEL CODIGO -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Codigo:</small>
                                    <input type="text" class="form-control input-lg" id="verCodigo" name="verCodigo" readonly required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL PROVEEDOR -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label><small style="color: #000;">Proveedor:</small>
                                    <input class="form-control input-lg" id="verProveedor" name="verProveedor" readonly>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL TIPO DE DOCUMENTO -->
                        <div class="form-group col-lg-2">
                            <div class="input-group">
                                <label><small style="color: #000;">Tipo documento:</small>
                                    <input class="form-control input-lg" id="verTipoDocumento" name="verTipoDocumento" readonly>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL DOCUMENTO-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Numero documento:</small>
                                    <input type="text" class="form-control input-lg" placeholder="Nº Documento"
                                           name="verDocumento" id="verDocumento" readonly required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL NOMBRE O RAZON SOCIAL -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Empresa:</small>
                                    <input type="text" class="form-control input-lg" id="verNombre" name="verNombre"
                                        placeholder="Nombre o Razon Social" readonly required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA LA FECHA-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Fecha:</small>
                                    <input type="text" class="form-control input-lg" id="verFechaCuenta" name="verFechaCuenta" readonly>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL AÑO-->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <small style="color: #000;">Año:</small>
                                    <input type="text" class="form-control input-lg" id="verFechaAno" name="verFechaAno" readonly>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL MONTO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Monto:</small>
                                    <input type="number" class="form-control input-lg" id="verMonto" name="verMonto" min="0" step="any" placeholder="Monto" readonly required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL SALDO -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Saldo:</small>
                                    <input type="number" class="form-control input-lg" id="verSaldo" name="verSaldo" min="0" step="any" placeholder="Saldo" readonly>
                                </label>
                            </div>
                        </div>
                        
                        <!-- ENTRADA PARA LA FECHA DE LA FACTURA-->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Fecha de factura:</small>
                                    <input type="date" class="form-control input-lg" id="verFechaFactura" name="verFechaFactura" readonly>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL ESTATUS -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label><small style="color: #000;">Estatus:</small>
                                    <input class="form-control input-lg" id="verEstatus" name="verEstatus" readonly>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA DESCRIPCÍON -->
                        <div class="col-lg-12">  
                            <div class="form-group">
                                <div class="input-group">
                                    <label><small style="color: #000;">Descripcíon:</small>
                                        <textarea type="text" class="form-control input-lg" name="verDescripcion"
                                                  id="verDescripcion" placeholder="Sin descripción" style="resize: none;" readonly></textarea>
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
    $eliminarCuentaCobrar = new ControllerCuentasCobrar();
    $eliminarCuentaCobrar->ctrEliminarCuentaCobrar();
?>



