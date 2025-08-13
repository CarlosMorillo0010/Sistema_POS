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
            Administrar Ingresos
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar ingresos</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarIngreso">
                    Agregar Ingresos
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped tablas">
                    <thead>
                        <tr>
                            <th style="width: 10px;">#</th>
                            <th>Imagen</th>
                            <th>Código</th>
                            <th>Nombre del Producto </th>
                            <th>Categoría</th>
                            <th>Stock</th>
                            <th>Precio de Compra</th>
                            <th>Precio de Venta</th>
                            <th>Agregado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><img src="view/img/products/default/anonymous.png" class="img-thumbnail" width="40px"></td>
                            <td>0001</td>
                            <td>Lorem Ipsum dolor sit amet</td>
                            <td>Lorem Ipsum</td>
                            <td>20</td>
                            <td>Bs.S 20</td>
                            <td>Bs.S 40</td>
                            <td>2020-09-30 17:24:32</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-danger"><i class="fa fa-times"></i></button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>1</td>
                            <td><img src="view/img/products/default/anonymous.png" class="img-thumbnail" width="40px"></td>
                            <td>0001</td>
                            <td>Lorem Ipsum dolor sit amet</td>
                            <td>Lorem Ipsum</td>
                            <td>20</td>
                            <td>Bs.S 20</td>
                            <td>Bs.S 40</td>
                            <td>2020-09-30 17:24:32</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-danger"><i class="fa fa-times"></i></button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>1</td>
                            <td><img src="view/img/products/default/anonymous.png" class="img-thumbnail" width="40px"></td>
                            <td>0001</td>
                            <td>Lorem Ipsum dolor sit amet</td>
                            <td>Lorem Ipsum</td>
                            <td>20</td>
                            <td>Bs.S 20</td>
                            <td>Bs.S 40</td>
                            <td>2020-09-30 17:24:32</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-danger"><i class="fa fa-times"></i></button>
                                </div>
                            </td>
                        </tr>
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