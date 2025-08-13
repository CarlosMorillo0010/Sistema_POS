/*=============================================
 AGREGAR PROVEEDOR DESDE EL BOTON
=============================================*/
$(".formularioFacturaGastos").on(
  "click",
  "button.btnFacturaGastos",
  function () {
    $(this).removeClass("btn-primary btnFacturaGastos");
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
        $(".nuevaFacturaGastos").append(
          "" +
            '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Proveedor:</small></label>\n' +
            '<select class="form-control nueva-FacturaGasto" id="nuevo-Proveedor" name="nuevo-Proveedor">\n' +
            "<option>Selecione un proeedor</option>\n" +
            "</select>\n" +
            "</div>\n" +
            '<div class="col-lg-2 input-group" style="padding-right: 10px;width: 80px;">\n' +
            '<label><small style="color: #000;">Letra:</small></label>\n' +
            '<input type="text" class="form-control nuevoTipoDocumento" id="nuevoTipoDocumento" name="nuevoTipoDocumento" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-2 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Documento:</small></label>\n' +
            '<input type="text" class="form-control nuevoDocumentoProveedor" id="nuevoDocumentoProveedor" name="nuevoDocumentoProveedor" value="">\n' +
            "</div>\n" +
            '<div class="input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Tipo Proveedor:</small></label>\n' +
            '<input type="text" class="form-control nuevoTipoProveedor" id="nuevoTipoProveedor" name="nuevoTipoProveedor" value="">\n' +
            "</div>\n" +
            '<div class="input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Telefono:</small></label>\n' +
            '<input type="text" class="form-control nuevoTelefonoProveedor" id="nuevoTelefonoProveedor" name="nuevoTelefonoProveedor" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-2 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Pais:</small></label>\n' +
            '<input type="text" class="form-control nuevoPaisProveedor" id="nuevoPaisProveedor" name="nuevoPaisProveedor" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-5 input-group">\n' +
            '<label><small style="color: #000;">Direccion:</small></label>\n' +
            '<input type="text" class="form-control nuevaDireccionProveedor" id="nuevaDireccionProveedor" name="nuevaDireccionProveedor" value="">\n' +
            "</div>"
        );
        /*=============================================
                 AGREGAR LOS PREVEEDORES AL SELECT
             =============================================*/
        respuesta.forEach(funcionForEach);

        function funcionForEach(item, index) {
          $(".nueva-FacturaGasto").append(
            '<option idCliente="' +
              item.id +
              '" value="' +
              item.nombre +
              '">' +
              item.nombre +
              "</option>"
          );
        }
      },
    });
  }
);

/*=============================================
 SELECCIONAR PROVEEDORES
=============================================*/
$(".formularioFacturaGastos").on(
  "change",
  "select.nueva-FacturaGasto",
  function () {
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
        $(".nuevoTipoDocumento").val(respuesta["tipo_documento"]);
        $(".nuevoDocumentoProveedor").val(respuesta["documento"]);
        $(".nuevoTipoProveedor").val(respuesta["tipo_proveedor"]);
        $(".nuevoTelefonoProveedor").val(respuesta["telefono"]);
        $(".nuevoPaisProveedor").val(respuesta["pais"]);
        $(".nuevaDireccionProveedor").val(respuesta["direccion"]);
      },
    });
  }
);

/*=====================================
    AGRGANDO PRODUCTO PARA FACTURA DE GASTOS
======================================**/
var numProducto = 0;
$(".formularioFacturaGastos").on(
  "click",
  "button.btnAgregarProductos_FacturaGastos",
  function () {
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
        $(".nuevoProductoFacturaGastos").append(
          "" +
            "<tr>\n" +
            '<td style="text-align: center; padding-left: 0;width: 5%;">\n' +
            '<div class="input-group">\n' +
            '<span class="input-group-addon" style="border: none;background-color: transparent;">\n' +
            '<button type="button" class="btn btn-danger btn-xs quitarProducto_FacturaGastos" idProducto>\n' +
            '<i class="fa fa-times"></i>\n' +
            "</button>\n" +
            "</span>\n" +
            "</div>\n" +
            "</td>" +
            '<td style="text-align: center;width: 10%;">\n' +
            '<input class="form-control nuevoCodigoProducto" name="nuevoCodigoProducto" codigo value readonly required>\n' +
            "</td>\n" +
            '<td style="text-align: center;width: 25%;">\n' +
            '<select class="form-control descripcion_FacturaGastos" id="producto' +
            numProducto +
            '" idProducto name="descripcion_FacturaGastos" required>\n' +
            "<option>Seleciones un producto</option>\n" +
            "</select>\n" +
            "</td>\n" +
            '<td style="text-align: center; padding-left: 0;width: 10%;">\n' +
            '<input type="number" class="form-control cantidad_FacturaGastos" value="1" min="1" name="cantidad_FacturaGastos" stock style="border: none;">\n' +
            "</td>\n" +
            '<td style="text-align: center; padding-left: 0;width: 10%;">\n' +
            '<input type="text" class="form-control precio_FacturaGastos" name="precio_FacturaGastos" precioReal="" style="border: none;" readonly required>\n' +
            "</td>\n" +
            '<td style="text-align: center; padding-left: 0;width: 10%;">\n' +
            '<input type="text" class="form-control precio_FacturaGastos" name="precio_FacturaGastos" precioReal="" style="border: none;" readonly required>\n' +
            "</td>\n" +
            '<td style="text-align: center; padding-left: 0;width: 10%;">\n' +
            '<input type="text" class="form-control precio_FacturaGastos" name="precio_FacturaGastos" precioReal="" style="border: none;" readonly required>\n' +
            "</td>\n" +
            '<td style="text-align: center; padding-left: 0;width: 10%;">\n' +
            '<input type="text" class="form-control precio_FacturaGastos" name="precio_FacturaGastos" precioReal="" style="border: none;" readonly required>\n' +
            "</td>\n" +
            "</tr>"
        );
        /*=============================================
                AGREGAR LOS ARTICULOS AL SELECT
            =============================================*/
        respuesta.forEach(funcionForEach);

        function funcionForEach(item, index) {
          if (item.stock != 0) {
            $("#producto" + numProducto).append(
              '<option idProducto="' +
                item.id +
                '" value="' +
                item.descripcion +
                '">' +
                item.descripcion +
                "</option>"
            );
          }
        }
      },
    });
  }
);

/*=============================================
 SELECCIONAR PRODUCTOS DE FACTURA DE GASTOS
=============================================*/
$(".formularioFacturaGastos").on(
  "change",
  "select.descripcion_FacturaGastos",
  function () {
    var nombreProducto = $(this).val();

    var codigoProducto = $(this)
      .parent()
      .parent()
      .children()
      .children(".nuevoCodigoProducto");
    var cantidadNota = $(this)
      .parent()
      .parent()
      .parent()
      .parent()
      .children()
      .children()
      .children()
      .children(".cantidad_FacturaGastos");
    var precioProducto = $(this)
      .parent()
      .parent()
      .children()
      .children(".precio_FacturaGastos");

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
        $(precioProducto).attr(
          "precioReal",
          respuesta["precio_unitario_total"]
        );
        $(precioProducto).val(respuesta["precio_unitario_total"]);
      },
    });
    /*=============================================
     SUMA TOTAL DE PRECIOS
    =============================================*/
    sumaTotalPrecios();
  }
);

/*======================================
 QUITAR PRODUCTOS DE FACTURA DE GASTOS
 ======================================**/
$(".formularioFacturaGastos").on(
  "click",
  "button.quitarProducto_FacturaGastos",
  function () {
    $(this).parent().parent().parent().parent().remove();
    /*=============================================
     SUMA TOTAL DE PRECIOS
    =============================================*/
    sumaTotalPrecios();
  }
);

/*=============================================
 MODIFICAR LA CANTIDAD
=============================================*/
$(".formularioFacturaGastos").on(
  "change",
  "input.cantidad_FacturaGastos",
  function () {
    var precio_FacturaGastos = $(this)
      .parent()
      .parent()
      .children()
      .children(".precio_FacturaGastos");
    var precioFinal_FacturaGastos =
      $(this).val() * precio_FacturaGastos.attr("precioReal");
    precio_FacturaGastos.val(precioFinal_FacturaGastos);

    if (Number($(this).val()) > Number($(this).attr("stock"))) {
      $(this).val(1);
      swal({
        title: "La cantidad supera al Stock Disponible",
        text: "¡Solo hay " + $(this).attr("stock") + " unidades!",
        type: "error",
        confirmButtonText: "Cerrar",
      });
    }
  }
);

/*=============================================
 SUMAR TODOS LOS PRECIOS EN FACTURA DE GASTOS
=============================================*/
function sumaTotalPrecios() {
  var precioItem = $(".nuevoPrecioProducto");
  /*console.log("precioItem", precioItem.get());*/
  var arraySumaPrecio = [];

  for (var i = 0; i < precioItem.length; i++) {
    arraySumaPrecio.push(Number($(precioItem[i].val())));
  }

  function sumaArrayPrecios(total, numero) {
    return total + numero;
  }

  var sumaTotalPrecio = arraySumaPrecio.reduce(sumaArrayPrecios);
  console.log("sumaTotalPrecio", sumaTotalPrecio);
}
