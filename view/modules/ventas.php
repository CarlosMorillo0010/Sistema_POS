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
            Administrar ventas
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar ventas</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <a href="caja">
                    <button class="btn btn-primary hidden-xs">
                        Crear venta
                    </button>
                </a>
                <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                    <span>
                        <i class="fa fa-calendar"></i> Rango de fecha
                    </span>
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
                    <thead>
                    <tr>
                        <th style="width: 10px;">#</th>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Forma de Pago</th>
                        <th>Total a Pagar</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (isset($_GET["fechaInicial"])) {
                        $fechaInicial = $_GET["fechaInicial"];
                        $fechaFinal = $_GET["fechaFinal"];
                    } else {
                        $fechaInicial = null;
                        $fechaFinal = null;
                    }

                    $respuesta = ControllerVentas::ctrRangoFechasVentas($fechaInicial, $fechaFinal);

                    foreach ($respuesta as $key => $value) {
                        $length = 10;
                        echo '<tr>
                                <td>' . ($key + 1) . '</td>
                                <td>' . $value["codigo_venta"] . '</td>';

                                $itemCliente = "id";
                                $valorCliente = $value["id_cliente"];
                                $respuestaCliente = ControllerClients::ctrMostrarClientes($itemCliente, $valorCliente);
                                echo '<td>' . $respuestaCliente["nombre"] . '</td>';

                                $itemUsuario = "id_usuario";
                                $valorUsuario = $value["id_vendedor"];
                                $respuestaUsuario = ControllerUsers::ctrMostrarUsuario($itemUsuario, $valorUsuario);
                                echo '<td>' . $respuestaUsuario["nombre"] . '</td>
                                
                                <td>' . $value["metodo_pago"] . '</td>
                                <td>' . number_format($value["total"], 2) . '</td>
                                <td>' . $value["fecha"] . '</td>

                                <td>

                                    <div class="btn-group">

                                        <button class="btn btn-info btnImprimirFactura" codigoVenta="'.$value["codigo_venta"].'" style="background-color: #00c0ef !important;">

                                        <i class="fa fa-print"></i>

                                        </button>

                                    </div>

                                </td>
                                
                            </tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
