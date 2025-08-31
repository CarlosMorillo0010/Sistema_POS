$('.tablaRecepcionMercancia').DataTable({
    "language": {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Último",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
});

/*=============================================
CAPTURAR CLIC EN BOTÓN RECIBIR
=============================================*/
$('.tablaRecepcionMercancia tbody').on('click', '.btnRecibirOrden', function () {

    var idOrden = $(this).attr("idOrden");

    var datos = new FormData();
    datos.append("idOrdenCompra", idOrden);

    $.ajax({
        url: "ajax/entradas.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta){
            
            // Validar la respuesta
            if (!respuesta || !respuesta.orden || !Array.isArray(respuesta.detalle)) {
                console.error("Respuesta AJAX inválida:", respuesta);
                Swal.fire({
                    icon: "error",
                    title: "¡Error!",
                    text: "No se pudieron obtener los detalles de la orden. Verifique la respuesta del servidor.",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                });
                return; // Detener la ejecución si la respuesta no es válida
            }

            var orden = respuesta.orden;
            var detalle = respuesta.detalle;

            // Llenar cabecera del modal
            $("#idOrdenCompra").val(orden.id_orden_compra);
            $("#codigoOrden").html(orden.codigo);

            // Limpiar y llenar tabla de productos de forma segura
            var tablaProductos = $("#tablaProductosRecepcion");
            tablaProductos.empty();

            detalle.forEach(function(producto) {
                var tr = $('<tr>');
                var tdDescripcion = $('<td>').text(producto.descripcion);
                var tdCantidad = $('<td>').text(producto.cantidad);
                var tdAccion = $('<td>');

                var inputCantidad = $('<input>').attr({
                    type: 'number',
                    class: 'form-control',
                    name: 'cantidad_recibida[' + producto.id_producto + ']',
                    value: producto.cantidad,
                    min: 0,
                    max: producto.cantidad,
                    required: true
                });
                var inputPrecio = $('<input>').attr({
                    type: 'hidden',
                    name: 'precio_compra[' + producto.id_producto + ']',
                    value: producto.precio_compra
                });
                var inputDescripcion = $('<input>').attr({
                    type: 'hidden',
                    name: 'descripcion_producto[' + producto.id_producto + ']',
                    value: producto.descripcion
                });

                tdAccion.append(inputCantidad, inputPrecio, inputDescripcion);
                tr.append(tdDescripcion, tdCantidad, tdAccion);
                tablaProductos.append(tr);
            });

            // Mostrar el modal
            $("#modalRecepcionMercancia").modal("show");
        }
    });
});

/*=============================================
GUARDAR RECEPCIÓN DE MERCANCÍA
=============================================*/
$("#formRecepcion").on("submit", function(event){
    event.preventDefault();

    var datos = $(this).serialize();
    datos += "&action=crearEntrada"; // Añadir la acción para el controlador AJAX

    $.ajax({
        url: "ajax/entradas.ajax.php",
        method: "POST",
        data: datos,
        success: function(respuesta){
            if(respuesta.trim() == "ok"){
                Swal.fire({
                    icon: "success",
                    title: "¡La recepción ha sido guardada correctamente!",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                }).then((result) => {
                    if(result.isConfirmed){
                        window.location = "entradas-almacen";
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "¡Error al guardar la recepción!",
                    text: respuesta, // Muestra el error devuelto por el servidor
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                });
            }
        }
    });
});
