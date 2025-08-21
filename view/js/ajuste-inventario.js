$(document).ready(function() {

    // =================================================================
    // LÓGICA PARA CREAR UN NUEVO AJUSTE
    // =================================================================

    // Evento para el botón "Agregar Producto" en el modal de creación.
    // Se usa delegación de eventos desde 'document' para máxima compatibilidad.
    $(document).on('click', '.btnAjusteInventario', function() {

        var almacenSeleccionado = $("#nuevoAlmacen").val();
        
        // 1. Validar que se haya seleccionado un almacén.
        if (almacenSeleccionado === "" || almacenSeleccionado === null) {
            Swal.fire({
                title: "Seleccione un Almacén",
                text: "¡Debe seleccionar un almacén para poder agregar productos!",
                icon: "warning",
                confirmButtonText: "Cerrar"
            });
            return;
        }

        // 2. Si la validación pasa, pedir la lista de productos vía AJAX.
        var datos = new FormData();
        datos.append("traerArticulos", "OK");

        $.ajax({
            url: "ajax/productos.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta){
                // 3. Agregar una nueva fila a la tabla con los campos necesarios.
                $(".nuevoAjusteProducto").append(
                '<tr class="nuevaFilaProducto">'+
                    '<td><div class="input-group"><select class="form-control nuevaDescripcionProducto" style="width:100%" required><option>Seleccione el producto</option></select></div></td>'+
                    '<td><input type="number" class="form-control cantidadActual" readonly required></td>'+
                    '<td><select class="form-control tipoAjuste" required><option value="">Seleccione tipo</option><option value="incremento">Incremento (+)</option><option value="decremento">Disminución (-)</option></select></td>'+
                    '<td><input type="number" class="form-control nuevaCantidadProducto" min="1" value="1" required></td>'+
                    '<td><input type="number" class="form-control cantidadFinal" readonly required></td>'+
                    '<td><button type="button" class="btn btn-danger btn-xs quitarProducto"><i class="fa fa-times"></i></button></td>'+
                '</tr>');

                // 4. Llenar el <select> recién creado con la lista de productos.
                respuesta.forEach(function(item, index){
                    $('.nuevaDescripcionProducto').last().append(
                       '<option idProducto="'+item.id_producto+'" value="'+item.id_producto+'">'+item.descripcion+'</option>'
                    )
                });
                
                // 5. Inicializar Select2 para tener búsqueda en el <select>.
                $('.nuevaDescripcionProducto').last().select2();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la llamada AJAX para traer productos: ", textStatus, errorThrown);
                Swal.fire({
                    title: "Error de Comunicación",
                    text: "No se pudo obtener la lista de productos. Revise la consola para más detalles.",
                    icon: "error"
                });
            }
        });
    });

    // Evento cuando se selecciona un producto de la lista.
    $(".formulario__AjusteInventario").on("change", "select.nuevaDescripcionProducto", function () {
        var idProducto = $(this).val();
        var fila = $(this).closest('.nuevaFilaProducto');
        var datos = new FormData();
        datos.append("idProducto", idProducto);

        $.ajax({
            url: "ajax/productos.ajax.php",
            method: "POST", data: datos, cache: false, contentType: false, processData: false, dataType: "json",
            success: function(respuesta){
                fila.find('.cantidadActual').val(respuesta.stock);
                fila.find('.cantidadFinal').val(respuesta.stock);
                fila.find('.nuevaCantidadProducto').val(1);
                fila.find('.tipoAjuste').val('').trigger('change');
                actualizarListaProductos();
            }
        });
    });

    // Evento cuando cambia la cantidad o el tipo de ajuste para recalcular el stock final.
    $(".formulario__AjusteInventario").on("change keyup", ".nuevaCantidadProducto, .tipoAjuste", function () {
        var fila = $(this).closest('.nuevaFilaProducto');
        var stockActual = Number(fila.find('.cantidadActual').val());
        var tipoAjuste = fila.find('.tipoAjuste').val();
        var cantidadAjustar = Number(fila.find('.nuevaCantidadProducto').val());
        var stockFinalInput = fila.find('.cantidadFinal');

        if(tipoAjuste == "incremento") {
            stockFinalInput.val(stockActual + cantidadAjustar);
        } else if (tipoAjuste == "decremento") {
            if(cantidadAjustar > stockActual) {
                fila.find('.nuevaCantidadProducto').val(stockActual);
                cantidadAjustar = stockActual;
                Swal.fire({
                    title: "La cantidad supera el Stock",
                    text: "Se ha ajustado al máximo disponible.",
                    icon: "error",
                    confirmButtonText: "Cerrar"
                });
            }
            stockFinalInput.val(stockActual - cantidadAjustar);
        } else {
            stockFinalInput.val(stockActual);
        }
        actualizarListaProductos();
    });

    // Evento para el botón "Quitar Producto" de una fila.
    $(".formulario__AjusteInventario").on("click", "button.quitarProducto", function(){
        $(this).closest(".nuevaFilaProducto").remove();
        actualizarListaProductos();
    });

    // Función que recopila los datos de todas las filas y los guarda en el input oculto.
    function actualizarListaProductos(){
        var listaProductos = [];
        $(".nuevaFilaProducto").each(function(){
            var idProducto = $(this).find(".nuevaDescripcionProducto").val();
            var stockActual = $(this).find(".cantidadActual").val();
            var tipoAjuste = $(this).find(".tipoAjuste").val();
            var cantidad = $(this).find(".nuevaCantidadProducto").val();
            var stockFinal = $(this).find(".cantidadFinal").val();

            if(idProducto && idProducto !== "Seleccione el producto" && tipoAjuste && cantidad > 0) {
                listaProductos.push({
                    "idProducto": idProducto, "stockActual": stockActual, "tipoAjuste": tipoAjuste, "cantidad": cantidad, "stockFinal": stockFinal
                });
            }
        });
        $("#listaProductos").val(JSON.stringify(listaProductos));
    }

    // Limpiar el modal de creación cuando se cierra para evitar datos residuales.
    $('#modalAjusteInventario').on('hidden.bs.modal', function () {
        $(".nuevoAjusteProducto").empty();
        $("#listaProductos").val("");
        $('.formulario__AjusteInventario')[0].reset();
        $("#nuevoAlmacen").val(null).trigger('change');
    });


    // =================================================================
    // LÓGICA PARA VER DETALLES DE UN AJUSTE EXISTENTE
    // =================================================================

    // Evento para el botón "Ver Detalle" (icono del ojo) en la tabla principal.
    $(document).on("click", ".btnVerAjuste", function() {

        var idAjuste = $(this).attr("idAjuste");
        
        var datos = new FormData();
        datos.append("idAjuste", idAjuste);

        $.ajax({
            url: "ajax/ajustes.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta) {
                
                // 1. Llenar la información del encabezado en el modal de detalles.
                $("#verIdAjuste").text(respuesta.header.id_ajuste);
                $("#verFechaAjuste").text(new Date(respuesta.header.fecha_ajuste).toLocaleString());
                $("#verAlmacenAjuste").text(respuesta.header.nombre_almacen);
                $("#verUsuarioAjuste").text("ID: " + respuesta.header.id_usuario); // Mejorar esto si tienes una forma de buscar el nombre del usuario.
                $("#verMotivoAjuste").text(respuesta.header.descripcion || "Sin descripción.");

                // 2. Limpiar la tabla de detalles antes de llenarla.
                $("#tablaDetallesAjuste tbody").empty();
                
                // 3. Llenar la tabla con cada producto del ajuste.
                respuesta.details.forEach(function(detalle) {
                    
                    var tipoAjusteTexto = detalle.tipo_ajuste.charAt(0).toUpperCase() + detalle.tipo_ajuste.slice(1);
                    var claseFila = detalle.tipo_ajuste === 'incremento' ? 'success' : 'danger';
                    var signo = detalle.tipo_ajuste === 'incremento' ? '+' : '-';

                    var filaHtml = `
                        <tr class="${claseFila}">
                            <td>${detalle.descripcion_producto}</td>
                            <td>${detalle.cantidad_anterior}</td>
                            <td>${tipoAjusteTexto}</td>
                            <td><strong>${signo} ${detalle.cantidad_ajustada}</strong></td>
                            <td>${detalle.cantidad_final}</td>
                        </tr>
                    `;
                    
                    $("#tablaDetallesAjuste tbody").append(filaHtml);
                });
                
                // 4. Mostrar el modal de detalles.
                $('#modalVerDetalleAjuste').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error al obtener detalles del ajuste: ", textStatus, errorThrown);
                Swal.fire({
                    title: "Error",
                    text: "No se pudieron cargar los detalles del ajuste. Intente de nuevo.",
                    icon: "error"
                });
            }
        });
    });

});