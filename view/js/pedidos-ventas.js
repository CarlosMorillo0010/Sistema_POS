/*=============================================
 AGREGAR CLIENTES DESDE EL BOTON
=============================================*/
$(".formularioPedido").on("click", "button.btnClientePedido", function () {
  $(this).removeClass("btn-primary btnClientePedido");
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
      $(".clientePedido").append(
        "" +
          '<input type="hidden" class="form-control" id="idCliente" name="idCliente">\n' +
          '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
          '<label><small style="color: #000;">Cliente:</small></label>\n' +
          '<select class="form-control nuevoClientePedido" idCliente id="nuevoClientePedido" name="nuevoClientePedido">\n' +
          "<option>Selecione un cliente</option>\n" +
          "</select>\n" +
          "</div>\n" +
          '<div class="input-group" style="padding-right: 10px;width: 80px;">\n' +
          '<label><small style="color: #000;">Letra:</small></label>\n' +
          '<input type="text" class="form-control nuevaLetra" id="nuevaLetra" name="nuevaLetra" value="">\n' +
          "</div>\n" +
          '<div class="col-lg-2 input-group" style="padding-right: 10px;">\n' +
          '<label><small style="color: #000;">Documento de Identidad:</small></label>\n' +
          '<input type="text" class="form-control nuevoDocumentoPedido" id="nuevoDocumentoPedido" name="nuevoDocumentoPedido" value="">\n' +
          "</div>\n" +
          '<div class="input-group" style="padding-right: 10px;">\n' +
          '<label><small style="color: #000;">Telefono:</small></label>\n' +
          '<input type="text" class="form-control nuevoTelefonoPedido" id="nuevoTelefonoPedido" name="nuevoTelefonoPedido" value="">\n' +
          "</div>\n" +
          '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
          '<label><small style="color: #000;">Correo Electronico:</small></label>\n' +
          '<input type="text" class="form-control nuevoEmailPedido" id="nuevoEmailPedido" name="nuevoEmailPedido" value="">\n' +
          "</div>\n" +
          '<div class="col-lg-4 input-group">\n' +
          '<label><small style="color: #000;">Direccion:</small></label>\n' +
          '<input type="text" class="form-control nuevaDireccionPedido" id="nuevaDireccionPedido" name="nuevaDireccionPedido" value="">\n' +
          "</div>"
      );
      /*=============================================
                 AGREGAR LOS CLIENTES AL SELECT
             =============================================*/
      respuesta.forEach(funcionForEach);

      function funcionForEach(item, index) {
        $(".nuevoClientePedido").append(
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
});

/*=============================================
 SELECCIONAR CLIENTE
=============================================*/
$(".formularioPedido").on("change", "select.nuevoClientePedido", function () {
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
      $(".nuevaLetra").val(respuesta["tipo_documento"]);
      $(".nuevoDocumentoPedido").val(respuesta["documento"]);
      $(".nuevoTelefonoPedido").val(respuesta["telefono"]);
      $(".nuevoEmailPedido").val(respuesta["email"]);
      $(".nuevaDireccionPedido").val(respuesta["direccion"]);
      /*=============================================
             AGRUPAR PROVEEDOR EN FORMATO JSON
            =============================================*/
      listaCliente();
      /*======================================
            AGREGAR IDPROVEEDORES
            ======================================**/
      idCliente_Pedido();
    },
  });
});

/*======================================
 AGREGAR IDCLIENTE
======================================**/
function idCliente_Pedido() {
  var idCliente = $("#idCliente");
  $("#clienteID").val(idCliente);
  /*console.log("ID CLIENTE", idCliente.val());*/
}

/*======================================
 LISTAR TODOS LOS CLIENTES
======================================**/
function listaCliente() {
  var listaCliente = [];
  var idCliente = $("#idCliente");
  var cliente = $(".nuevoClientePedido");
  var tipoDocumento = $(".nuevaLetra");
  var documento = $(".nuevoDocumentoPedido");
  var telefono = $(".nuevoTelefonoPedido");
  var correo = $(".nuevoEmailPedido");
  var direccion = $(".nuevaDireccionPedido");

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
  /*console.log("DATOS DEL CLIENTE", listaCliente);*/
  $("#listaCliente").val(JSON.stringify(listaCliente));
}

/*=====================================
 AGRGANDO PRODUCTO A PEDIDOS
======================================**/
var numProducto = 0;
$(".formularioPedido").on("click", "button.btnProductoPedido", function () {
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
      $(".nuevoProductoPedido").append(
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
          '<input class="form-control nuevoCodigoPedido" name="nuevoCodigoPedido" codigo value readonly required>\n' +
          "</td>\n" +
          '<td style="text-align: center;width: 25%;">\n' +
          '<select class="form-control nuevaDescripcionPedido" id="producto' +
          numProducto +
          '" idProducto name="nuevaDescripcionPedido" required>\n' +
          "<option>Seleciones un producto</option>\n" +
          "</select>\n" +
          "</td>\n" +
          '<td style="text-align: center;width: 10%;">\n' +
          '<input type="text" class="form-control unidadProducto" name="unidadProducto" idUnidad readonly required>\n' +
          "</td>\n" +
          '<td style="text-align: center; width: 10%;">\n' +
          '<input type="number" class="form-control nuevaCantidadPedido" value="1" min="1" name="nuevaCantidadPedido" stock nuevoStock style="border: none;">\n' +
          "</td>\n" +
          '<td style="text-align: center; width: 10%;">\n' +
          '<input type="text" class="form-control nuevoPrecioPedido" name="nuevoPrecioPedido" style="border: none;" readonly required>\n' +
          "</td>\n" +
          '<td style="text-align: center; width: 10%;">\n' +
          '<input type="text" class="form-control nuevoImpuestoPedido" name="nuevoImpuestoPedido" impuesto style="border: none;" readonly required>\n' +
          "</td>\n" +
          '<td style="text-align: center;width: 10%;">\n' +
          '<input type="text" class="form-control nuevoTotalPedido" name="nuevoTotalPedido" precioReal style="border: none;" readonly required>\n' +
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
 SELECCIONAR PRODUCTOS PEDIDOS
=============================================*/
$(".formularioPedido").on(
  "change",
  "select.nuevaDescripcionPedido",
  function () {
    var nombreProducto = $(this).val();
    var codigoPedido = $(this)
      .parent()
      .parent()
      .children()
      .children(".nuevoCodigoPedido");
    var cantidadPedido = $(this)
      .parent()
      .parent()
      .parent()
      .parent()
      .children()
      .children()
      .children()
      .children(".nuevaCantidadPedido");
    var unidadPedido = $(this)
      .parent()
      .parent()
      .parent()
      .parent()
      .children()
      .children()
      .children()
      .children(".unidadProducto");
    var precioProducto = $(this)
      .parent()
      .parent()
      .children()
      .children(".nuevoPrecioPedido");
    var impuesto = $(this)
      .parent()
      .parent()
      .children()
      .children(".nuevoImpuestoPedido");
    var precioTotal = $(this)
      .parent()
      .parent()
      .children()
      .children(".nuevoTotalPedido");

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
        $(codigoPedido).attr("codigo", respuesta["codigo"]);
        $(codigoPedido).val(respuesta["codigo"]);
        $(cantidadPedido).attr("stock", respuesta["stock"]);
        $(cantidadPedido).attr("nuevoStock", Number(respuesta["stock"]) - 1);
        $(unidadPedido).val(respuesta["unidad"]);
        $(impuesto).attr("impuesto", respuesta["impuesto"]);
        $(impuesto).val(respuesta["impuesto"]);
        $(precioProducto).attr("precioReal", respuesta["precio_unitario"]);
        $(precioProducto).val(respuesta["precio_unitario"]);
        $(precioTotal).attr("precioReal", respuesta["precio_unitario_total"]);
        $(precioTotal).val(respuesta["precio_unitario_total"]);
        /*=============================================
             AGRUPAR PRODUCTOS EN FORMATO JSON
            =============================================*/
        listaProductos_Pedidos();
        /*=============================================
             SUMA TOTAL DE PRECIOS
            =============================================*/
        sumaPedidos();
      },
    });
  }
);

/*======================================
 QUITAR PRODUCTOS COMPUESTOS
 ======================================**/
$(".formularioPedido").on("click", "button.quitarProducto", function () {
  $(this).parent().parent().parent().parent().remove();

  if ($(".nuevoProductoPedido").children().length == 0) {
    $("#totalPedido").val(0);
    $("#totalPedido").attr("total", 0);
  } else {
    /*=============================================
         SUMA TOTAL DE PRECIOS
        =============================================*/
    sumaPedidos();
  }
});

/*=============================================
 MODIFICAR LA CANTIDAD
=============================================*/
$(".formularioPedido").on("change", "input.nuevaCantidadPedido", function () {
  var precio_Pedido = $(this)
    .parent()
    .parent()
    .children()
    .children(".nuevoTotalPedido");
  var precioFinal_Pedido =
    Number($(this).val()) * Number(precio_Pedido.attr("precioReal"));
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
    listaProductos_Pedidos();
    /*=============================================
         SUMA TOTAL DE PRECIOS
        =============================================*/
    sumaPedidos();

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
  listaProductos_Pedidos();
  /*=============================================
     SUMA TOTAL DE PRECIOS
    =============================================*/
  sumaPedidos();
});

/*=============================================
 SUMAR TODOS LOS PRECIOS EN NOTA DE ENTREGA
=============================================*/
function sumaPedidos() {
  var precioItem = $(".nuevoTotalPedido");
  var arraySumaPrecio = [];

  for (var i = 0; i < precioItem.length; i++) {
    arraySumaPrecio.push(Number($(precioItem[i]).val()));
  }

  function sumaArrayPedidos(total, numero) {
    return total + numero;
  }

  var sumaTotalPedidos = arraySumaPrecio.reduce(sumaArrayPedidos);
  $("#totalPedido").val(sumaTotalPedidos.toFixed(2));
  /*console.log(sumaTotalPedidos);*/
}

/*======================================
 LISTAR TODOS LOS PRODUCTOS
======================================**/
function listaProductos_Pedidos() {
  var listaProductos_Pedidos = [];
  var codigo = $(".nuevoCodigoPedido");
  var descripcion = $(".nuevaDescripcionPedido");
  var unidad = $(".unidadProducto");
  var cantidad = $(".nuevaCantidadPedido");
  var precioUnitario = $(".nuevoPrecioPedido");
  var impuesto = $(".nuevoImpuestoPedido");
  var total = $(".nuevoTotalPedido");

  for (var i = 0; i < descripcion.length; i++) {
    listaProductos_Pedidos.push({
      codigo: $(codigo[i]).val(),
      descripcion: $(descripcion[i]).val(),
      unidad: $(unidad[i]).val(),
      cantidad: $(cantidad[i]).val(),
      PrecioUnitario: $(precioUnitario[i]).val(),
      impuesto: $(impuesto[i]).val(),
      total: $(total[i]).val(),
    });
  }
  console.log(listaProductos_Pedidos);
  $("#listaProductosPedidos").val(JSON.stringify(listaProductos_Pedidos));
}

/*======================================
 VISUALIZAR PEDIDOS
=======================================*/
$(".btnVerPedido").click(function () {
  var idPedido = $(this).attr("idPedido");
  var datos = new FormData();
  datos.append("idPedido", idPedido);

  $.ajax({
    url: "ajax/pedidos.ajax.php",
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

      $("#verTotalPedido").val(respuesta["total_pedido"]);
    },
  });
});

/*======================================
 EDITAR PEDIDOS
=======================================*/
$(".btnEditarPedido").click(function () {
  var idPedido = $(this).attr("idPedido");
  var datos = new FormData();
  datos.append("idPedido", idPedido);

  $.ajax({
    url: "ajax/pedidos.ajax.php",
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

      $("#editarTotalPedido").val(respuesta["total_pedido"]);
    },
  });
});

/*======================================
 ELIMINAR PEDIDO
=======================================*/
$(".btnEliminarPedido").click(function () {
  var idPedido = $(this).attr("idPedido");
  swal({
    title: "¿Esta Seguro de Borrar el pedido?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar pedido",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=pedidos&idPedido=" + idPedido;
    }
  });
});
