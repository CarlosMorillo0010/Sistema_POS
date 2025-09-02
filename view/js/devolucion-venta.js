$('.tablaVentas').DataTable({
    "ajax": "ajax/datatable-ventas.ajax.php",
    "deferRender": true,
    "retrieve": true,
    "processing": true,
    "language": {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
});

$(".tablaVentas").on("click", ".btnAgregarVenta", function(){

    var idVenta = $(this).attr("idVenta");
    $("#idVenta").val(idVenta);

    var datos = new FormData();
    datos.append("idVenta", idVenta);

    $.ajax({
        url:"ajax/ventas.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(respuesta){

            var idCliente = respuesta.id_cliente;
            var productos = JSON.parse(respuesta.productos);

            $("#seleccionarCliente").val(idCliente);
            $("#seleccionarCliente").trigger("change");

            $(".nuevoProducto").html('');

            productos.forEach(function(producto, index){

                var idProducto = producto.id;
                var descripcion = producto.descripcion;
                var cantidad = producto.cantidad;
                var precio = producto.precio;
                var subtotal = producto.total;

                $(".nuevoProducto").append(
                    '<div class="row" style="padding:5px 15px">'+
                        '<div class="col-xs-6" style="padding-right:0px">'+
                            '<div class="input-group">'+
                                '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto="'+idProducto+'"><i class="fa fa-times"></i></button></span>'+
                                '<input type="text" class="form-control nuevaDescripcionProducto" idProducto="'+idProducto+'" name="agregarProducto" value="'+descripcion+'" readonly required>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-xs-3">'+
                            '<input type="number" class="form-control nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="'+cantidad+'" stock="'+cantidad+'" required>'+
                        '</div>'+
                        '<div class="col-xs-3" style="padding-left:0px">'+
                            '<div class="input-group">'+
                                '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+
                                '<input type="text" class="form-control nuevoPrecioProducto" precioReal="'+precio+'" name="nuevoPrecioProducto" value="'+subtotal+'" readonly required>'+
                            '</div>'+
                        '</div>'+
                    '</div>');
            });

            sumarTotalPrecios();
            listarProductos();
        }
    });
});

$(".formularioDevolucionVenta").on("click", ".quitarProducto", function(){
    $(this).parent().parent().parent().parent().remove();
    sumarTotalPrecios();
    listarProductos();
});

$(".formularioDevolucionVenta").on("change", "input.nuevaCantidadProducto", function(){
    var precioUnitario = $(this).parent().parent().find(".nuevoPrecioProducto").attr("precioReal");
    var subtotalInput = $(this).parent().parent().find(".nuevoPrecioProducto");

    var cantidadActual = Number($(this).val());
    var cantidadMaxima = Number($(this).attr("stock"));

    if(cantidadActual > cantidadMaxima){
        $(this).val(cantidadMaxima);
        cantidadActual = cantidadMaxima;

        Swal.fire({
            icon: "error",
            title: "La cantidad supera la vendida",
            text: "¡Sólo puedes devolver hasta "+cantidadMaxima+" unidades!",
            confirmButtonText: "¡Cerrar!"
        });
    }

    var subtotalFinal = cantidadActual * precioUnitario;
    subtotalInput.val(subtotalFinal.toFixed(2));

    sumarTotalPrecios();
    listarProductos();
});

function sumarTotalPrecios(){
    var precioItem = $(".nuevoPrecioProducto");
    var arraySumaPrecio = [];
    for(var i = 0; i < precioItem.length; i++){
        arraySumaPrecio.push(Number($(precioItem[i]).val()));
    }
    function sumaArrayPrecios(total, numero){
        return total + numero;
    }
    var sumaTotalPrecio = arraySumaPrecio.reduce(sumaArrayPrecios, 0);
    $("#montoTotalDevolucion").val(sumaTotalPrecio.toFixed(2));
}

function listarProductos(){
    var listaProductos = [];
    var items = $(".formularioDevolucionVenta .nuevoProducto .row");

    for(var i = 0; i < items.length; i++){
        var item = $(items[i]);
        var idProducto = item.find(".nuevaDescripcionProducto").attr("idProducto");
        var cantidad = item.find(".nuevaCantidadProducto").val();
        var precio = item.find(".nuevoPrecioProducto").attr("precioReal");
        var total = item.find(".nuevoPrecioProducto").val();

        listaProductos.push({
            "id_producto" : idProducto,
            "cantidad" : cantidad,
            "precio" : precio,
            "total" : total
        });
    }

    $("#listaProductosDevolucion").val(JSON.stringify(listaProductos));
}