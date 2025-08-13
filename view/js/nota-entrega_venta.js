/*=============================================
 VALIDAR FECHA
=============================================*/
var fechaRegistro = $("input#nuevaFechaRegistro");
if (fechaRegistro.length > 0) {
  fechaRegistro.datepicker({
    format: "yyyy/mm/dd",
    startDate: new Date(),
  });
}

/*=============================================
 AGREGAR CLIENTES DESDE EL BOTON
=============================================*/
$(".formulario__NotaEntregaVenta").on(
  "click",
  "button.btnNotaEntregaVenta",
  function () {
    $(this).removeClass("btn-primary btnNotaEntregaVenta");
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
        $(".ClienteNotaEntregaVenta").append(
          "" +
            '<input type="hidden" class="form-control" id="idCliente" name="idCliente">\n' +
            '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Cliente:</small></label>\n' +
            '<select class="form-control nuevoClienteNotaEntregaVenta" idCliente id="nuevoClienteNotaEntregaVenta" name="nuevoClienteNotaEntregaVenta">\n' +
            "<option>Selecione un cliente</option>\n" +
            "</select>\n" +
            "</div>\n" +
            '<div class="input-group" style="padding-right: 10px;width: 80px;">\n' +
            '<label><small style="color: #000;">Letra:</small></label>\n' +
            '<input type="text" class="form-control nuevaLetraNotaEntregaVenta" id="nuevaLetraNotaEntregaVenta" name="nuevaLetraNotaEntregaVenta" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-2 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Documento de Identidad:</small></label>\n' +
            '<input type="text" class="form-control nuevoDocumentoNotaEntregaVenta" id="nuevoDocumentoNotaEntregaVenta" name="nuevoDocumentoNotaEntregaVenta" value="">\n' +
            "</div>\n" +
            '<div class="input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Telefono:</small></label>\n' +
            '<input type="text" class="form-control nuevoTelefonoNotaEntregaVenta" id="nuevoTelefonoNotaEntregaVenta" name="nuevoTelefonoNotaEntregaVenta" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Correo Electronico:</small></label>\n' +
            '<input type="text" class="form-control nuevoEmailNotaEntregaVenta" id="nuevoEmailNotaEntregaVenta" name="nuevoEmailNotaEntregaVenta" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-4 input-group">\n' +
            '<label><small style="color: #000;">Direccion:</small></label>\n' +
            '<input type="text" class="form-control nuevaDireccionNotaEntregaVenta" id="nuevaDireccionNotaEntregaVenta" name="nuevaDireccionNotaEntregaVenta" value="">\n' +
            "</div>"
        );
        /*=============================================
        AGREGAR LOS CLIENTES AL SELECT
        =============================================*/
        respuesta.forEach(funcionForEach);

        function funcionForEach(item, index) {
          $(".nuevoClienteNotaEntregaVenta").append(
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
$(".formulario__NotaEntregaVenta").on(
  "change",
  "select.nuevoClienteNotaEntregaVenta",
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
        $(".nuevaLetraNotaEntregaVenta").val(respuesta["tipo_documento"]);
        $(".nuevoDocumentoNotaEntregaVenta").val(respuesta["documento"]);
        $(".nuevoTelefonoNotaEntregaVenta").val(respuesta["telefono"]);
        $(".nuevoEmailNotaEntregaVenta").val(respuesta["email"]);
        $(".nuevaDireccionNotaEntregaVenta").val(respuesta["direccion"]);
        /*=============================================
        AGRUPAR PROVEEDOR EN FORMATO JSON
        =============================================*/
        listaClienteNotaEntregaVenta();
        /*======================================
        AGREGAR IDPROVEEDORES
        ======================================**/
        idClienteNotaEntregaVenta();
      },
    });
  }
);

/*======================================
 AGREGAR IDCLIENTE
======================================**/
function idClienteNotaEntregaVenta() {
  var idCliente = $("#idCliente");
  $("#clienteID").val(idCliente);
  /* console.log("ID CLIENTE", idCliente.val()); */
}

/*======================================
LISTAR TODOS LOS CLIENTES
======================================**/
function listaClienteNotaEntregaVenta() {
  var listaCliente = [];
  var idCliente = $("#idCliente");
  var cliente = $(".nuevoClienteNotaEntregaVenta");
  var tipoDocumento = $(".nuevaLetraNotaEntregaVenta");
  var documento = $(".nuevoDocumentoNotaEntregaVenta");
  var telefono = $(".nuevoTelefonoNotaEntregaVenta");
  var correo = $(".nuevoEmailNotaEntregaVenta");
  var direccion = $(".nuevaDireccionNotaEntregaVenta");

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
 AGRGANDO PRODUCTO NOTA DE ENTREGA VENTA
======================================**/
var numProducto = 0;
$(".btnproductoNotaEntregaVenta").click(function () {
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
      $(".productosNotaEntregaVenta").append(
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
          '<input class="form-control codigoProducto" name="codigoProducto" codigo value readonly required>\n' +
          "</td>\n" +
          '<td style="text-align: center;width: 25%;">\n' +
          '<select class="form-control descripcionProducto" id="producto' +
          numProducto +
          '" idProducto name="descripcionProducto" required>\n' +
          "<option>Seleciones un producto</option>\n" +
          "</select>\n" +
          "</td>\n" +
          '<td style="text-align: center;width: 10%;">\n' +
          '<input class="form-control unidadProducto" name="unidadProducto" id="unidad' +
          numUnidad +
          '" idUnidad required readonly>\n' +
          "</td>\n" +
          '<td style="text-align: center; padding-left: 0;width: 10%;">\n' +
          '<input type="text" class="form-control precioProducto" name="precioProducto" value="" precioReal="" style="border: none;" readonly required>\n' +
          "</td>\n" +
          '<td style="text-align: center; width: 10%;">\n' +
          '<input type="number" class="form-control cantidadProducto" min="1" value="1" name="cantidadProducto" required>\n' +
          "</td>\n" +
          '<td style="text-align: center; width: 10%;">\n' +
          '<input type="text" class="form-control totalProducto" name="totalProducto" total required readonly>\n' +
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
              item.id_producto +
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
 SELECCIONAR PRODUCTOS NOTA DE ENTREGA VENTA
=============================================*/
$(".formulario__NotaEntregaVenta").on(
  "change",
  "select.descripcionProducto",
  function () {
    var nombreProducto = $(this).val();
    var codigo = $(this)
      .parent()
      .parent()
      .children()
      .children(".codigoProducto");
    var unidad = $(this)
      .parent()
      .parent()
      .children()
      .children(".unidadProducto");
    var precio = $(this)
      .parent()
      .parent()
      .children()
      .children(".precioProducto");
    var cantidad = $(this)
      .parent()
      .parent()
      .parent()
      .children()
      .children()
      .children(".cantidadProducto");
    var total = $(this).parent().parent().children().children(".totalProducto");

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
        $(precio).attr("precioReal", respuesta["precio_unitario"]);
        $(precio).val(respuesta["precio_unitario"]);
        $(total).attr("precioReal", respuesta["precio_unitario_total"]);
        $(total).val(respuesta["precio_unitario_total"]);
        /*=============================================
        AGRUPAR PRODUCTOS EN FORMATO JSON
        =============================================*/
        listaProductosNotaEntregaVenta();
        /*=============================================
        SUMA TOTAL DE PRECIOS
        =============================================*/
        sumaPrecios__notaEntregaVenta();
      },
    });
  }
);

/*======================================
 QUITAR PRODUCTOS NOTA ENTREGA VENTA
 ======================================**/
$(".formulario__NotaEntregaVenta").on(
  "click",
  "button.quitarProducto",
  function () {
    $(this).parent().parent().parent().parent().remove();

    if ($(".productosNotaEntregaVenta").children().length == 0) {
      $("#total__notaEntregaVenta").val(0);
    } else {
      /*=============================================
      AGRUPAR PRODUCTOS EN FORMATO JSON
      =============================================*/
      listaProductosNotaEntregaVenta();
      /*=============================================
      SUMA TOTAL DE PRECIOS
      =============================================*/
      sumaPrecios__notaEntregaVenta();
    }
  }
);

/*=============================================
 MODIFICAR LA CANTIDAD NOTA DE ENTREGA VENTA
=============================================*/
$(".formulario__NotaEntregaVenta").on(
  "change",
  "input.cantidadProducto",
  function () {
    var precio = $(this)
      .parent()
      .parent()
      .children()
      .children(".totalProducto");
    var precioFinal = $(this).val() * precio.attr("precioReal");
    precio.val(precioFinal);

    if (Number($(this).val()) > Number($(this).attr("stock"))) {
      $(this).val(1);
      var precioFinal = $(this).val() * precio.attr("precioReal");
      precio.val(precioFinal);
      /*=============================================
      AGRUPAR PRODUCTOS EN FORMATO JSON
      =============================================*/
      listaProductosNotaEntregaVenta();
      /*=============================================
      SUMA TOTAL DE PRECIOS
      =============================================*/
      sumaPrecios__notaEntregaVenta();
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
    listaProductosNotaEntregaVenta();
    /*=============================================
    SUMA TOTAL DE PRECIOS
    =============================================*/
    sumaPrecios__notaEntregaVenta();
  }
);

/*=============================================
 SUMAR TODOS LOS PRECIOS EN NOTA DE ENTREGA VENTA
=============================================*/
function sumaPrecios__notaEntregaVenta() {
  var precioItem = $(".totalProducto");
  var arraySumaNotaEntregaVenta = [];

  for (let i = 0; i < precioItem.length; i++) {
    arraySumaNotaEntregaVenta.push(Number($(precioItem[i]).val()));
  }

  function sumaArrayNotaEntregaVenta(total, numero) {
    return total + numero;
  }

  var sumaTotalNotaEntregaVenta = arraySumaNotaEntregaVenta.reduce(
    sumaArrayNotaEntregaVenta
  );
  $("#total__notaEntregaVenta").val(sumaTotalNotaEntregaVenta.toFixed(2));
}

/*======================================
 FORMATEAR PRECIOS
 ======================================**/
$("#total__notaEntrega").number(true, 2, ",", ".");

/*======================================
 LISTAR TODOS LOS PRODUCTOS
======================================**/
function listaProductosNotaEntregaVenta() {
  var listaProductos_NotaEntregaVenta = [];
  var codigo = $(".codigoProducto");
  var descripcion = $(".descripcionProducto");
  var unidad = $(".unidadProducto");
  var cantidad = $(".cantidadProducto");
  var precioUnitario = $(".precioProducto");
  var total = $(".totalProducto");

  for (var i = 0; i < descripcion.length; i++) {
    listaProductos_NotaEntregaVenta.push({
      codigo: $(codigo[i]).val(),
      descripcion: $(descripcion[i]).val(),
      unidad: $(unidad[i]).val(),
      cantidad: $(cantidad[i]).val(),
      PrecioUnitario: $(precioUnitario[i]).val(),
      total: $(total[i]).val(),
    });
  }
  /* console.log(listaProductos_NotaEntregaVenta); */
  $("#listarProductos").val(JSON.stringify(listaProductos_NotaEntregaVenta));
}

/*======================================
 VISUALIZAR NOTA DE ENTREGA VENTA
=======================================*/
$(".btnVerNotaEntrega").click(function () {
  var idNotaVentas = $(this).attr("idNotaVentas");
  var datos = new FormData();
  datos.append("idNotaVentas", idNotaVentas);

  $.ajax({
    url: "ajax/nota-entrega-ventas.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      var cliente = JSON.parse(respuesta["cliente"]);
      var productos = JSON.parse(respuesta["productos"]);

      $("#verNumeroRecepcionVenta").html(
        respuesta["numero_recepcion"].padStart(10, 0)
      );
      $("#verNumeroRecepcionVenta").val(
        respuesta["numero_recepcion"].padStart(10, 0)
      );
      $("#verNumeroNotaEntrega").html(respuesta["numero_nota"].padStart(10, 0));
      $("#verNumeroNotaEntrega").val(respuesta["numero_nota"].padStart(10, 0));
      $("#verFechaNota").val(respuesta["fecha_nota"]);
      $("#verFechaRegistro").val(respuesta["feregistro_nota"]);
      /*======================================
        LISTA DE CLIENTE
        =======================================*/
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
      $("#verTotalProducto").val(productos[0].total);

      $("#verTotalNotaEntregaVenta").val(respuesta["total_nota_entrega_venta"]);
    },
  });
});

/*======================================
 EDITAR NOTA ENTREGA VENTA
=======================================*/
$(".btnEditarNotaEntrega").click(function () {
  var idNota = $(this).attr("idNota");
  var datos = new FormData();
  datos.append("idNota", idNota);

  $.ajax({
    url: "ajax/nota-entrega-ventas.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      var cliente = JSON.parse(respuesta["cliente"]);
      var productos = JSON.parse(respuesta["productos"]);

      $("#editarNumeroRecepcionVenta").html(
        respuesta["numero_recepcion"].padStart(10, 0)
      );
      $("#editarNumeroRecepcionVenta").val(
        respuesta["numero_recepcion"].padStart(10, 0)
      );
      $("#editarNumeroNotaEntrega").html(
        respuesta["numero_nota"].padStart(10, 0)
      );
      $("#editarNumeroNotaEntrega").val(
        respuesta["numero_nota"].padStart(10, 0)
      );
      $("#editarFechaNota").val(respuesta["fecha_nota"]);
      $("#editarFechaRegistro").val(respuesta["feregistro_nota"]);
      /*======================================
        LISTA DE CLIENTE
        =======================================*/
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
      $("#editarTotalProducto").val(productos[0].total);

      $("#editarTotalNotaEntregaVenta").val(
        respuesta["total_nota_entrega_venta"]
      );
    },
  });
});

/*======================================
 ELIMINAR NOTA ENTREGA VENTA
=======================================*/
$(".btnEliminarNotaEntrega").click(function () {
  var idNota = $(this).attr("idNota");
  swal({
    title: "¿Esta Seguro de Borrar la nota de entrega?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar nota entrega",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=notas-entrega-venta&idNota=" + idNota;
    }
  });
});
