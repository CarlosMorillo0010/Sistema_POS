<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Administrar Almacenes
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar almacenes</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProducto">
                    Agregar Almacen
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

<!-- Modal Registrar Producto -->
<div id="modalAgregarProducto" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post" enctype="multipart/form-data">
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Agregar Producto</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <!-- Codigo del Producto -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-code"></i></span>
                                <input type="text" class="form-control input-lg" name="codigo" placeholder="Código" required>
                            </div>
                        </div>
                        <!-- Entrada para Descripción -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>
                                <input type="text" class="form-control input-lg" name="descripcion" placeholder="Nombre del Producto" required>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <!-- Entrada de Categoria -->
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                    <select class="form-control input-lg" name="categoria">
                                        <option value="">Categoria</option>
                                        <option value="Taladros">Taladros</option>
                                        <option value="Andamios">Andamios</option>
                                        <option value="Martillos">Martillos</option>
                                    </select>
                                </div>
                            </div>
                        
                            <!-- Entrada para Stock -->
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-check"></i></span>
                                    <input type="number" class="form-control input-lg" name="stock" min="0" placeholder="Stock" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <!-- Entrada para Precio Compra -->
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span>
                                    <input type="number" class="form-control input-lg" name="precioCompra" min="0" placeholder="Precio Compra" required>
                                </div>
                            </div>
                            <!-- Entrada para Precio Venta -->
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>
                                    <input type="number" class="form-control input-lg" name="precioVenta" min="0" placeholder="Precio Venta" required>
                                </div>
                                <br>
                                <!-- CHECK PARA PORCENTAJE -->
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" class="minimal porcentaje" checked>
                                            Utilizar porcentaje
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-6" style="padding: 0">
                                    <div class="input-group">
                                        <input type="number" class="form-control input-lg nuevoPorcentaje" min="0" value="40" required>
                                        <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Entrada para Subir Imagen -->
                        <div class="form-group">
                            <div class="panel">SUBIR IMAGEN</div>
                            <input type="file" id="imagen" name="imagen">
                            <p class="help-block">Peso máximo de la imgen 2MB</p>
                            <img src="view/img/products/default/anonymous.png" class="img-thumbnail" width="100px">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
