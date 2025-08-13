<?php
if ($_SESSION["perfil"] != "ADMINISTRADOR") {
    echo '<script>window.location = "inicio";</script>';
    return;
}
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Divisas y Tasa de Cambio</h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li><a href="empresa">Configuración</a></li>
            <li class="active">Divisas</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <ul class="nav nav-tabs nav-justified">
                <li role="presentation"><a href="config-empresa">Empresa e Impuestos</a></li>
                <li role="presentation" class="active"><a href="config-divisas">Divisas y Tasa de Cambio</a></li>
                <li role="presentation"><a href="config-perfil">Perfiles</a></li>
                <li role="presentation"><a href="config-usuarios">Usuarios</a></li>
            </ul>
            <div class="box-header with-border" style="padding: 20px;">
                <button class="btn btn-primary" id="btnActualizarTasaBCV">
                    <i class="fa fa-refresh"></i> Consultar y Actualizar Tasa del BCV
                </button>
                <p class="help-block" style="margin-top:10px;">Esta acción consulta la tasa oficial del Dólar (USD) del BCV y la guarda para el día de hoy.</p>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                        <tr>
                            <th style="width:10px;">#</th>
                            <th>Divisa</th>
                            <th>Código</th>
                            <th>Símbolo</th>
                            <th>Tipo</th>
                            <th>Última Tasa Registrada (vs Bs.)</th>
                            <th>Fecha de Tasa</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $divisasConTasa = ControllerDivisas::ctrMostrarDivisasConTasa();
                        foreach($divisasConTasa as $key => $item) {
                            echo '<tr>
                                    <td>'.($key + 1).'</td>
                                    <td>'.htmlspecialchars($item["nombre"]).'</td>
                                    <td>'.htmlspecialchars($item["codigo"]).'</td>
                                    <td>'.htmlspecialchars($item["simbolo"]).'</td>
                                    <td>'.($item["es_local"] ? '<span class="label label-success">Local</span>' : '<span class="label label-warning">Extranjera</span>').'</td>';
                            
                            if($item["es_local"]) {
                                echo '<td>N/A</td><td>N/A</td>';
                            } else {
                                echo '<td>'.($item["ultima_tasa"] ? number_format($item["ultima_tasa"], 2, ',', '.') : 'Sin registro').'</td>
                                      <td>'.($item["fecha_tasa"] ? date("d/m/Y", strtotime($item["fecha_tasa"])) : 'Sin registro').'</td>';
                            }

                            echo '</tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>