/*=============================================
 VALIDAR FECHA
=============================================*/
var fechaVencimiento = $("input#nuevaFechaVencimiento");
if (fechaVencimiento.length > 0) {
  fechaVencimiento.datepicker({
    format: "yyyy/mm/dd",
    startDate: new Date(),
  });
}

/*=============================================
 AGREGAR CLIENTES DESDE EL BOTON
=============================================*/
$(".formularioNotaCredito").on(
  "click",
  "button.btnAgregarCliente",
  function () {
    $(this).removeClass("btn-primary btnAgregarCliente");
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
        $(".nuevaNotaCredito").append(
          "" +
            '<input type="hidden" class="form-control" id="idCliente" name="idCliente">\n' +
            '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Cliente:</small></label>\n' +
            '<select class="form-control nuevoClienteNotaCredito" idCliente id="nuevoClienteNotaCredito" name="nuevoClienteNotaCredito">\n' +
            "<option>Selecione un cliente</option>\n" +
            "</select>\n" +
            "</div>\n" +
            '<div class="input-group" style="padding-right: 10px;width: 80px;">\n' +
            '<label><small style="color: #000;">Letra:</small></label>\n' +
            '<input type="text" class="form-control nuevaLetraNotaCredito" id="nuevaLetraNotaCredito" name="nuevaLetraNotaCredito" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-2 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Documento de Identidad:</small></label>\n' +
            '<input type="text" class="form-control nuevoDocumentoNotaCredito" id="nuevoDocumentoNotaCredito" name="nuevoDocumentoNotaCredito" value="">\n' +
            "</div>\n" +
            '<div class="input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Telefono:</small></label>\n' +
            '<input type="text" class="form-control nuevoTelefonoNotaCredito" id="nuevoTelefonoNotaCredito" name="nuevoTelefonoNotaCredito" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Correo Electronico:</small></label>\n' +
            '<input type="text" class="form-control nuevoEmailNotaCredito" id="nuevoEmailNotaCredito" name="nuevoEmailNotaCredito" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-4 input-group">\n' +
            '<label><small style="color: #000;">Direccion:</small></label>\n' +
            '<input type="text" class="form-control nuevaDireccionNotaCredito" id="nuevaDireccionNotaCredito" name="nuevaDireccionNotaCredito" value="">\n' +
            "</div>"
        );
        /*=============================================
                 AGREGAR LOS CLIENTES AL SELECT
             =============================================*/
        respuesta.forEach(funcionForEach);

        function funcionForEach(item, index) {
          $(".nuevoClienteNotaCredito").append(
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
$(".formularioNotaCredito").on(
  "change",
  "select.nuevoClienteNotaCredito",
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
        $(".nuevaLetraNotaCredito").val(respuesta["tipo_documento"]);
        $(".nuevoDocumentoNotaCredito").val(respuesta["documento"]);
        $(".nuevoTelefonoNotaCredito").val(respuesta["telefono"]);
        $(".nuevoEmailNotaCredito").val(respuesta["email"]);
        $(".nuevaDireccionNotaCredito").val(respuesta["direccion"]);
        /*=============================================
        AGRUPAR CLIENTE EN FORMATO JSON
        =============================================*/
        listaClienteNotaCredito();
        /*======================================
        AGREGAR IDCLIENTE
        ======================================**/
        idClienteNotaCredito();
      },
    });
  }
);

/*======================================
 AGREGAR IDCLIENTE
======================================**/
function idClienteNotaCredito() {
  var idCliente = $("#idCliente");
  $("#clienteID").val(idCliente);
  /* console.log("ID CLIENTE", idCliente.val()); */
}

/*======================================
  LISTAR TODOS LOS CLIENTES
  ======================================**/
function listaClienteNotaCredito() {
  var listaCliente = [];
  var idCliente = $("#idCliente");
  var cliente = $(".nuevoClienteNotaCredito");
  var tipoDocumento = $(".nuevaLetraNotaCredito");
  var documento = $(".nuevoDocumentoNotaCredito");
  var telefono = $(".nuevoTelefonoNotaCredito");
  var correo = $(".nuevoEmailNotaCredito");
  var direccion = $(".nuevaDireccionNotaCredito");

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
    AGRGANDO PRODUCTO PARA COMPUESTOS
======================================**/
var numProducto = 0;
$(".formularioNotaCredito").on(
  "click",
  "button.btnAgregarNotaCredito",
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
        $(".productoNotaCredito").append(
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
            '<input class="form-control codigoNotaCredito" name="codigoNotaCredito" codigo value readonly required>\n' +
            "</td>\n" +
            '<td style="text-align: center;width: 25%;">\n' +
            '<select class="form-control descripcionNotaCredito" id="producto' +
            numProducto +
            '" idProducto name="descripcionNotaCredito" required>\n' +
            "<option>Seleciones un producto</option>\n" +
            "</select>\n" +
            "</td>\n" +
            '<td style="text-align: center; padding-left: 0;width: 10%;">\n' +
            '<input type="text" class="form-control unidadNotaCredito" name="unidadNotaCredito" id="unidad' +
            numUnidad +
            '" idUnidad required readonly>\n' +
            "</td>\n" +
            '<td style="text-align: center; padding-left: 0;width: 10%;">\n' +
            '<input type="text" class="form-control precioNotaCredito" name="precioNotaCredito" precioReal="" style="border: none;" readonly required>\n' +
            "</td>\n" +
            '<td style="text-align: center; width: 10%;">\n' +
            '<input type="text" class="form-control impuestoNotaCredito" name="impuestoNotaCredito" required readonly>\n' +
            "</td>\n" +
            '<td style="text-align: center; width: 10%;">\n' +
            '<input type="number" class="form-control cantidadNotaCredito" min="1" value="1" name="cantidadNotaCredito" required>\n' +
            "</td>\n" +
            '<td style="text-align: center; width: 10%;">\n' +
            '<input type="text" class="form-control totalNotaCredito" name="totalNotaCredito" total required readonly>\n' +
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
 SELECCIONAR PRODUCTOS NOTA DE ENTREGA
=============================================*/
$(".formularioNotaCredito").on(
  "change",
  "select.descripcionNotaCredito",
  function () {
    var nombreProducto = $(this).val();
    var codigo = $(this)
      .parent()
      .parent()
      .children()
      .children(".codigoNotaCredito");
    var unidad = $(this)
      .parent()
      .parent()
      .children()
      .children(".unidadNotaCredito");
    var precio = $(this)
      .parent()
      .parent()
      .children()
      .children(".precioNotaCredito");
    var impuesto = $(this)
      .parent()
      .parent()
      .children()
      .children(".impuestoNotaCredito");
    var cantidad = $(this)
      .parent()
      .parent()
      .parent()
      .children()
      .children()
      .children(".cantidadNotaCredito");
    var total = $(this)
      .parent()
      .parent()
      .children()
      .children(".totalNotaCredito");

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
        $(total).attr("precioReal", respuesta["precio_unitario_total"]);
        $(total).val(respuesta["precio_unitario_total"]);
        /*=============================================
        AGRUPAR PRODUCTOS EN FORMATO JSON
        =============================================*/
        listaProductosNotaCredito();
        /*=============================================
        SUMA TOTAL DE PRECIOS
        =============================================*/
        sumaPrecios__NotaCredito();
      },
    });
  }
);

/*======================================
 QUITAR PRODUCTOS COMPUESTOS
 ======================================**/
$(".formularioNotaCredito").on("click", "button.quitarProducto", function () {
  $(this).parent().parent().parent().parent().remove();

  /*=============================================
  AGRUPAR PRODUCTOS EN FORMATO JSON
  =============================================*/
  listaProductosNotaCredito();
  /*=============================================
  SUMA TOTAL DE PRECIOS
  =============================================*/
  sumaPrecios__NotaCredito();
});

/*=============================================
 MODIFICAR LA CANTIDAD
=============================================*/
$(".formularioNotaCredito").on(
  "change",
  "input.cantidadNotaCredito",
  function () {
    var precio = $(this)
      .parent()
      .parent()
      .children()
      .children(".totalNotaCredito");
    var precioFinal = $(this).val() * precio.attr("precioReal");
    precio.val(precioFinal);

    if (Number($(this).val()) > Number($(this).attr("stock"))) {
      $(this).val(1);
      /*=============================================
      AGRUPAR PRODUCTOS EN FORMATO JSON
      =============================================*/
      listaProductosNotaCredito();
      /*=============================================
      SUMA TOTAL DE PRECIOS
      =============================================*/
      sumaPrecios__NotaCredito();
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
    listaProductosNotaCredito();
    /*=============================================
    SUMA TOTAL DE PRECIOS
    =============================================*/
    sumaPrecios__NotaCredito();
  }
);

/*=============================================
 SUMAR TODOS LOS PRECIOS EN NOTA DE ENTREGA VENTA
=============================================*/
function sumaPrecios__NotaCredito() {
  var precioItem = $(".totalNotaCredito");
  var arraySumaNotaCredito = [];

  for (let i = 0; i < precioItem.length; i++) {
    arraySumaNotaCredito.push(Number($(precioItem[i]).val()));
  }

  function sumaArrayNotaCredito(total, numero) {
    return total + numero;
  }

  var sumaTotalNotaCredito = arraySumaNotaCredito.reduce(sumaArrayNotaCredito);
  $("#total__notaCredito").val(sumaTotalNotaCredito.toFixed(2));
}

/*======================================
   FORMATEAR PRECIOS
   ======================================**/
$("#total__notaCredito").number(true, 2, ",", ".");

/*======================================
   LISTAR TODOS LOS PRODUCTOS
  ======================================**/
function listaProductosNotaCredito() {
  var listaProductos_NotaCredito = [];
  var codigo = $(".codigoNotaCredito");
  var descripcion = $(".descripcionNotaCredito");
  var unidad = $(".unidadNotaCredito");
  var cantidad = $(".cantidadNotaCredito");
  var impuesto = $(".impuestoNotaCredito");
  var precioUnitario = $(".precioNotaCredito");
  var total = $(".totalNotaCredito");

  for (var i = 0; i < descripcion.length; i++) {
    listaProductos_NotaCredito.push({
      codigo: $(codigo[i]).val(),
      descripcion: $(descripcion[i]).val(),
      unidad: $(unidad[i]).val(),
      cantidad: $(cantidad[i]).val(),
      impuesto: $(impuesto[i]).val(),
      PrecioUnitario: $(precioUnitario[i]).val(),
      total: $(total[i]).val(),
    });
  }
  /* console.log(listaProductos_NotaCredito); */
  $("#listarProductos").val(JSON.stringify(listaProductos_NotaCredito));
}

/*======================================
 VISUALIZAR NOTA DE CREDITO
=======================================*/
$(".btnVerNotaCredito").click(function () {
  var idNotaCredito = $(this).attr("idNotaCredito");
  var datos = new FormData();
  datos.append("idNotaCredito", idNotaCredito);

  $.ajax({
    url: "ajax/nota-credito.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      var cliente = JSON.parse(respuesta["cliente"]);
      var productos = JSON.parse(respuesta["productos"]);
      $("#verFechaEmision").val(respuesta["fecha_registro"]);
      $("#verFechaVencimiento").val(respuesta["fecha_vencimiento"]);
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

      $("#verTotalNotaCredito").val(respuesta["total_nota_credito"]);
    },
  });
});
