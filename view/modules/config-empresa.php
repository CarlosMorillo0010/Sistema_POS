<?php
if ($_SESSION["perfil"] != "ADMINISTRADOR") {
    echo '<script>window.location = "inicio";</script>';
    return;
}
$configuracion = ControllerConfiguraciones::ctrMostrarConfiguracion();
// Si es la primera vez, $configuracion puede ser false, lo convertimos a array para evitar errores.
if(!$configuracion) $configuracion = []; 
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Configuración del Sistema</h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Configuración</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <ul class="nav nav-tabs nav-justified">
                <li role="presentation" class="active"><a href="config-empresa">Empresa e Impuestos</a></li>
                <li role="presentation"><a href="config-divisas">Divisas y Tasa de Cambio</a></li>
                <li role="presentation"><a href="config-perfil">Perfiles</a></li>
                <li role="presentation"><a href="config-usuarios">Usuarios</a></li>
            </ul>
            <div class="box-body">
                <form role="form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="idConfiguracion" value="<?php echo $configuracion['id'] ?? ''; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Datos de la Empresa</h4><hr>
                            <div class="form-group"><label>Nombre de la empresa:</label><input type="text" class="form-control" name="nombreEmpresa" placeholder="Nombre de la Empresa" value="<?php echo htmlspecialchars($configuracion['nombre'] ?? ''); ?>" required></div>
                            <div class="form-group"><label>RIF de la empresa:</label><input type="text" class="form-control" name="rifEmpresa" placeholder="Ej: J-12345678-9" value="<?php echo htmlspecialchars($configuracion['rif'] ?? ''); ?>" pattern="[JVEGjvge]-[0-9]{8,9}-[0-9]" title="El formato debe ser J-12345678-9" required></div>
                            <div class="form-group"><label>Teléfono:</label><input type="text" class="form-control" name="telefonoEmpresa" placeholder="Teléfono" value="<?php echo htmlspecialchars($configuracion['telefono'] ?? ''); ?>"></div>
                            <div class="form-group"><label>Correo electrónico:</label><input type="email" class="form-control" name="emailEmpresa" placeholder="Correo Electrónico" value="<?php echo htmlspecialchars($configuracion['email'] ?? ''); ?>"></div>
                            <div class="form-group"><label>Dirección:</label><textarea class="form-control" rows="3" name="direccionEmpresa" placeholder="Dirección Fiscal Completa"><?php echo htmlspecialchars($configuracion['direccion'] ?? ''); ?></textarea></div>
                        </div>
                        <div class="col-md-6">
                            <h4>Configuración Financiera y Fiscal</h4><hr>
                            <div class="row">
                                <div class="col-sm-6 form-group"><label>IVA (%):</label><div class="input-group"><input type="number" class="form-control" name="iva" min="0" step="0.01" value="<?php echo $configuracion['iva'] ?? '16.00'; ?>" required><span class="input-group-addon">%</span></div></div>
                                <div class="col-sm-6 form-group"><label>IGTF (%):</label><div class="input-group"><input type="number" class="form-control" name="igtf" min="0" step="0.01" value="<?php echo $configuracion['igtf'] ?? '3.00'; ?>" required><span class="input-group-addon">%</span></div></div>
                            </div>
                            <div class="form-group">
                                <label>Moneda Principal del Sistema:</label>
                                <select class="form-control" name="monedaPrincipalId" required>
                                    <option value="">Seleccione una moneda</option>
                                    <?php
                                    $divisas = ControllerDivisas::ctrMostrarDivisa(null, null);
                                    foreach ($divisas as $divisa) {
                                        $selected = (isset($configuracion["moneda_principal_id"]) && $divisa["id"] == $configuracion["moneda_principal_id"]) ? 'selected' : '';
                                        echo '<option value="'.$divisa["id"].'" '.$selected.'>'.htmlspecialchars($divisa["nombre"]).' ('.htmlspecialchars($divisa["simbolo"]).')</option>';
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Esta será la moneda usada para los reportes principales y cálculos base.</p>
                            </div>
                            <div class="form-group">
                                <label>Logo de la Empresa</label>
                                <img src="<?php echo (!empty($configuracion['logo'])) ? $configuracion['logo'] : 'views/img/config/default/anonymous.png'; ?>" class="img-thumbnail previsualizar" width="150px">
                                <input type="file" class="imagenEmpresa" name="imagenEmpresa" style="margin-top: 10px;">
                                <input type="hidden" name="logoActual" value="<?php echo $configuracion['logo'] ?? ''; ?>">
                                <p class="help-block">Peso máximo 2MB. Formato JPG o PNG.</p>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Guardar Configuración</button>
                    </div>
                </form>
                <?php
                    $guardarConfiguracion = new ControllerConfiguraciones();
                    $guardarConfiguracion->ctrGuardarConfiguracion();
                ?>
            </div>
        </div>
    </section>
</div>
