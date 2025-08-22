<?php
// =====================================================================
// 1. VERIFICACIÓN DE PERMISOS DE USUARIO
// =====================================================================
// Este bloque comprueba si el perfil del usuario actual tiene permiso
// para acceder a la sección de inventario. Si no, lo redirige.
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
    <!--=====================================
    CABECERA DE LA PÁGINA
    ======================================-->
    <section class="content-header">
        <h1>
            Ajustes de Inventario
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Ajustes de inventario</li>
        </ol>
    </section>

    <!--=====================================
    CONTENIDO PRINCIPAL
    ======================================-->
    <section class="content">
        <div class="box">
            <!-- BOTÓN PARA ABRIR EL MODAL DE NUEVO AJUSTE -->
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAjusteInventario">
                    Nuevo Ajuste de Inventario
                </button>
            </div>
            <!-- TABLA QUE MUESTRA LOS AJUSTES REALIZADOS -->
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Fecha</th>
                        <th>Almacén</th>
                        <th>Usuario Responsable</th>
                        <th>Descripción / Motivo</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        // =====================================================================
                        // 2. CARGA DINÁMICA DE LOS AJUSTES GUARDADOS
                        // =====================================================================
                        // Se llama al controlador para traer todos los ajustes de la BD.
                        $item = null;
                        $valor = null;
                        $ajustes = ControllerAjustes::ctrMostrarAjustes($item, $valor);
                        
                        foreach ($ajustes as $key => $value):
                    ?>
                        <tr>
                            <td><?php echo $value["id_ajuste"]; ?></td>
                            <td><?php echo date("d/m/Y H:i A", strtotime($value["fecha_ajuste"])); ?></td>
                            <td><?php echo $value["nombre_almacen"]; ?></td>
                            <td><?php 
                                // Para mostrar el nombre del usuario, necesitarás una función similar
                                // a la de mostrar perfiles que busque un usuario por su ID.
                                // Ejemplo: $usuario = ControllerUsuarios::ctrMostrarUsuarios("id_usuario", $value["id_usuario"]);
                                // echo $usuario["nombre"];
                                echo "ID Usuario: ".$value["id_usuario"]; // Solución temporal
                            ?></td>
                            <td><?php echo htmlspecialchars($value["descripcion"]); ?></td>
                            <td>
                                <div class="btn-group">
                                    <!-- Botón para ver el detalle del ajuste (funcionalidad a futuro) -->
                                    <button class="btn btn-primary btnVerAjuste" idAjuste="<?php echo $value["id_ajuste"]; ?>"><i class="fa fa-eye"></i></button>
                                    
                                    <!-- NOTA: Eliminar un ajuste es complejo porque implicaría revertir el stock,
                                         lo cual puede causar inconsistencias. Es mejor no permitirlo o hacerlo
                                         con un proceso de "contra-ajuste". Por ahora, lo dejamos deshabilitado. -->
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!--=====================================
MODAL AGREGAR AJUSTE DE INVENTARIO
======================================-->
<div id="modalAjusteInventario" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">

            <!-- El formulario apunta a la misma página para ser procesado por el controlador -->
            <form role="form" method="post" class="formulario__AjusteInventario">

                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar ajuste de inventario</h4>
                </div>

                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- FILA 1: DATOS GENERALES DEL AJUSTE -->
                        <div class="row">
                            <!-- ENTRADA PARA LA FECHA -->
                            <div class="form-group col-md-4">
                                <label>Fecha:</label>
                                <div class="input-group">
                                    <input type="datetime-local" class="form-control input-lg" name="nuevaFecha" value="<?php echo date("Y-m-d\TH:i"); ?>" required>
                                </div>
                            </div>
                            <!-- ENTRADA PARA EL ALMACÉN -->
                            <div class="form-group col-md-8">
                                <label>Almacén:</label>
                                <div class="input-group" style="width: 100%;">
                                    <select class="form-control input-lg" id="nuevoAlmacen" name="nuevoAlmacen" required>
                                        <option value="">Seleccionar Almacén</option>
                                        <?php
                                        $item = null;
                                        $valor = null;
                                        $almacenes = ControllerAlmacenes::ctrMostrarAlmacen($item, $valor);
                                        foreach ($almacenes as $key => $value) {
                                            echo '<option value="' . $value["id_almacen"] . '">' . $value["nombre"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- ENTRADA PARA LA DESCRIPCIÓN -->
                            <div class="form-group col-md-12">
                                <label>Descripción / Motivo del ajuste:</label>
                                <div class="input-group" style="width: 100%;">
                                    <textarea class="form-control" name="nuevaDescripcion" placeholder="Ej: Mercancía dañada, corrección de conteo, etc." style="resize: vertical;" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <hr>

                        <!-- FILA 2: PRODUCTOS A AJUSTAR -->
                        <div class="row">
                            <div class="col-xs-12">
                                <button type="button" class="btn btn-default btn-sm btnAjusteInventario" style="margin-bottom: 15px;">
                                    <i class="fa fa-plus"></i> Agregar Producto
                                </button>
                                
                                <!-- Tabla para los productos del ajuste -->
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 35%">Producto</th>
                                            <th style="width: 15%">Stock Actual</th>
                                            <th style="width: 20%">Tipo Ajuste</th>
                                            <th style="width: 15%">Cantidad</th>
                                            <th style="width: 15%">Stock Final</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="nuevoAjusteProducto">
                                        <!-- Las filas de productos se agregarán aquí con JavaScript -->
                                    </tbody>
                                </table>

                                <!-- CAMPO OCULTO: Este campo es CRUCIAL. Guardará un string JSON
                                     con la lista de todos los productos para enviarlo al controlador. -->
                                <input type="hidden" id="listaProductos" name="listaProductos">
                            </div>
                        </div>
                    </div>
                </div>

                <!--=====================================
                PIE DEL MODAL
                ======================================-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Ajuste</button>
                </div>
                
                <?php
                    // =====================================================================
                    // 3. LLAMADA AL CONTROLADOR PARA PROCESAR EL FORMULARIO
                    // =====================================================================
                    // Cuando el formulario se envía (POST), este código se ejecuta.
                    $crearAjuste = new ControllerAjustes();
                    $crearAjuste->ctrCrearAjusteInventario();
                ?>

            </form>
        </div>
    </div>
</div>

<!-- =================================================================
MODAL PARA VER EL DETALLE DEL AJUSTE
================================================================== -->
<div id="modalVerDetalleAjuste" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- CABEZA DEL MODAL -->
            <div class="modal-header" style="background:#5bc0de; color:white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Detalles del Ajuste de Inventario</h4>
            </div>

            <!-- CUERPO DEL MODAL -->
            <div class="modal-body">
                <div class="box-body">
                    <!-- Aquí mostraremos la información general del ajuste -->
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-6">
                            <strong>Ajuste ID:</strong> <span id="verIdAjuste"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Fecha:</strong> <span id="verFechaAjuste"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Almacén:</strong> <span id="verAlmacenAjuste"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Usuario:</strong> <span id="verUsuarioAjuste"></span>
                        </div>
                         <div class="col-md-12" style="margin-top: 10px;">
                            <strong>Motivo:</strong> <p id="verMotivoAjuste" style="white-space: pre-wrap;"></p>
                        </div>
                    </div>
                    
                    <!-- Tabla para los productos ajustados -->
                    <table id="tablaDetallesAjuste" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Stock Anterior</th>
                                <th>Tipo de Ajuste</th>
                                <th>Cantidad</th>
                                <th>Stock Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Las filas con los detalles se cargarán aquí con AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PIE DEL MODAL -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>