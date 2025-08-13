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
$(".formularioPresupuesto").on(
  "click",
  "button.btnClientePresupuesto",
  function () {
    $(this).removeClass("btn-primary btnClientePresupuesto");
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
        $(".nuevoCliente").append(
          "" +
            '<input type="hidden" class="form-control" id="idCliente" name="idCliente">\n' +
            '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Cliente:</small></label>\n' +
            '<select class="form-control nuevoClientePresupuesto" idCliente id="nuevoClientePresupuesto" name="nuevoClientePresupuesto">\n' +
            "<option>Seleccione un cliente</option>\n" +
            "</select>\n" +
            "</div>\n" +
            '<div class="input-group" style="padding-right: 10px;width: 80px;">\n' +
            '<label><small style="color: #000;">Letra:</small></label>\n' +
            '<input type="text" class="form-control nuevaLetraPresupuesto" id="nuevaLetraPresupuesto" name="nuevaLetraPresupuesto" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-2 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Documento de Identidad:</small></label>\n' +
            '<input type="text" class="form-control nuevoDocumentoPresupuesto" id="nuevoDocumentoPresupuesto" name="nuevoDocumentoPresupuesto" value="">\n' +
            "</div>\n" +
            '<div class="input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Telefono:</small></label>\n' +
            '<input type="text" class="form-control nuevoTelefonoPresupuesto" id="nuevoTelefonoPresupuesto" name="nuevoTelefonoPresupuesto" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Correo Electronico:</small></label>\n' +
            '<input type="text" class="form-control nuevoEmailPresupuesto" id="nuevoEmailPresupuesto" name="nuevoEmailPresupuesto" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-4 input-group">\n' +
            '<label><small style="color: #000;">Direccion:</small></label>\n' +
            '<input type="text" class="form-control nuevaDireccionPresupuesto" id="nuevaDireccionPresupuesto" name="nuevaDireccionPresupuesto" value="">\n' +
            "</div>"
        );
        /*=============================================
                 AGREGAR LOS CLIENTES AL SELECT
             =============================================*/
        respuesta.forEach(funcionForEach);

        function funcionForEach(item, index) {
          $(".nuevoClientePresupuesto").append(
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
$(".formularioPresupuesto").on(
  "change",
  "select.nuevoClientePresupuesto",
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
        $(".nuevaLetraPresupuesto").val(respuesta["tipo_documento"]);
        $(".nuevoDocumentoPresupuesto").val(respuesta["documento"]);
        $(".nuevoTelefonoPresupuesto").val(respuesta["telefono"]);
        $(".nuevoEmailPresupuesto").val(respuesta["email"]);
        $(".nuevaDireccionPresupuesto").val(respuesta["direccion"]);
        /*=============================================
        AGRUPAR PROVEEDOR EN FORMATO JSON
        =============================================*/
        listaClientePresupuesto();
        /*======================================
        AGREGAR IDPROVEEDORES
        ======================================**/
        idClientePresupuesto();
      },
    });
  }
);

/*======================================
 AGREGAR IDCLIENTE
======================================**/
function idClientePresupuesto() {
  var idCliente = $("#idCliente");
  $("#clienteID").val(idCliente);
  /* console.log("ID CLIENTE", idCliente.val()); */
}

/*======================================
LISTAR TODOS LOS CLIENTES
======================================**/
function listaClientePresupuesto() {
  var listaCliente = [];
  var idCliente = $("#idCliente");
  var cliente = $(".nuevoClientePresupuesto");
  var tipoDocumento = $(".nuevaLetraPresupuesto");
  var documento = $(".nuevoDocumentoPresupuesto");
  var telefono = $(".nuevoTelefonoPresupuesto");
  var correo = $(".nuevoEmailPresupuesto");
  var direccion = $(".nuevaDireccionPresupuesto");

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
 AGRGANDO PRESUPUESTO
======================================**/
var numProducto = 0;
$(".formularioPresupuesto").on(
  "click",
  "button.btnAgregarPresupuesto",
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
        $(".nuevoProductoPresupuesto").append(
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
            '<input type="text" class="form-control precioProducto" name="precioProducto" precioReal="" style="border: none;" readonly required>\n' +
            "</td>\n" +
            '<td style="text-align: center; width: 10%;">\n' +
            '<input type="text" class="form-control impuestoProducto" name="impuestoProducto" required readonly>\n' +
            "</td>\n" +
            '<td style="text-align: center; padding-left: 0;width: 10%;">\n' +
            '<input type="number" class="form-control cantidadProducto" value="1" min="1" name="cantidadProducto" stock style="border: none;">\n' +
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
        /*=============================================
             FORMATEAR PRECIOS
            =============================================*/
        $(".totalProducto").number(true, 2);
      },
    });
  }
);

/*=============================================
 SELECCIONAR PRODUCTOS NOTA DE ENTREGA
=============================================*/
$(".formularioPresupuesto").on(
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
    var impuesto = $(this)
      .parent()
      .parent()
      .children()
      .children(".impuestoProducto");
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
        $(impuesto).attr("impuesto", respuesta["impuesto"]);
        $(impuesto).val(respuesta["impuesto"]);
        $(precio).attr("precioReal", respuesta["precio_unitario"]);
        $(precio).val(respuesta["precio_unitario"]);
        $(total).attr("precioReal", respuesta["precio_unitario_total"]);
        $(total).val(respuesta["precio_unitario_total"]);
        /*=============================================
        AGRUPAR PRODUCTOS EN FORMATO JSON
        =============================================*/
        listaProductosPresupuesto();
        /*=============================================
        SUMA TOTAL DE PRECIOS
        =============================================*/
        sumaPreciosPresupuesto();
      },
    });
  }
);

/*======================================
 QUITAR PRODUCTOS COMPUESTOS
 ======================================**/
$(".formularioPresupuesto").on("click", "button.quitarProducto", function () {
  $(this).parent().parent().parent().parent().remove();
  if ($(".nuevoProductoPresupuesto").children().length == 0) {
    $("#total__notaEntrega").val(0);
  } else {
    /*=============================================
    SUMA TOTAL DE PRECIOS
    =============================================*/
    sumaPreciosPresupuesto();
  }
});

/*=============================================
 MODIFICAR LA CANTIDAD
=============================================*/
$(".formularioPresupuesto").on("change", "input.cantidadProducto", function () {
  var precio = $(this).parent().parent().children().children(".totalProducto");
  var precioFinal = $(this).val() * precio.attr("precioReal");
  precio.val(precioFinal);

  if (Number($(this).val()) > Number($(this).attr("stock"))) {
    $(this).val(1);
    var precioFinal = $(this).val() * precio.attr("precioReal");
    precio.val(precioFinal);
    /*=============================================
    SUMA TOTAL DE PRECIOS
    =============================================*/
    sumaPreciosPresupuesto();
    swal({
      title: "La cantidad supera al Stock Disponible",
      text: "¡Solo hay " + $(this).attr("stock") + " unidades!",
      type: "error",
      confirmButtonText: "Cerrar",
    });
  }
  /*=============================================
  SUMA TOTAL DE PRECIOS
  =============================================*/
  sumaPreciosPresupuesto();
});

/*=============================================
 SUMAR TODOS LOS PRECIOS PRESUPUESTO
=============================================*/
function sumaPreciosPresupuesto() {
  var precioItem = $(".totalProducto");
  var arraySumaPresupuesto = [];

  for (let i = 0; i < precioItem.length; i++) {
    arraySumaPresupuesto.push(Number($(precioItem[i]).val()));
  }

  function sumaArrayPresupuesto(total, numero) {
    return total + numero;
  }

  var sumaTotalPresupuesto = arraySumaPresupuesto.reduce(sumaArrayPresupuesto);
  $("#total__Presupuesto").val(sumaTotalPresupuesto.toFixed(2));
}

/*======================================
 FORMATEAR PRECIOS
 ======================================**/
$("#total__notaEntrega").number(true, 2, ",", ".");

/*======================================
 LISTAR TODOS LOS PRODUCTOS
======================================**/
function listaProductosPresupuesto() {
  var listaProductos_Presupuesto = [];
  var codigo = $(".codigoProducto");
  var descripcion = $(".descripcionProducto");
  var unidad = $(".unidadProducto");
  var cantidad = $(".cantidadProducto");
  var impuesto = $(".impuestoProducto");
  var precioUnitario = $(".precioProducto");
  var total = $(".totalProducto");

  for (var i = 0; i < descripcion.length; i++) {
    listaProductos_Presupuesto.push({
      codigo: $(codigo[i]).val(),
      descripcion: $(descripcion[i]).val(),
      unidad: $(unidad[i]).val(),
      cantidad: $(cantidad[i]).val(),
      impuesto: $(impuesto[i]).val(),
      PrecioUnitario: $(precioUnitario[i]).val(),
      total: $(total[i]).val(),
    });
  }
  /* console.log(listaProductos_Presupuesto); */
  $("#listarProductos").val(JSON.stringify(listaProductos_Presupuesto));
}

/*======================================
 VISUALIZAR PRESUPUESTO
=======================================*/
$(".btnVerPresupuesto").click(function () {
  var idPresupuesto = $(this).attr("idPresupuesto");
  var datos = new FormData();
  datos.append("idPresupuesto", idPresupuesto);

  $.ajax({
    url: "ajax/presupuesto.ajax.php",
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

      $("#verTotalPresupuesto").val(respuesta["total_presupuesto"]);
    },
  });
});

/*======================================
 EDITAR PRESUPUESTO
=======================================*/
$(".btnEditarPresupuesto").click(function () {
  var idPresupuesto = $(this).attr("idPresupuesto");
  var datos = new FormData();
  datos.append("idPresupuesto", idPresupuesto);

  $.ajax({
    url: "ajax/presupuesto.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      var cliente = JSON.parse(respuesta["cliente"]);
      var productos = JSON.parse(respuesta["productos"]);

      $("#editarFechaEmision").val(respuesta["fecha_registro"]);
      $("#editarFechaVencimiento").val(respuesta["fecha_vencimiento"]);
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

      $("#editarTotalPresupuesto").val(respuesta["total_presupuesto"]);
    },
  });
});

/*======================================
 ELIMINAR PEDIDO
=======================================*/
$(".btnEliminarPresupuesto").click(function () {
  var idPresupuesto = $(this).attr("idPresupuesto");
  swal({
    title: "¿Esta Seguro de Borrar el presupuesto?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar presupuesto",
  }).then((result) => {
    if (result.value) {
      window.location =
        "index.php?ruta=presupuesto&idPresupuesto=" + idPresupuesto;
    }
  });
});
