/*=============================================
 AGREGAR CLIENTES DESDE EL BOTON
=============================================*/
$(".formularioFacturaVenta").on(
  "click",
  "button.btnClienteFacturaVenta",
  function () {
    $(this).removeClass("btn-primary btnClienteFacturaVenta");
    $(this).addClass("disabled");

    var datos = new FormData();
    datos.append("traerClientes", "OK");

    $.ajax({
      url: "ajax/clients.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
        $(".nuevoClienteFacturaVenta").append(
          "" +
            '<input type="hidden" class="form-control" id="idCliente" name="idCliente">\n' +
            '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Cliente:</small></label>\n' +
            '<select class="form-control clienteFacturaVenta" idCliente id="clienteFacturaVenta" name="clienteFacturaVenta">\n' +
            "<option>Selecione un cliente</option>\n" +
            "</select>\n" +
            "</div>\n" +
            '<div class="input-group" style="padding-right: 10px;width: 80px;">\n' +
            '<label><small style="color: #000;">Letra:</small></label>\n' +
            '<input type="text" class="form-control nuevaLetraFacturaVenta" id="nuevaLetraFacturaVenta" name="nuevaLetraFacturaVenta" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-2 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Documento de Identidad:</small></label>\n' +
            '<input type="text" class="form-control documentoFacturaVenta" id="documentoFacturaVenta" name="documentoFacturaVenta" value="">\n' +
            "</div>\n" +
            '<div class="input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Telefono:</small></label>\n' +
            '<input type="text" class="form-control telefonoFacturaVenta" id="telefonoFacturaVenta" name="telefonoFacturaVenta" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Correo Electronico:</small></label>\n' +
            '<input type="text" class="form-control emailFacturaventa" id="emailFacturaventa" name="emailFacturaventa" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-4 input-group">\n' +
            '<label><small style="color: #000;">Direccion:</small></label>\n' +
            '<input type="text" class="form-control direccionFacturaVenta" id="direccionFacturaVenta" name="direccionFacturaVenta" value="">\n' +
            "</div>"
        );
        /*=============================================
                 AGREGAR LOS CLIENTES AL SELECT
             =============================================*/
        respuesta.forEach(funcionForEach);

        function funcionForEach(item, index) {
          $(".clienteFacturaVenta").append(
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
 SELECCIONAR CLIENTE
=============================================*/
$(".formularioFacturaVenta").on(
  "change",
  "select.clienteFacturaVenta",
  function () {
    var nombreCliente = $(this).val();

    var datos = new FormData();
    datos.append("nombreCliente", nombreCliente);
    $.ajax({
      url: "ajax/clients.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
        $("#idCliente").val(respuesta["id"]);
        $(".nuevaLetraFacturaVenta").val(respuesta["tipo_documento"]);
        $(".documentoFacturaVenta").val(respuesta["documento"]);
        $(".telefonoFacturaVenta").val(respuesta["telefono"]);
        $(".emailFacturaventa").val(respuesta["email"]);
        $(".direccionFacturaVenta").val(respuesta["direccion"]);
        /*=============================================
             AGRUPAR PROVEEDOR EN FORMATO JSON
            =============================================*/
        listaClienteFacturaVenta();
        /*======================================
            AGREGAR IDPROVEEDORES
            ======================================**/
        idClienteFacturaVenta();
      },
    });
  }
);

/*======================================
 AGREGAR IDCLIENTE
======================================**/
function idClienteFacturaVenta() {
  var idCliente = $("#idCliente");
  $("#clienteID").val(idCliente);
  /* console.log("ID CLIENTE", idCliente.val()); */
}

/*======================================
 LISTAR TODOS LOS CLIENTES
======================================**/
function listaClienteFacturaVenta() {
  var listaCliente = [];
  var idCliente = $("#idCliente");
  var cliente = $(".clienteFacturaVenta");
  var tipoDocumento = $(".nuevaLetraFacturaVenta");
  var documento = $(".documentoFacturaVenta");
  var telefono = $(".telefonoFacturaVenta");
  var correo = $(".emailFacturaventa");
  var direccion = $(".direccionFacturaVenta");

  for (var i = 0; i < cliente.length; i++) {
    listaCliente.push({
      id: $(idCliente[i]).val(),
      nombre: $(cliente[i]).val(),
      tipoDocumento: $(tipoDocumento[i]).val(),
      documento: $(documento[i]).val(),
      telefono: $(telefono[i]).val(),
      correo: $(correo[i]).val(),
      direccion: $(direccion[i]).val(),
    });
  }
  /* console.log("DATOS DEL CLIENTE", listaCliente); */
  $("#listaCliente").val(JSON.stringify(listaCliente));
}

/*=====================================
 AGRGANDO PRODUCTO FACTURA DE VENTA
======================================**/
var numProducto = 0;
$(".formularioFacturaVenta").on("click", "button.btnFacturaVenta", function () {
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
      $(".productoFacturaVenta").append(
        "" +
          "<tr>\n" +
          '<td style="text-align: center; padding-left: 0;width: 5%;">\n' +
          '<div class="input-group">\n' +
          '<span class="input-group-addon" style="border: none;background-color: transparent;">\n' +
          '<button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto>\n' +
          '<i class="fa fa-times"></i>\n' +
          "</button>\n" +
          "</span>\n" +
          "</div>\n" +
          "</td>" +
          '<td style="text-align: center;width: 10%;">\n' +
          '<input class="form-control codigoFacturaVenta" name="codigoFacturaVenta" codigo value readonly required>\n' +
          "</td>\n" +
          '<td style="text-align: center;width: 25%;">\n' +
          '<select class="form-control descripcionFacturaVenta" id="producto' +
          numProducto +
          '" idProducto name="descripcionFacturaVenta" required>\n' +
          "<option>Seleciones un producto</option>\n" +
          "</select>\n" +
          "</td>\n" +
          '<td style="text-align: center;width: 10%;">\n' +
          '<input type="text" class="form-control unidadFacturaVenta" name="unidadFacturaVenta" idUnidad readonly required>\n' +
          "</td>\n" +
          '<td style="text-align: center; padding-left: 0;width: 10%;">\n' +
          '<input type="number" class="form-control cantidadFacturaVenta" value="1" min="1" name="cantidadFacturaVenta" stock style="border: none;">\n' +
          "</td>\n" +
          '<td style="text-align: center; padding-left: 0;width: 10%;">\n' +
          '<input type="text" class="form-control precioFacturaVenta" name="precioFacturaVenta" style="border: none;" readonly required>\n' +
          "</td>\n" +
          '<td style="text-align: center; width: 10%;">\n' +
          '<input type="text" class="form-control nuevoImpuestoFacturaVenta" name="nuevoImpuestoFacturaVenta" impuesto style="border: none;" readonly required>\n' +
          "</td>\n" +
          '<td style="text-align: center;width: 10%;">\n' +
          '<input type="text" class="form-control nuevoTotalFacturaVenta" name="nuevoTotalFacturaVenta" precioReal style="border: none;" readonly required>\n' +
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
});

/*=============================================
 SELECCIONAR PRODUCTOS FACTURA
=============================================*/
$(".formularioFacturaVenta").on(
  "change",
  "select.descripcionFacturaVenta",
  function () {
    var nombreProducto = $(this).val();

    var codigo = $(this)
      .parent()
      .parent()
      .children()
      .children(".codigoFacturaVenta");
    var cantidad = $(this)
      .parent()
      .parent()
      .parent()
      .parent()
      .children()
      .children()
      .children()
      .children(".cantidadFacturaVenta");
    var precio = $(this)
      .parent()
      .parent()
      .children()
      .children(".precioFacturaVenta");
    var unidad = $(this)
      .parent()
      .parent()
      .parent()
      .parent()
      .children()
      .children()
      .children()
      .children(".unidadFacturaVenta");
    var impuesto = $(this)
      .parent()
      .parent()
      .children()
      .children(".nuevoImpuestoFacturaVenta");
    var precioTotal = $(this)
      .parent()
      .parent()
      .children()
      .children(".nuevoTotalFacturaVenta");

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
        $(codigo).attr("codigo", respuesta["codigo"]);
        $(codigo).val(respuesta["codigo"]);
        $(cantidad).attr("stock", respuesta["stock"]);
        $(cantidad).attr("nuevoStock", Number(respuesta["stock"]) - 1);
        $(unidad).val(respuesta["unidad"]);
        $(impuesto).attr("impuesto", respuesta["impuesto"]);
        $(impuesto).val(respuesta["impuesto"]);
        $(precio).attr("precioReal", respuesta["precio_unitario"]);
        $(precio).val(respuesta["precio_unitario"]);
        $(precioTotal).attr("precioReal", respuesta["precio_unitario_total"]);
        $(precioTotal).val(respuesta["precio_unitario_total"]);
        /*=============================================
            AGRUPAR PRODUCTOS EN FORMATO JSON
            =============================================*/
        listaProductos();
        /*=============================================
            SUMA TOTAL DE PRECIOS
            =============================================*/
        sumaFactura();
      },
    });
  }
);

/*======================================
 QUITAR PRODUCTO FACTURA DE VENTA
 ======================================**/
$(".formularioFacturaVenta").on("click", "button.quitarProducto", function () {
  $(this).parent().parent().parent().parent().remove();

  if ($(".productoFacturaVenta").children().length == 0) {
    $("#totalFacturaVenta").val(0);
    $("#totalFacturaVenta").attr("total", 0);
  } else {
    /*=============================================
     SUMA TOTAL DE PRECIOS
    =============================================*/
    sumaFactura();
  }
});

/*=============================================
 MODIFICAR LA CANTIDAD
=============================================*/
$(".formularioFacturaVenta").on(
  "change",
  "input.cantidadFacturaVenta",
  function () {
    var precio_Pedido = $(this)
      .parent()
      .parent()
      .children()
      .children(".nuevoTotalFacturaVenta");
    var precioFinal_Pedido = $(this).val() * precio_Pedido.attr("precioReal");
    precio_Pedido.val(precioFinal_Pedido);

    var nuevoStock = Number($(this).attr("stock")) - $(this).val();
    $(this).attr("nuevoStock", nuevoStock);

    if (Number($(this).val()) > Number($(this).attr("stock"))) {
      $(this).val(1);
      var precioFinal_Pedido = $(this).val() * precio_Pedido.attr("precioReal");
      precio_Pedido.val(precioFinal_Pedido);
      /*=============================================
        AGRUPAR PRODUCTOS EN FORMATO JSON
        =============================================*/
      listaProductos();
      /*=============================================
        SUMA TOTAL DE PRECIOS
        =============================================*/
      sumaFactura();
      swal({
        title: "La cantidad supera al Stock Disponible",
        text: "¡Solo hay " + $(this).attr("stock") + " unidades!",
        type: "error",
        confirmButtonText: "Cerrar",
      });
    }
    /*=============================================
    AGRUPAR PRODUCTOS EN FORMATO JSON
    =============================================*/
    listaProductos();
    /*=============================================
    SUMA TOTAL DE PRECIOS
    =============================================*/
    sumaFactura();
  }
);

/*=============================================
 SUMAR TODOS LOS PRECIOS FACTURA
=============================================*/
function sumaFactura() {
  var precioItem = $(".nuevoTotalFacturaVenta");
  var arraySumaFactura = [];

  for (var i = 0; i < precioItem.length; i++) {
    arraySumaFactura.push(Number($(precioItem[i]).val()));
  }

  function sumaArrayPrecios(total, numero) {
    return total + numero;
  }
  var sumaTotalFactura = arraySumaFactura.reduce(sumaArrayPrecios);
  $("#totalFacturaVenta").val(sumaTotalFactura.toFixed(2));
}

/*======================================
 LISTAR TODOS LOS PRODUCTOS
======================================**/
function listaProductos() {
  var listaProductos = [];
  var codigo = $(".codigoFacturaVenta");
  var descripcion = $(".descripcionFacturaVenta");
  var unidad = $(".unidadFacturaVenta");
  var cantidad = $(".cantidadFacturaVenta");
  var precioUnitario = $(".precioFacturaVenta");
  var impuesto = $(".nuevoImpuestoFacturaVenta");
  var total = $(".nuevoTotalFacturaVenta");

  for (var i = 0; i < descripcion.length; i++) {
    listaProductos.push({
      codigo: $(codigo[i]).val(),
      descripcion: $(descripcion[i]).val(),
      unidad: $(unidad[i]).val(),
      cantidad: $(cantidad[i]).val(),
      PrecioUnitario: $(precioUnitario[i]).val(),
      impuesto: $(impuesto[i]).val(),
      total: $(total[i]).val(),
    });
  }
  $("#listaProductosFacturaVenta").val(JSON.stringify(listaProductos));
  /* console.log("LISTA DE PRODUCTOS", listaProductos); */
}

/*======================================
 VISUALIZAR FACTURA DE VENTAS
=======================================*/
$(".btnVerFactura").click(function () {
  var idFactura = $(this).attr("idFactura");
  var datos = new FormData();
  datos.append("idFactura", idFactura);

  $.ajax({
    url: "ajax/factura-ventas.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      var cliente = JSON.parse(respuesta["cliente"]);
      var productos = JSON.parse(respuesta["productos"]);
      /*======================================
        LISTA DE CLIENTE
        =======================================*/
      $("#verCodigo").html(respuesta["codigo"].padStart(10, 0));
      $("#verCodigo").val(respuesta["codigo"].padStart(10, 0));
      $("#verCliente").val(cliente[0].nombre);
      $("#verTipoDocumento").val(cliente[0].tipoDocumento);
      $("#verDocumento").val(cliente[0].documento);
      $("#verTelefonoCliente").val(cliente[0].telefono);
      $("#verEmailCliente").val(cliente[0].correo);
      $("#verDireccionCliente").val(cliente[0].direccion);
      /*======================================
        LISTA DE PRODUCTOS
        =======================================*/
      $("#verCodigoProducto").val(productos[0].codigo);
      $("#verDescripcionProducto").val(productos[0].descripcion);
      $("#verUnidadProducto").html(productos[0].unidad);
      $("#verUnidadProducto").val(productos[0].unidad);
      $("#verCantidadProducto").val(productos[0].cantidad);
      $("#verPrecioProducto").val(productos[0].PrecioUnitario);
      $("#verImpuestoProducto").val(productos[0].impuesto);
      $("#verTotalProducto").val(productos[0].total);

      $("#verTotalFactura").val(respuesta["total_factura"]);
    },
  });
});

/*======================================
EDITAR FACTURA DE VENTAS
=======================================*/
$(".btnEditarFactura").click(function () {
  var idFactura = $(this).attr("idFactura");
  var datos = new FormData();
  datos.append("idFactura", idFactura);

  $.ajax({
    url: "ajax/factura-ventas.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      var cliente = JSON.parse(respuesta["cliente"]);
      var productos = JSON.parse(respuesta["productos"]);
      /*======================================
        LISTA DE CLIENTE
        =======================================*/
      $("#editarCodigo").html(respuesta["codigo"].padStart(10, 0));
      $("#editarCodigo").val(respuesta["codigo"].padStart(10, 0));
      $("#editarCliente").val(cliente[0].nombre);
      $("#editarTipoDocumento").val(cliente[0].tipoDocumento);
      $("#editarDocumento").val(cliente[0].documento);
      $("#editarTelefonoCliente").val(cliente[0].telefono);
      $("#editarEmailCliente").val(cliente[0].correo);
      $("#editarDireccionCliente").val(cliente[0].direccion);
      /*======================================
        LISTA DE PRODUCTOS
        =======================================*/
      $("#editarCodigoProducto").val(productos[0].codigo);
      $("#editarDescripcionProducto").val(productos[0].descripcion);
      $("#editarUnidadProducto").html(productos[0].unidad);
      $("#editarUnidadProducto").val(productos[0].unidad);
      $("#editarCantidadProducto").val(productos[0].cantidad);
      $("#editarPrecioProducto").val(productos[0].PrecioUnitario);
      $("#editarImpuestoProducto").val(productos[0].impuesto);
      $("#editarTotalProducto").val(productos[0].total);

      $("#editarTotalFactura").val(respuesta["total_factura"]);
    },
  });
});

/*======================================
 ELIMINAR FACTURA DE VENTAS
=======================================*/
$(".btnEliminarFactura").click(function () {
  var idFactura = $(this).attr("idFactura");
  swal({
    title: "¿Esta Seguro de Borrar la factura?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar factura",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=facturas&idFactura=" + idFactura;
    }
  });
});
