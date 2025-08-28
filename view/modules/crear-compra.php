<?php
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value):
    if ($_SESSION["perfil"] == $value["perfil"] && $value["compras"] == "N"){
        echo '
            <script>
            window.location = "inicio";
            </script>
        ';
        return;
    }
endforeach;
?>
<div class="content-wrapper" style="background-color: #f4f6f9;">
    <section class="content-header">
        <h1>
            Registrar Nueva Compra
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li><a href="compra">Administrar Compras</a></li>
            <li class="active">Registrar Compra</li>
        </ol>

    </section>
    <section class="content">
        <form role="form" method="post" class="formulario-compra">
            <div class="row">
                <!-- Columna del formulario -->
                <div class="col-lg-8 col-xs-12">
                    <!-- Card de Selección de Orden -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-check-square-o"></i> 1. Seleccionar Orden de Compra</h3>
                        </div>
                        <div class="box-body">
                            <p class="text-muted">Elige la orden de compra para cargar los productos y comenzar la recepción.</p>
                            <div class="form-group">
                                <div class="input-group">
                                    <select class="form-control select2" id="seleccionarOrdenCompra" name="seleccionarOrdenCompra" required>
                                        <option value="">Seleccionar Orden de Compra</option>
                                        <?php
                                        $ordenes = ControllerOrdenesCompras::ctrMostrarOrdenCompra(null, null);
                                        foreach ($ordenes as $key => $value) {
                                            echo '<option value="'.$value["id_orden_compra"].'" data-proveedor="'.$value["id_proveedor"].'">'.str_pad($value["codigo"], 10, 0, STR_PAD_LEFT).'</option>';
                                        }
                                        ?>
                                    </select>
                                    <input type="hidden" name="idProveedor" id="idProveedor">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card de Productos -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-cubes"></i> 2. Verificar Productos Recibidos</h3>
                        </div>
                        <div class="box-body" style="height: 540px;">
                            <div class="form-group tabla-productos-scroll-container">
                                <table class="table table-bordered table-striped tabla-productos-compra" width="100%">
                                    <thead style="background-color: #3c8dbc; color: white;">
                                        <tr>
                                            <th style="width:15%">Código</th>
                                            <th>Producto</th>
                                            <th style="width:10%">Cant. Pedida</th>
                                            <th style="width:15%">Cant. Recibida</th>
                                            <th style="width:15%">Costo Unit.</th>
                                            <th style="width:15%">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="productos-compra">
                                        <tr class="no-products-row">
                                            <td colspan="6" class="text-center">Seleccione una orden de compra para ver los productos.</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div id="loading-spinner" style="display: none; text-align: center; padding: 20px;">
                                    <i class="fa fa-spinner fa-spin fa-3x"></i>
                                    <p>Cargando productos...</p>
                                </div>
                                <input type="hidden" name="listaProductosCompra" id="listaProductosCompra">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna de totales y acciones -->
                <div class="col-lg-4 col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-calculator"></i> 3. Resumen y Finalización</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Items:</label>
                                <input type="text" class="form-control" id="totalItems" value="0" readonly>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <h4><strong>Total:</strong></h4>
                                </div>
                                <div class="col-xs-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg" id="montoTotalCompra" name="montoTotalCompra" value="0.00" readonly style="font-weight: bold; text-align: right;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="btn-group btn-block">
                                <button type="button" class="btn btn-danger btn-lg" onclick="window.history.back();" style="width: 50%;">Cancelar</button>
                                <button type="submit" class="btn btn-primary btn-lg" style="width: 50%;">Confirmar y Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                $crearCompra = new ControllerCompras();
                $crearCompra->ctrCrearCompra();
            ?>
        </form>
    </section>
</div>

<style>
.productos-compra input[type='number'] {
    width: 90px;
    text-align: center;
}
.select2-container .select2-selection--single {
    height: 34px !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 32px !important;
}
.tabla-productos-scroll-container {
    max-height: 380px; /* Puedes ajustar esta altura */
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
}
</style>

<script>
$(document).ready(function() {
    // Inicializar Select2
    $('#seleccionarOrdenCompra').select2({ placeholder: "Buscar y seleccionar una orden", allowClear: true });

    $('#seleccionarOrdenCompra').on('change', function() {
        var idOrdenCompra = $(this).val();
        var idProveedor = $(this).find(':selected').data('proveedor');
        $('#idProveedor').val(idProveedor);
        var productosCompraTbody = $('.productos-compra');

        if (idOrdenCompra) {
            $('#loading-spinner').show();
            productosCompraTbody.hide();

            $.ajax({
                url: 'ajax/compras.ajax.php',
                method: 'POST',
                data: { idOrdenCompra: idOrdenCompra },
                dataType: 'json',
                success: function(data) {
                    var productos = data.detalle;
                    productosCompraTbody.empty();

                    productos.forEach(function(producto) {
                        var newRow = '<tr>' +
                            '<td>' + producto.codigo + '</td>' +
                            '<td>' + producto.descripcion + '</td>' +
                            '<td>' + producto.cantidad + '</td>' +
                            '<td><div class="input-group"><input type="number" class="form-control cantidad-recibida" value="' + producto.cantidad + '" min="0" data-idproducto="' + producto.id_producto + '"></div></td>' +
                            '<td><div class="input-group"><input type="number" class="form-control costo-unitario" value="' + producto.precio_compra + '" min="0" step="0.01"></div></td>' +
                            '<td class="subtotal-celda" style="font-weight:bold; text-align:right;">' + (producto.cantidad * producto.precio_compra).toFixed(2) + '</td>' +
                            '</tr>';
                        productosCompraTbody.append(newRow);
                    });

                    actualizarListaProductos();
                    actualizarTotal();
                    $('#loading-spinner').hide();
                    productosCompraTbody.show();
                },
                error: function(){
                     $('#loading-spinner').hide();
                     productosCompraTbody.show();
                     productosCompraTbody.html('<tr class="no-products-row"><td colspan="6" class="text-center text-danger">Error al cargar los productos.</td></tr>');
                }
            });
        } else {
            productosCompraTbody.html('<tr class="no-products-row"><td colspan="6" class="text-center">Seleccione una orden de compra para ver los productos.</td></tr>');
            actualizarTotal();
        }
    });

    function actualizarListaProductos() {
        var listaProductos = [];
        var totalItems = 0;
        $('.productos-compra tr').each(function() {
            var id_producto = $(this).find('.cantidad-recibida').data('idproducto');
            var cantidad = parseFloat($(this).find('.cantidad-recibida').val()) || 0;
            var costo = parseFloat($(this).find('.costo-unitario').val()) || 0;
            var subtotal = (cantidad * costo).toFixed(2);

            if (cantidad > 0) {
                 listaProductos.push({
                    "id_producto": id_producto,
                    "cantidad": cantidad,
                    "costo": costo,
                    "subtotal": subtotal
                });
                totalItems += cantidad;
            }
        });
        $('#listaProductosCompra').val(JSON.stringify(listaProductos));
        $('#totalItems').val(totalItems);
    }

    function actualizarTotal() {
        var total = 0;
        $('.subtotal-celda').each(function() {
            total += parseFloat($(this).text()) || 0;
        });
        $('#montoTotalCompra').val(total.toFixed(2));
    }

    $(document).on('change keyup', '.cantidad-recibida, .costo-unitario', function() {
        var row = $(this).closest('tr');
        var cantidad = parseFloat(row.find('.cantidad-recibida').val()) || 0;
        var costo = parseFloat(row.find('.costo-unitario').val()) || 0;
        var subtotal = (cantidad * costo).toFixed(2);
        row.find('.subtotal-celda').text(subtotal);
        actualizarListaProductos();
        actualizarTotal();
    });
});
</script>
