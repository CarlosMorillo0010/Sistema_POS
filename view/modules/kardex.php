<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Kardex de Inventario Valorizado
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
                <h3 class="box-title">Filtros de Búsqueda</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Producto:</label>
                            <select class="form-control select2" id="seleccionarProductoKardex" name="id_producto" required>
                                <option value="">Seleccione un producto</option>
                                <?php
                                $productos = ControllerProducts::ctrMostrarProductos(null, null, "descripcion");
                                foreach ($productos as $producto) {
                                    echo '<option value="' . $producto["id_producto"] . '">' . $producto["descripcion"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Rango de Fechas:</label>
                            <button type="button" class="btn btn-default pull-right" id="daterange-btn-kardex">
                                <span>
                                <i class="fa fa-calendar"></i> Rango de fecha
                                </span>
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Reporte de Kardex</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dt-responsive tablaKardex" width="100%">
                        <thead>
                            <tr>
                                <th rowspan="2" style="vertical-align: middle; text-align: center;">#</th>
                                <th rowspan="2" style="vertical-align: middle; text-align: center;">Fecha</th>
                                <th rowspan="2" style="vertical-align: middle; text-align: center;">Documento</th>
                                <th rowspan="2" style="vertical-align: middle; text-align: center;">Concepto</th>
                                <th colspan="3" style="text-align: center;">Entradas</th>
                                <th colspan="3" style="text-align: center;">Salidas</th>
                                <th colspan="3" style="text-align: center;">Saldos</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">Cant.</th>
                                <th style="text-align: center;">Costo U.</th>
                                <th style="text-align: center;">Costo T.</th>
                                <th style="text-align: center;">Cant.</th>
                                <th style="text-align: center;">Costo U.</th>
                                <th style="text-align: center;">Costo T.</th>
                                <th style="text-align: center;">Cant.</th>
                                <th style="text-align: center;">Costo U.</th>
                                <th style="text-align: center;">Costo T.</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
