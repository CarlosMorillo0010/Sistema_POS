<?php
require_once "model/ordenes-compras.model.php";
$esEditar = false;
if(isset($_GET["idOrden"])){
    $esEditar = true;
    $titulo = "Editar Orden de Compra";
    $idOrden = $_GET["idOrden"];
    $orden = ControllerOrdenesCompras::ctrMostrarOrdenCompra("id_orden_compra", $idOrden);
    $detalle = ModelOrdenesCompras::mdlMostrarOrdenCompraDetalle("orden_compra_detalle", "id_orden_compra", $idOrden);
} else {
    $titulo = "Crear Orden de Compra";
    $orden = null;
    $detalle = array();
}
?>

<div id="config-vars"
     data-tasa-bcv="<?php echo htmlspecialchars($tasaBCV, ENT_QUOTES, 'UTF-8'); ?>"
     data-iva-porcentaje="<?php echo htmlspecialchars($ivaPorcentaje, ENT_QUOTES, 'UTF-8'); ?>"
     data-moneda-principal="<?php echo htmlspecialchars($monedaPrincipal, ENT_QUOTES, 'UTF-8'); ?>"
     style="display: none;">
</div>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php echo $titulo; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li><a href="orden-compra">Administrar Órdenes</a></li>
            <li class="active"><?php echo $titulo; ?></li>
        </ol>
    </section>

    <section class="content">
        <form role="form" method="post" class="formulario-orden-compra">
            <?php if($esEditar): ?>
                <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">
            <?php endif; ?>
            <div class="row">
                <!-- Columna principal de la orden -->
                <div class="col-lg-8 col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Detalles de la Orden</h3>
                        </div>
                        <div class="box-body" style="height: 670px; overflow-y: auto;">
                            <div class="row">
                                <!-- Selección de Proveedor -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Proveedor:</label>
                                        <select class="form-control select2" name="idProveedor" id="seleccionarProveedor" required>
                                            <option value="">Seleccionar un proveedor</option>
                                            <?php
                                            $proveedores = ControllerProveedores::ctrMostrarProveedores(null, null);
                                            foreach ($proveedores as $key => $value) {
                                                $selected = ($orden != null && $orden["id_proveedor"] == $value["id_proveedor"]) ? "selected" : "";
                                                echo '<option value="'.$value["id_proveedor"].'" '.$selected.'>'.$value["nombre"].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Buscador de Productos -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Añadir Productos:</label>
                                        <select class="form-control select2" id="seleccionarProductoOC" name="seleccionarProductoOC">
                                            <option value="">Buscar producto por código o nombre</option>
                                             <?php
                                            $productos = ControllerProducts::ctrMostrarProductos(null, null, "id_producto");
                                            foreach ($productos as $key => $value) {
                                                echo '<option value="'.$value["id_producto"].'">'.$value["codigo"].' - '.$value["descripcion"].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabla de Productos -->
                            <table class="table table-bordered table-striped tabla-productos-orden">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">Quitar</th>
                                        <th>Producto</th>
                                        <th style="width: 120px">Cantidad</th>
                                        <th style="width: 150px">Precio Unit.</th>
                                        <th style="width: 150px">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="productos-orden">
                                    <?php if($esEditar): ?>
                                        <?php foreach($detalle as $item): ?>
                                            <tr data-idproducto="<?php echo $item['id_producto']; ?>">
                                                <td><button type="button" class="btn btn-danger btn-xs quitar-producto-oc"><i class="fa fa-times"></i></button></td>
                                                <td><?php echo $item['descripcion']; ?></td>
                                                <td><input type="number" class="form-control cantidad-oc" value="<?php echo $item['cantidad']; ?>" min="1"></td>
                                                <td><input type="number" class="form-control precio-oc" value="<?php echo $item['precio_compra']; ?>" min="0"></td>
                                                <td class="subtotal-oc-celda" style="font-weight:bold; text-align:right;"><?php echo $item['subtotal']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <input type="hidden" name="listaProductosOrden" id="listaProductosOrden">
                        </div>
                    </div>
                </div>

                <!-- Columna de resumen y totales -->
                <div class="col-lg-4 col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Resumen Financiero</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Subtotal:</label>
                                        <div class="input-group">
                                            <input style="margin-bottom: 20px;" type="text" class="form-control input-lg" name="subtotalOrden" id="subtotalOrden" value="<?php echo $esEditar ? $orden['subtotal'] : '0.00'; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IVA (%):</label>
                                        <div class="input-group">
                                            <input style="margin-bottom: 20px;" type="number" class="form-control input-lg" name="porcentajeImpuestos" id="porcentajeImpuestos" min="0" value="<?php echo $esEditar && isset($orden['subtotal']) && $orden['subtotal'] > 0 ? ($orden['impuestos'] / $orden['subtotal'] * 100) : (isset($ivaPorcentaje) ? $ivaPorcentaje : 0); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="form-group">
                                <label>Total IVA:</label>
                                <div class="input-group">
                                    <input style="margin-bottom: 20px;" type="text" class="form-control input-lg" name="totalImpuestos" id="totalImpuestos" value="<?php echo $esEditar ? $orden['impuestos'] : '0.00'; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Descuento ($):</label>
                                <div class="input-group">
                                    <input style="margin-bottom: 20px;" type="number" class="form-control input-lg" name="descuentoOrden" id="descuentoOrden" min="0" value="<?php echo $esEditar ? $orden['descuento'] : '0.00'; ?>">
                                </div>
                            </div>
                             <div class="form-group">
                                <label>Costo de Envío ($):</label>
                                <div class="input-group">
                                    <input style="margin-bottom: 20px;" type="number" class="form-control input-lg" name="envioOrden" id="envioOrden" min="0" value="<?php echo $esEditar ? $orden['costo_envio'] : '0.00'; ?>">
                                </div>
                            </div>
                            <hr>
                            <h4>
                                <strong>Total a Pagar:</strong>
                                <strong class="pull-right" id="totalFinalOrden">$ <?php echo $esEditar ? number_format($orden['total'], 2) : '0.00'; ?></strong>
                                <input type="hidden" name="totalOrden" id="totalOrdenInput" value="<?php echo $esEditar ? $orden['total'] : '0.00'; ?>">
                            </h4>
                        </div>
                        <div class="box-footer">
                            <div class="form-group">
                                <label>Términos de Pago:</label>
                                <input type="text" class="form-control" name="terminosPago" placeholder="Ej: Neto 30 días" value="<?php echo $esEditar ? $orden['terminos_pago'] : ''; ?>">
                            </div>
                             <div class="form-group">
                                <label>Notas Adicionales:</label>
                                <textarea style="height: 105px;" class="form-control" name="notasOrden" rows="3"><?php echo $esEditar ? $orden['notas'] : ''; ?></textarea>
                            </div>
                            <div class="btn-group btn-block">
                                <button style="width: 33.33%;" type="button" class="btn btn-default btn-lg" onclick="window.location.href='orden-compra'">Cancelar</button>
                                <button style="width: 33.33%;" type="submit" class="btn btn-primary btn-lg" name="action" value="guardar_borrador">Guardar Borrador</button>
                                <button style="width: 33.33%;" type="submit" class="btn btn-success btn-lg" name="action" value="crear_enviar"><?php echo $esEditar ? 'Actualizar y Enviar' : 'Crear y Enviar'; ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                $controller = new ControllerOrdenesCompras();
                if($esEditar){
                    $controller->ctrEditarOrdenCompra();
                } else {
                    $controller->ctrCrearOrdenCompra();
                }
            ?>
        </form>
    </section>
</div>

<script>
$(document).ready(function() {
    // Inicializar Select2
    $('#seleccionarProveedor').select2({ placeholder: "Buscar y seleccionar un proveedor", allowClear: true });
    $('#seleccionarProductoOC').select2({ placeholder: "Buscar un producto", allowClear: true });

    // Añadir producto a la tabla
    $('#seleccionarProductoOC').on('select2:select', function (e) {
        var idProducto = e.params.data.id;
        
        // Evitar añadir el mismo producto dos veces
        if($(".productos-orden").find(`[data-idproducto='${idProducto}']`).length > 0){
            $(this).val(null).trigger('change'); // Limpiar select2
            swal("Atención", "El producto ya ha sido agregado a la orden.", "warning");
            return;
        }

        var datos = new FormData();
        datos.append("idProducto", idProducto);

        $.ajax({
            url: "ajax/productos.ajax.php", // Asumo que tienes un ajax de productos que devuelve info por id
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta) {
                var newRow = `
                    <tr data-idproducto="${respuesta.id_producto}">
                        <td><button type="button" class="btn btn-danger btn-xs quitar-producto-oc"><i class="fa fa-times"></i></button></td>
                        <td>${respuesta.descripcion}</td>
                        <td><input type="number" class="form-control cantidad-oc" value="1" min="1"></td>
                        <td><input type="number" class="form-control precio-oc" value="${respuesta.precio_costo}" min="0"></td>
                        <td class="subtotal-oc-celda" style="font-weight:bold; text-align:right;">${respuesta.precio_costo}</td>
                    </tr>`;
                $(".productos-orden").append(newRow);
                actualizarCalculos();
                $(this).val(null).trigger('change'); // Limpiar select2
            }.bind(this)
        });
    });

    // Quitar producto de la tabla
    $(".productos-orden").on("click", ".quitar-producto-oc", function(){
        $(this).closest("tr").remove();
        actualizarCalculos();
    });

    // Actualizar cálculos al cambiar valores
    $(".formulario-orden-compra").on("change keyup", ".cantidad-oc, .precio-oc, #porcentajeImpuestos, #descuentoOrden, #envioOrden", function(){
        actualizarCalcululosFila(this);
        actualizarCalculos();
    });

    function actualizarCalcululosFila(element) {
        var fila = $(element).closest('tr');
        var cantidad = parseFloat(fila.find('.cantidad-oc').val()) || 0;
        var precio = parseFloat(fila.find('.precio-oc').val()) || 0;
        fila.find('.subtotal-oc-celda').text((cantidad * precio).toFixed(2));
    }

    function actualizarCalculos(){
        var subtotal = 0;
        $(".subtotal-oc-celda").each(function(){
            subtotal += parseFloat($(this).text()) || 0;
        });
        $("#subtotalOrden").val(subtotal.toFixed(2));

        var porcentajeImpuestos = parseFloat($("#porcentajeImpuestos").val()) || 0;
        var totalImpuestos = subtotal * (porcentajeImpuestos / 100);
        $("#totalImpuestos").val(totalImpuestos.toFixed(2));

        var descuento = parseFloat($("#descuentoOrden").val()) || 0;
        var envio = parseFloat($("#envioOrden").val()) || 0;

        var totalFinal = subtotal + totalImpuestos - descuento + envio;
        $("#totalFinalOrden").text("$ " + totalFinal.toFixed(2));
        $("#totalOrdenInput").val(totalFinal.toFixed(2));

        // Actualizar lista de productos para el POST
        actualizarListaProductos();
    }

    function actualizarListaProductos(){
        var listaProductos = [];
        $(".productos-orden tr").each(function(){
            var fila = $(this);
            listaProductos.push({
                "id_producto": fila.data("idproducto"),
                "descripcion": fila.find("td:nth-child(2)").text(),
                "cantidad": fila.find(".cantidad-oc").val(),
                "precio_compra": fila.find(".precio-oc").val(),
                "subtotal": fila.find(".subtotal-oc-celda").text()
            });
        });
        $("#listaProductosOrden").val(JSON.stringify(listaProductos));
    }
    
    <?php if($esEditar): ?>
        actualizarCalculos();
    <?php endif; ?>
});
</script>
