<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Administrar Articulos
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar articulos</li>
        </ol>
    </section>
    
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarArticulo">
                    Agregar articulo
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped td-responsive tablaArticulos" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 10px;">#</th>
                            <th>Imagen</th>
                            <th>Nombre del articulo</th>
                            <th>Marca</th>
                            <th>Unidades</th>
                            <th>Precio de compra</th>
                            <th>Precio de venta</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!--=====================================
    MODAL REGISTRAR ARTICULO
======================================-->
<div id="modalAgregarArticulo" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post" enctype="multipart/form-data">
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Agregar articulo</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <!-- Entrada de marca -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Marca:</small>
                                    <select class="form-control input-lg" id="nuevaMarca" name="nuevaMarca" required>
                                        <option value="0">Marca</option>
                                        <?php
                                            $item = null;
                                            $valor = null;
                                            $marcas = ControllerMarcas::ctrMostrarMarca($item, $valor);

                                            foreach ($marcas as $key => $value) {
                                                echo '<option value="'.$value["id_marca"].'">'.$value["marca"].'</option>';
                                            }
                                        ?>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <!-- Entrada para Nombre -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Nombre del articulo:</small>
                                <input type="text" class="form-control input-lg" id="nuevoArticulo" name="nuevoArticulo"
                                       placeholder="Nombre del articulo" required>
                                </label>
                            </div>
                        </div>
                        <!-- Entrada para unidades -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Unidades:</small>
                                <input type="text" class="form-control input-lg" name="nuevaUnidades" min="0"
                                        placeholder="Unidades" required>
                                </label>
                            </div>
                        </div>
                        <!-- Entrada para Precio Compra -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Precio Compra:</small>
                                <input type="text" class="form-control input-lg" id="nuevoPrecioCompra"
                                    name="nuevoPrecioCompra" min="0" step="any" placeholder="Precio Compra"
                                    required>
                                </label>
                            </div>
                        </div>
                        <!-- Entrada para Precio Venta -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Precio Venta:</small>
                                <input type="text" class="form-control input-lg" id="nuevoPrecioVenta"
                                        name="nuevoPrecioVenta" min="0" step="any" placeholder="Precio Venta"
                                        required>
                                </label>
                            </div>
                        </div>
                            <br>
                            <!-- CHECK PARA PORCENTAJE -->
                            <div class="form-group col-lg-6">

                                <div class="input-group">
                                    <small style="color: #000;">Porcentaje:</small>
                                    <input type="text" class="form-control input-lg nuevoPorcentaje" min="0"
                                            value="40" required>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" class="minimal porcentaje" checked>
                                        Utilizar procentaje
                                    </label>
                                </div>

                            </div>
                        <!-- Entrada para Subir Imagen -->
                        <div class="form-group col-lg-6">
                            <div>Subir imagen</div>
                            <input type="file" class="imagenArticulo" name="imagenArticulo">
                            <p class="help-block">Peso máximo de la imgen 2MB</p>
                            <img src="view/img/articulos/default/anonymous.png" class="img-thumbnail previsualizar"
                                 width="100px">
                        </div>
                    </div>

                    <span class="col-lg-12" style="color: red;">( * ) Campos obligatorios</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
            <?php
            $crearArticulo = new ControllerArticulos();
            $crearArticulo->ctrCrearArticulo();
            ?>
        </div>
    </div>
</div>

<!--=====================================
    MODAL EDITAR ARTICULOS
======================================-->
<div id="modalEditarArticulo" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post" enctype="multipart/form-data">
                <div class="modal-header" style="background: #3c8dbc; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Editar Articulo</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">

                    <div class="form-group row">
                        <!-- Entrada de Marca -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label> <small style="color: #000;">Marca:</small>
                                    <select class="form-control input-lg" name="editarMarca" readonly required>
                                        <option id="editarMarca"></option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <!-- Entrada para el nombre -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label> <small style="color: #000;">Nombre del articulo:</small>
                                    <input type="text" class="form-control input-lg" id="editarArticulo"
                                       name="editarArticulo" required>
                                </label>
                            </div>
                        </div>


                        <!-- Entrada para la unidad -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label> <small style="color: #000;">Unidades:</small>
                                    <input type="number" class="form-control input-lg" id="editarUnidades"
                                        name="editarUnidades" min="0" required>
                                </label>
                            </div>
                        </div>

                        <!-- Entrada para Precio Compra -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label> <small style="color: #000;">Precio compra:</small>
                                    <input type="number" class="form-control input-lg" id="editarPrecioCompra"
                                        name="editarPrecioCompra" min="0" step="any" required>
                                </label>
                            </div>
                        </div>

                        <!-- Entrada para Precio Venta -->
                        <div class="form-group col-lg-6">
                            <div class="input-group">
                                <label> <small style="color: #000;">Precio venta:</small>
                                    <input type="number" class="form-control input-lg" id="editarPrecioVenta"
                                        name="editarPrecioVenta" min="0" step="any" readonly required>
                                </label>
                            </div>
                        </div>

                            <!-- CHECK PARA PORCENTAJE -->
                        <div class="form-group col-lg-6">

                            <div class="input-group">
                                <small style="color: #000;">Porcentaje:</small>
                                <input type="number" class="form-control input-lg editarPorcentaje" min="0"
                                        value="40" required>
                            </div>

                            <div class="form-group">
                                <input type="checkbox" class="minimal porcentaje" checked>
                                Utilizar procentaje
                            </div>
                        
                        </div>

                        <!-- Entrada para Subir Imagen -->
                        <div class="form-group col-lg-6">
                            <div>Subir imagen</div>
                            <input type="file" class="imagenArticulo" name="editArticulo">
                            <p class="help-block">Peso máximo de la imgen 2MB</p>
                            <img src="view/img/articulos/default/anonymous.png" class="img-thumbnail previsualizar"
                                 width="100px">
                            <input type="hidden" name="imagenActual" id="imagenActual">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
            <?php
            $editarArticulo = new ControllerArticulos();
            $editarArticulo->ctrEditarArticulo();
            ?>
        </div>
    </div>
</div>
<?php
    $eliminarArticulo = new ControllerArticulos();
    $eliminarArticulo->ctrEliminarArticulo();
?>

