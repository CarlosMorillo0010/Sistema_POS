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
                        <th>Tasa del Día</th>
                        <th>Sub Total $</th>
                        <th>IVA $</th>
                        <th>Total $</th>
                        <th>Sub Total Bs</th>
                        <th>IVA Bs</th>
                        <th>Total Bs</th>
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
                        
                        echo '<tr>
                                <td>' . ($key + 1) . '</td>
                                <td>' . htmlspecialchars($value["codigo_venta"]) . '</td>';

                                // 1. OBTENER Y VERIFICAR EL CLIENTE
                                // **CORRECCIÓN**: El ID del cliente está en $value["id_cliente"], no en id_usuario.
                                $itemCliente = "id";
                                $valorCliente = $value["id_cliente"];
                                $respuestaCliente = ControllerClients::ctrMostrarClientes($itemCliente, $valorCliente);
                                
                                echo '<td>' . ($respuestaCliente ? htmlspecialchars($respuestaCliente["nombre"]) : 'Cliente no encontrado') . '</td>';
                        
                                // 2. OBTENER Y VERIFICAR EL VENDEDOR
                                $itemUsuario = "id_usuario";
                                $valorUsuario = $value["id_vendedor"];
                                $respuestaUsuario = ControllerUsers::ctrMostrarUsuario($itemUsuario, $valorUsuario);
                        
                                echo '<td>' . ($respuestaUsuario ? htmlspecialchars($respuestaUsuario["nombre"]) : 'Vendedor no encontrado') . '</td>';
                                
                                // 3. MOSTRAR LOS NUEVOS CAMPOS MONETARIOS
                                // Se usa number_format para que los números se vean bien (ej: 1,234.56)
                                echo '<td>' . number_format($value["tasa_dia"], 2, ',', '.') . '</td>
                                      <td>' . number_format($value["subtotal_usd"], 2, ',', '.') . '</td>
                                      <td>' . number_format($value["iva_usd"], 2, ',', '.') . '</td>
                                      <td style="font-weight:bold;">' . number_format($value["total_usd"], 2, ',', '.') . '</td>
                                      <td>' . number_format($value["subtotal_bs"], 2, ',', '.') . '</td>
                                      <td>' . number_format($value["iva_bs"], 2, ',', '.') . '</td>
                                      <td style="font-weight:bold;">' . number_format($value["total_bs"], 2, ',', '.') . '</td>
                                      <td>' . date("d/m/Y H:i", strtotime($value["fecha"])) . '</td>

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
