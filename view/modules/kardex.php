<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Kardex de Inventario
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Kardex</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Seleccionar Producto y Rango de Fechas</h3>
            </div>
            <div class="box-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Producto:</label>
                                <select class="form-control select2" name="id_producto" required>
                                    <option value="">Seleccione un producto</option>
                                    <?php
                                    $productos = ControllerProducts::ctrMostrarProductos(null, null, "descripcion");
                                    foreach ($productos as $producto) {
                                        $selected = (isset($_POST['id_producto']) && $_POST['id_producto'] == $producto["id_producto"]) ? 'selected' : '';
                                        echo '<option value="' . $producto["id_producto"] . '" ' . $selected . '>' . $producto["descripcion"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha Inicial:</label>
                                <input type="date" class="form-control" name="fecha_inicial" value="<?php echo isset($_POST['fecha_inicial']) ? $_POST['fecha_inicial'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha Final:</label>
                                <input type="date" class="form-control" name="fecha_final" value="<?php echo isset($_POST['fecha_final']) ? $_POST['fecha_final'] : ''; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Generar Reporte</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php
        if (isset($_POST['id_producto']) && !empty($_POST['id_producto'])) {
            $itemProducto = "id_producto";
            $valorProducto = $_POST['id_producto'];
            
            $producto = ControllerProducts::ctrMostrarProductos($itemProducto, $valorProducto, "id_producto");

            $item = "id_producto";
            $valor = $_POST['id_producto'];
            $fechaInicial = !empty($_POST['fecha_inicial']) ? $_POST['fecha_inicial'] : null;
            $fechaFinal = !empty($_POST['fecha_final']) ? $_POST['fecha_final'] : null;

            $kardex = ControllerKardex::ctrMostrarKardex($item, $valor, $fechaInicial, $fechaFinal);
        ?>
            <style>
                .kardex-report-container {
                    background-color: #fff;
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 3px;
                }
                .kardex-table {
                    border-collapse: collapse;
                    width: 100%;
                }
                .kardex-table th, .kardex-table td {
                    border: 1px solid #e9e9e9;
                    padding: 8px;
                }
                .kardex-table thead th {
                    background-color: #f7f7f7;
                    text-align: center;
                    vertical-align: middle;
                }
                .kardex-table tbody tr:hover {
                    background-color: #f1f1f1;
                }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
                .font-bold { font-weight: bold; }
            </style>

            <div class="kardex-report-container">
                <!-- Info del Producto -->
                <?php if ($producto): ?>
                <div class="row" style="margin-bottom: 20px; font-size: 16px;">
                    <div class="col-md-4"><strong>Artículo:</strong> <?php echo $producto['descripcion']; ?></div>
                    <div class="col-md-4"><strong>Código:</strong> <?php echo $producto['codigo']; ?></div>
                    <div class="col-md-4"><strong>Método:</strong> PROMEDIO PONDERADO</div>
                </div>
                <?php else: ?>
                <div class="alert alert-danger">No se encontró el producto seleccionado.</div>
                <?php endif; ?>

                <?php if ($producto): ?>
                <div class="table-responsive">
                    <table class="table kardex-table" width="100%">
                        <thead>
                            <tr>
                                <th rowspan="2">Fecha</th>
                                <th rowspan="2">Detalle</th>
                                <th rowspan="2">V. Unitario</th>
                                <th colspan="2">Entradas</th>
                                <th colspan="2">Salidas</th>
                                <th colspan="2">Saldos</th>
                            </tr>
                            <tr>
                                <th>Cantidad</th>
                                <th>Valores</th>
                                <th>Cantidad</th>
                                <th>Valores</th>
                                <th>Cantidad</th>
                                <th>Valores</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($kardex) > 0) {
                                foreach ($kardex as $movimiento) {
                                    echo '<tr>';
                                    echo '<td class="text-center">' . $movimiento["fecha"] . '</td>';
                                    echo '<td>' . $movimiento["detalle"] . '</td>';
                                    echo '<td class="text-right">' . number_format($movimiento["v_unitario"], 2) . '</td>';
                                    
                                    echo '<td class="text-right">' . ($movimiento["entrada_cant"] ? number_format($movimiento["entrada_cant"], 2) : '') . '</td>';
                                    echo '<td class="text-right">' . ($movimiento["entrada_valor"] ? number_format($movimiento["entrada_valor"], 2) : '') . '</td>';

                                    echo '<td class="text-right">' . ($movimiento["salida_cant"] ? number_format($movimiento["salida_cant"], 2) : '') . '</td>';
                                    echo '<td class="text-right">' . ($movimiento["salida_valor"] ? number_format($movimiento["salida_valor"], 2) : '') . '</td>';

                                    echo '<td class="text-right font-bold">' . number_format($movimiento["saldo_cant"], 2) . '</td>';
                                    echo '<td class="text-right font-bold">' . number_format($movimiento["saldo_valor"], 2) . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="9" class="text-center">No se encontraron movimientos para el producto y rango de fechas seleccionados.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        <?php } ?>
    </section>
</div>
