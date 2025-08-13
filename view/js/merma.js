/*$('.tablaCompuestos').DataTable({
        "ajax": "ajax/datatable-compuestos.ajax.php",
        "deferRender": true,
        "retrieve": true,
        "processing": true,
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
    }
);*/

/*=====================================
 CAPTURANDO LA CATEGORIA PARA ASIGNAR UN CODIGO
======================================**/
/*$("#nuevaCategoria").change(function () {
    var idCategoria = $(this).val();
    var datos = new FormData();
    datos.append("idCategoria", idCategoria);
    $.ajax({
        url: "ajax/compuestos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
            if (!respuesta) {
                var nuevoCodigo = idCategoria + "1";
                $("#nuevoCodigo").val(nuevoCodigo);
            } else {
                var nuevoCodigo = Number(respuesta["codigo"]) + 1;
                $("#nuevoCodigo").val(nuevoCodigo);
            }
        }
    })
})*/

/*=====================================
 AGRGANDO PRODUCTO PARA MERMA
======================================**/
var numProducto = 0;
$(".btnAgregarMerma").click(function () {
    numProducto++;
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
        success: function (respuesta) {
            $(".nuevaTablaMerma").append('' +
                '<tr>\n' +

                '<td style="text-align: center; padding-left: 0;width: 5%;">\n' +
                '<div class="input-group">\n' +
                '<span class="input-group-addon" style="border: none;background-color: transparent;">\n' +
                '<button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto>\n' +
                '<i class="fa fa-times"></i>\n' +
                '</button>\n' +
                '</span>\n' +
                '</div>\n' +
                '</td>' +

                '<td style="text-align: center;width: 45%;">\n' +
                '<select class="form-control productoMerma" id="producto' + numProducto + '" name="productoMerma" idProducto required>\n' +
                '<option>Seleciones un producto</option>\n' +
                '</select>\n' +
                '</td>\n' +

                '<td style="text-align: center; padding-left: 0;width: 15%;">\n' +
                '<input type="text" class="form-control cantidadIngrediente" id="cantidadIngrediente" name="cantidadIngrediente" style="border: none;">\n' +
                '</td>\n' +

                '<td style="text-align: center; padding-left: 0;width: 15%;">\n' +
                '<input type="text" class="form-control cantidadPerdida" id="cantidadPerdida" name="cantidadPerdida" style="border: none;">\n' +
                '</td>\n' +

                '<td style="text-align: center; padding-left: 0;width: 15%;">\n' +
                '<input type="text" class="form-control perdidaTotal" id="perdidaTotal" name="perdidaTotal" style="border: none;">\n' +
                '</td>\n' +

                '</tr>'
            );
            /*=============================================
                AGREGAR LOS ARTICULOS AL SELECT
            =============================================*/
            respuesta.forEach(funcionForEach);

            function funcionForEach(item, index) {
                if (item.stock != 0) {
                    $("#producto" + numProducto).append(
                        '<option idProducto="' + item.id + '" value="' + item.descripcion + '">' + item.descripcion + '</option>'
                    )
                }
            }
        }
    })
})

/*=============================================
 SELECCIONAR PRODUCTOS
=============================================*/
$(".formMerma").on("change", "select.productoMerma", function () {

    var nombreProducto = $(this).val();
    var cantidadCompuesto = $(this).parent().parent().children().children("#cantidadIngrediente");

    var datos = new FormData();
    datos.append("nombreProducto", nombreProducto);

    $.ajax({
        url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
            $(cantidadCompuesto).attr("stock", respuesta["stock"]);
            $(cantidadCompuesto).attr("nuevoStock", Number(respuesta["stock"]) - 1);
            /*=============================================
             AGRUPAR PRODUCTOS EN FORMATO JSON
            =============================================*/
            listaTerminados();
        }
    })
})

/*=============================================
 MODIFICAR LA CANTIDAD
=============================================*/
$(".formMerma").on("change", "input.cantidadIngrediente", function () {
    var nuevoStock = Number($(this).attr("stock")) - $(this).val();
    $(this).attr("nuevoStock", nuevoStock);
    if (Number($(this).val()) > Number($(this).attr("stock"))) {
        /*=============================================
        SI LA CANTIDAD ES SUPERIOR AL STOCK REGRESAR VALORES INICIALES
        =============================================*/
        $(this).val(1);
        $(this).attr("nuevoStock", $(this).attr("stock"));
        /*=============================================
         AGRUPAR PRODUCTOS EN FORMATO JSON
        =============================================*/
        listaTerminados();
        swal({
            title: "La cantidad supera el Stock",
            text: "¡Sólo hay " + $(this).attr("stock") + " unidades!",
            type: "error",
            confirmButtonText: "¡Cerrar!"
        });
        return;
    }
    /*=============================================
     AGRUPAR PRODUCTOS EN FORMATO JSON
    =============================================*/
    listaTerminados();
})

/*======================================
 QUITAR PRODUCTOS COMPUESTOS
 ======================================**/
$(".formMerma").on("click", "button.quitarProducto", function () {
    $(this).parent().parent().parent().parent().remove();
    if ($(".productoCompuesto").children().length == 0) {

    } else {
        /*=============================================
         AGRUPAR PRODUCTOS EN FORMATO JSON
        =============================================*/
        listaProductos();
    }
})

/*======================================
 LISTAR TODOS LOS PRODUCTOS TERMINADOS
======================================**/
function listaTerminados() {
    var listaTerminados = [];

    var descripcion = $(".productoCompuesto");
    var cantidad = $(".cantidadProductoCompuesto");

    for (var i = 0; i < descripcion.length; i++) {
        listaTerminados.push(
            {
                "descripcion": $(descripcion[i]).val(),
                "cantidad": $(cantidad[i]).val(),
                "stock": $(cantidad[i]).attr("nuevoStock")
            }
        )
    }
    $("#listaCompuestos").val(JSON.stringify(listaTerminados));
    console.log("listaTerminados", listaTerminados);
}