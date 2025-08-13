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
            Configurar perfiles
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i>Inicio</a></li>
            <li><a href="configuracion"></i>Configuración</a>
            <li><a href="config-perfil"></i>Perfiles</a>
            <li class="active">Configurar perfiles</li>
        </ol>

    </section>
    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <ul class="nav nav-tabs nav-justified">
                <li role="presentation"><a href="config-empresa">Empresa</a></li>
                <li role="presentation" class="active"><a href="config-perfil">Perfiles</a></li>
                <li role="presentation"><a href="config-usuarios">Usuarios</a></li>
                <li role="presentation"><a href="config-divisas">Divisas</a></li>
            </ul>

            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
                    <thead>
                    <tr>
                        <th rowspan="2" style="width:10px">#</th>
                        <th rowspan="2">Perfiles</th>
                    </tr>
                    <tr>
                        <th>Mantenimiento</th>
                        <th>Inventario</th>
                        <th>Compras</th>
                        <th>Ventas</th>
                        <th>R. Venta</th>
                        <th>Configuración</th>
                        <th>POS</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $item = null;
                    $valor = null;
                    $perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
                    /*var_dump($perfil);*/
                    foreach ($perfil as $key => $value):
                        echo '
                        <tr>
                            <td>' . ($key + 1) . '</td>
                            <td>' . $value["perfil"] . '</td>';

                        if ($value["mantenimiento"] != "N") {
                            echo '
                            <td style="width: 20px;">
                            <button class="btn btn-success btn-xs btnMantenimiento" idMantenimiento=' . $value["id_perfil"] . '" estadoMantenimiento="N">Mantenimiento</button>
                            </td>';
                        }else {
                            echo '
                            <td style="width: 20px;">
                            <button class="btn btn-danger btn-xs btnMantenimiento" idMantenimiento="' . $value["id_perfil"] . '"estadoMantenimiento="S">Mantenimiento</button>
                            </td>';
                        }

                        if ($value["inventario"] != "N") {
                            echo '
                            <td style="width: 20px;">
                            <button class="btn btn-success btn-xs btnInventario" idInventario=' . $value["id_perfil"] . '" estadoInventario="N">Almacén</button>
                            </td>';
                        }else {
                            echo '
                            <td style="width: 20px;">  
                            <button class="btn btn-danger btn-xs btnInventario" idInventario=' . $value["id_perfil"] . '" estadoInventario="S">Almacén</button>            
                            </td>';
                        }

                        if ($value["compras"] != "N") {
                            echo '
                            <td style="width: 20px;">
                            <button class="btn btn-success btn-xs btnCompras" idCompra=' . $value["id_perfil"] . '" estadoCompra="N">Compras</button>
                            </td>';
                        }else {
                            echo '
                            <td style="width: 20px;">  
                            <button class="btn btn-danger btn-xs btnCompras" idCompra=' . $value["id_perfil"] . '" estadoCompra="S">Compras</button>
                            </td>';
                        }

                        if ($value["ventas"] != "N") {
                            echo '
                            <td style="width: 20px;">
                            <button class="btn btn-success btn-xs btnVentas" idVenta=' . $value["id_perfil"] . '" estadoVenta="N">Ventas</button>
                            </td>';
                        }else {
                            echo '
                            <td style="width: 20px;">                         
                            <button class="btn btn-danger btn-xs btnVentas" idVenta=' . $value["id_perfil"] . '" estadoVenta="S">Ventas</button>                       
                            </td>';
                        }

                        if ($value["reporte_venta"] != "N") {
                            echo '
                            <td style="width: 20px;">
                            <button class="btn btn-success btn-xs btnReporteVenta" idReporteVenta=' . $value["id_perfil"] . '" estadoReporteVenta="N">Reporte de Ventas</button>
                            </td>';
                        }else {
                            echo '
                            <td style="width: 20px;">                         
                            <button class="btn btn-danger btn-xs btnReporteVenta" idReporteVenta=' . $value["id_perfil"] . '" estadoReporteVenta="S">Reporte de Ventas</button>                       
                            </td>';
                        }

                        if ($value["configuracion"] != "N") {
                            echo '
                            <td style="width: 20px;">
                            <button class="btn btn-success btn-xs btnConfiguracion" idConfiguracion=' . $value["id_perfil"] . '" estadoConfiguracion="N">Configuración</button>
                            </td>';
                        }else {
                            echo '
                            <td style="width: 20px;">                         
                            <button class="btn btn-danger btn-xs btnConfiguracion" idConfiguracion=' . $value["id_perfil"] . '" estadoConfiguracion="S">Configuración</button>                       
                            </td>';
                        }

                        if ($value["caja"] != "N") {
                            echo '
                            <td style="width: 20px;">
                            <button class="btn btn-success btn-xs btnCaja" idCaja=' . $value["id_perfil"] . '" estadoCaja="N">POS</button>
                            </td>';
                        }else {
                            echo '
                            <td style="width: 20px;">                         
                            <button class="btn btn-danger btn-xs btnCaja" idCaja=' . $value["id_perfil"] . '" estadoCaja="S">POS</button>                       
                            </td>';
                        }

                        echo '</tr>';
                    endforeach;
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>