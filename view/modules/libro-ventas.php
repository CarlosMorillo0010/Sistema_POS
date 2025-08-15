<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Libro de ventas
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Libro de ventas</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <!-- <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                    <span>
                        <i class="fa fa-calendar"></i> Rango de fecha
                    </span>
                </button> -->
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablaLibro" width="100%">
                    <thead>
                        <tr>
                            <th class="dt-center" style="width: 5%;">#</th>
                            <th class="dt-center">Facturas</th>
                            <th class="dt-center">Productos</th>
                            <th class="dt-center">Metodo</th>
                            <th class="dt-center">Neto</th>
                            <th class="dt-center">Impuesto</th>
                            <th class="dt-center">Total</th>
                            <th class="dt-center">Fecha</th>
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
                            
                            // 1. Decodificar el JSON de productos para obtener las descripciones
                            $productos = json_decode($value["productos"], true);
                        
                            // Verificamos si la decodificación fue exitosa
                            if (is_array($productos)) {
                                $descripciones = array();
                                foreach ($productos as $producto) {
                                    // Añadimos la cantidad y la descripción para más detalle
                                    $descripciones[] = htmlspecialchars($producto["cantidad"] . "x " . $producto["descripcion"]);
                                }
                                $descripcionProductos = implode("<br>", $descripciones); // Usamos <br> para saltos de línea
                            } else {
                                $descripcionProductos = "Error al leer productos";
                            }
                            
                            // 2. Imprimir la fila con los nuevos campos de la base de datos
                            echo '<tr>
                                <td class="dt-center" style="width: 5%;">' . ($key + 1) . '</td>
                                <td class="dt-center">' . htmlspecialchars($value["codigo_venta"]) . '</td>
                                <td class="dt-left">' . $descripcionProductos . '</td>
                                <td class="dt-center">' . htmlspecialchars($value["metodo_pago"]) . '</td>
                                <td class="dt-right">' . number_format($value["subtotal_usd"], 2, ',', '.') . '</td>
                                <td class="dt-right">' . number_format($value["iva_usd"], 2, ',', '.') . '</td>
                                <td class="dt-right" style="font-weight:bold;">' . number_format($value["total_usd"], 2, ',', '.') . '</td>
                                <td class="dt-center">' . date("d/m/Y H:i", strtotime($value["fecha"])) . '</td>
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