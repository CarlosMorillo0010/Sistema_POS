/*=============================================
 AGREGAR PROVEEDORES DESDE EL BOTON
=============================================*/
$(".formularioDevolucionCompra").on("click", "button.btnAgregarDevolucionCompra", function () {
    $(this).removeClass("btn-primary btnAgregarDevolucionCompra");
    $(this).addClass("disabled");

    var datos = new FormData();
    datos.append("traerProveedores", "OK");

    $.ajax({
        url: "ajax/proveedores.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
            $(".nuevaDevolucionCompraProveedores").append('' +
                '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
                '<label><small style="color: #000;">Proveedor:</small></label>\n' +
                '<select class="form-control nuevo-ProveedorDevolucionCompra" id="nuevo-ProveedorDevolucionCompra" name="nuevo-ProveedorDevolucionCompra">\n' +
                '<option>Selecione un proeedor</option>\n' +
                '</select>\n' +
                '</div>\n' +
                '<div class="col-lg-2 input-group" style="padding-right: 10px;">\n' +
                '<label><small style="color: #000;">Tipo Documento:</small></label>\n' +
                '<input type="text" class="form-control nuevoTipoDocumento_DevolucionCompra" id="nuevoTipoDocumento_DevolucionCompra" name="nuevoTipoDocumento_DevolucionCompra" value="">\n' +
                '</div>\n' +
                '<div class="col-lg-2 input-group" style="padding-right: 10px;">\n' +
                '<label><small style="color: #000;">Documento:</small></label>\n' +
                '<input type="text" class="form-control nuevoDocumento_DevolucionCompra" id="nuevoDocumento_DevolucionCompra" name="nuevoDocumento_DevolucionCompra" value="">\n' +
                '</div>\n' +
                '<div class="input-group" style="padding-right: 10px;">\n' +
                '<label><small style="color: #000;">Tipo Proveedor:</small></label>\n' +
                '<input type="text" class="form-control nuevoTipo_DevolucionCompra" id="nuevoTipo_DevolucionCompra" name="nuevoTipo_DevolucionCompra" value="">\n' +
                '</div>\n' +
                '<div class="input-group" style="padding-right: 10px;">\n' +
                '<label><small style="color: #000;">Telefono:</small></label>\n' +
                '<input type="text" class="form-control nuevoTelefono_DevolucionCompra" id="nuevoTelefono_DevolucionCompra" name="nuevoTelefono_DevolucionCompra" value="">\n' +
                '</div>\n' +
                '<div class="col-lg-2 input-group" style="padding-right: 10px;">\n' +
                '<label><small style="color: #000;">Pais:</small></label>\n' +
                '<input type="text" class="form-control nuevoPais_DevolucionCompra" id="nuevoPais_DevolucionCompra" name="nuevoPais_DevolucionCompra" value="">\n' +
                '</div>\n' +
                '<div class="col-lg-5 input-group">\n' +
                '<label><small style="color: #000;">Direccion:</small></label>\n' +
                '<input type="text" class="form-control nuevaDireccion_DevolucionCompra" id="nuevaDireccion_DevolucionCompra" name="nuevaDireccion_DevolucionCompra" value="">\n' +
                '</div>'
            );
            /*=============================================
                 AGREGAR LOS CLIENTES AL SELECT
             =============================================*/
            respuesta.forEach(funcionForEach);

            function funcionForEach(item, index) {
                $(".nuevo-ProveedorDevolucionCompra").append(
                    '<option idCliente="' + item.id + '" value="' + item.nombre + '">' + item.nombre + '</option>'
                )
            }
        }
    })
})

/*=============================================
 SELECCIONAR PROVEEDOR
=============================================*/
$(".formularioDevolucionCompra").on("change", "select.nuevo-ProveedorDevolucionCompra", function () {
    var nombreProveedor = $(this).val();

    var datos = new FormData();
    datos.append("nombreProveedor", nombreProveedor);
    $.ajax({
        url: "ajax/proveedores.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
            $(".nuevoTipoDocumento_DevolucionCompra").val(respuesta["tipo_documento"]);
            $(".nuevoDocumento_DevolucionCompra").val(respuesta["documento"]);
            $(".nuevoTipo_DevolucionCompra").val(respuesta["tipo_proveedor"]);
            $(".nuevoTelefono_DevolucionCompra").val(respuesta["telefono"]);
            $(".nuevoPais_DevolucionCompra").val(respuesta["pais"]);
            $(".nuevaDireccion_DevolucionCompra").val(respuesta["direccion"]);
        }
    })
})

/*=====================================
 AGRGANDO PRODUCTO FACTURA COMPRA
======================================**/
var numProducto = 0;
$(".formularioDevolucionCompra").on("click", "button.btnDevolucionCompraProductos", function () {
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

            $(".DevolucionCompraProductos").append('' +
                '<tr>\n' +

                '<td style="text-align: center; padding-left: 0;width: 10%;">\n' +
                '<div class="input-group">\n' +
                '<span class="input-group-addon" style="border: none;background-color: transparent;">\n' +
                '<button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto>\n' +
                '<i class="fa fa-times"></i>\n' +
                '</button>\n' +
                '</span>\n' +
                '</div>\n' +
                '</td>' +

                '<td style="text-align: center;width: 15%;">\n' +
                '<input class="form-control nuevoCodigoProducto" name="nuevoCodigoProducto" codigo value readonly required>\n' +
                '</td>\n' +

                '<td style="text-align: center;width: 50%;">\n' +
                '<select class="form-control nuevaDevolucionCompra" id="producto'+numProducto+'" idProducto name="nuevaDevolucionCompra" required>\n' +
                '<option>Seleciones un producto</option>\n' +
                '</select>\n' +
                '</td>\n' +

                '<td style="text-align: center; padding-left: 0;width: 10%;">\n' +
                '<input type="number" class="form-control cantidadDevolucionCompra" value="1" min="1" name="cantidadDevolucionCompra" stock style="border: none;">\n' +
                '</td>\n' +

                '<td style="text-align: center; padding-left: 0;width: 15%;">\n' +
                '<input type="text" class="form-control nuevoPrecioProductoOrdenCompra" name="nuevoPrecioProductoOrdenCompra" precioReal style="border: none;" readonly required>\n' +
                '</td>\n' +

                '</tr>'
            )
            /*=============================================
                AGREGAR LOS ARTICULOS AL SELECT
            =============================================*/
            respuesta.forEach(funcionForEach);

            function funcionForEach(item, index) {
                if (item.stock != 0) {
                    $("#producto"+numProducto).append(
                        '<option idProducto="'+item.id+'" value="'+item.descripcion+'">'+item.descripcion+'</option>'
                    )
                }
            }
        }
    })
})

/*=============================================
 SELECCIONAR PRODUCTOS FACTURA COMPRA
=============================================*/
$(".formularioDevolucionCompra").on("change", "select.nuevaDevolucionCompra", function () {
    var nombreProducto = $(this).val();

    var codigoProducto = $(this).parent().parent().children().children(".nuevoCodigoProducto");
    var cantidadNota = $(this).parent().parent().parent().parent().children().children().children().children(".nuevaCantidadOrdenCompra");
    var precioProducto = $(this).parent().parent().children().children(".nuevoPrecioProductoOrdenCompra");

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
            $(codigoProducto).attr("codigo", respuesta["codigo"]);
            $(codigoProducto).val(respuesta["codigo"]);
            $(cantidadNota).attr("stock", respuesta["stock"]);
            $(precioProducto).attr("precioReal",respuesta["precio_unitario_total"]);
            $(precioProducto).val(respuesta["precio_unitario_total"]);
        }
    })
    /*=============================================
     SUMA TOTAL DE PRECIOS
    =============================================*/
    sumaTotalPrecios();

})

/*======================================
 QUITAR PRODUCTOS
 ======================================**/
$(".formularioDevolucionCompra").on("click", "button.quitarProducto", function () {
    $(this).parent().parent().parent().parent().remove();
    /*=============================================
     SUMA TOTAL DE PRECIOS
    =============================================*/
    sumaTotalPrecios();
})

/*=============================================
 MODIFICAR LA CANTIDAD
=============================================*/
$(".formularioOrdenCompra").on("change", "input.cantidadDevolucionCompra", function () {
    var precio_OrdenCompra = $(this).parent().parent().children().children(".nuevoPrecioProductoOrdenCompra");
    var precioFinal_OrdenCompra = $(this).val() * precio_OrdenCompra.attr("precioReal");
    precio_OrdenCompra.val(precioFinal_OrdenCompra);

    if (Number($(this).val()) > Number($(this).attr("stock"))){
        $(this).val(1);
        swal({
            title: "La cantidad supera al Stock Disponible",
            text: "¡Solo hay " + $(this).attr("stock") + " unidades!",
            type: "error",
            confirmButtonText: "Cerrar"
        });
    }
})

/*=============================================
 SUMAR TODOS LOS PRECIOS FACTURA COMPRA
=============================================*/
function sumaTotalPrecios() {
    var precioItem = $(".nuevoPrecioProducto");
    console.log("precioItem", precioItem);
    var arraySumaPrecio = [];

    for (var i = 0; i < precioItem.length; i++){
        arraySumaPrecio.push(Number($(precioItem[i].val())));
    }

    function sumaArrayPrecios(total, numero){
        return total + numero;
    }

    var sumaTotalPrecio = arraySumaPrecio.reduce(sumaArrayPrecios);
    console.log("sumaTotalPrecio", sumaTotalPrecio);
}