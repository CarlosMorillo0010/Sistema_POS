<?php
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value):
    if ($_SESSION["perfil"] == $value["perfil"] && $value["inventario"] == "N"){
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
            Administrar categorías
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar categorías</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarCategoria">
                    Agregar categoría
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Imagen</th>
                        <th>Categoria</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $item = null;
                    $valor = null;
                    $categorias = ControllerCategories::ctrMostrarCategoria($item, $valor);

                    foreach ($categorias as $key => $value) {
                        echo '<tr>
                        <td>' . ($key + 1) . '</td>
                        <td><img src="'. $value["imagen"] .'" class="img-thumbnail" width="40px"></td>
                        <td>' . $value["categoria"] . '</td>
                        <td>
                            <div class="btn-group">                  
                                <button class="btn btn-warning btnEditarCategoria" idCategoria="' . $value["id_categoria"] . '" data-toggle="modal" data-target="#modalEditarCategoria"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-danger btnEliminarCategoria" idCategoria="' . $value["id_categoria"] . '"><i class="fa fa-times"></i></button>
                            </div>  
                        </td>
                    </tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!--=====================================
MODAL AGREGAR CATEGORIA
======================================-->
<div id="modalAgregarCategoria" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCategoria" role="form" method="post" enctype="multipart/form-data">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar categoría</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="input-group">
                                <label for="categorias" style="color: red;"> * <small style="color: #000;">Categoria:</small>
                                    <input type="text" class="form-control input-lg" id="categori" name="nuevaCategoria"
                                           placeholder="Ingresar categoría" required>
                                </label>
                            </div>
                        </div>
                        <!-- Entrada para Subir Imagen -->
                        <div class="form-group">
                            <div class="panel">SUBIR IMAGEN</div>
                            <input type="file" class="nuevaImagen" name="nuevaImagen">
                            <p class="help-block">Peso máximo de la imgen 2MB</p>
                            <img src="view/img/categories/default/anonymous.png" class="img-thumbnail mostrar"
                                 width="100px">
                        </div>
                    </div>
                    <span class="col-lg-12" style="color: red;">( * ) Campo obligatorio</span>
                </div>

                <!--=====================================
                PIE DEL MODAL
                ======================================-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php
                $crearCategoria = new ControllerCategories();
                $crearCategoria->ctrCrearCategoria();
                ?>
            </form>
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR CATEGORIA
======================================-->
<div id="modalEditarCategoria" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar categoría</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="form-group">
                            <div class="input-group">
                                <label><small style="color: #000;">Categoria:</small>
                                    <input type="text" class="form-control input-lg" name="editarCategoria"
                                        id="editarCategoria" required>
                                    <input type="hidden" name="idCategoria" id="idCategoria" required>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!--=====================================
                PIE DEL MODAL
                ======================================-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php
                $editarCategoria = new ControllerCategories();
                $editarCategoria->ctrEditarCategoria();
                ?>
            </form>
        </div>
    </div>
</div>
<?php
$borrarCategoria = new ControllerCategories();
$borrarCategoria->ctrBorrarCategoria();
?> 




