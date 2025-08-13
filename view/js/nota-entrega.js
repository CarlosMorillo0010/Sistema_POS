/*=============================================
 VALIDAR FECHA
=============================================*/
var fechaRegistro = $("input#nuevaFechaRegistroNota");
if (fechaRegistro.length > 0) {
  fechaRegistro.datepicker({
    format: "yyyy/mm/dd",
    startDate: new Date(),
  });
}

/*=============================================
 AGREGAR PROVEEDORES DESDE EL BOTON
=============================================*/
$(".formulario__NotaEntrega").on(
  "click",
  "button.btnAgregar__Proveedor",
  function () {
    $(this).removeClass("btn-primary btnAgregar__Proveedor");
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
        $(".nuevoProveedor").append(
          "" +
            '<input type="hidden" class="form-control" id="idProveedor" name="idProveedor">\n' +
            '<div class="col-lg-4 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Proveedor:</small></label>\n' +
            '<select class="form-control proveedor__NotaEntrega" idProveedor id="proveedor__NotaEntrega" name="proveedor__NotaEntrega">\n' +
            "<option>Selecione un proeedor</option>\n" +
            "</select>\n" +
            "</div>\n" +
            '<div class="col-lg-2 input-group" style="padding-right: 10px;width: 80px;">\n' +
            '<label><small style="color: #000;">Letra:</small></label>\n' +
            '<input type="text" class="form-control tipoDocumento__NotaEntrega" id="tipoDocumento__NotaEntrega" name="tipoDocumento__NotaEntrega">\n' +
            "</div>\n" +
            '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Documento:</small></label>\n' +
            '<input type="text" class="form-control documento__NotaEntrega" id="documento__NotaEntrega" name="documento__NotaEntrega" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-4 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Tipo Proveedor:</small></label>\n' +
            '<input type="text" class="form-control tipoProveedor__NotaEntrega" id="tipoProveedor__NotaEntrega" name="tipoProveedor__NotaEntrega" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">Teléfono:</small></label>\n' +
            '<input type="text" class="form-control telefono__NotaEntrega" id="telefono__NotaEntrega" name="telefono__NotaEntrega" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
            '<label><small style="color: #000;">País:</small></label>\n' +
            '<input type="text" class="form-control pais__NotaEntrega" id="pais__NotaEntrega" name="pais__NotaEntrega" value="">\n' +
            "</div>\n" +
            '<div class="col-lg-4 input-group">\n' +
            '<label><small style="color: #000;">Dirección:</small></label>\n' +
            '<input type="text" class="form-control direccion__NotaEntrega" id="direccion__NotaEntrega" name="direccion__NotaEntrega" value="">\n' +
            "</div>"
        );
        /*=============================================
                 AGREGAR LOS CLIENTES AL SELECT
             =============================================*/
        respuesta.forEach(funcionForEach);

        function funcionForEach(item, index) {
          $(".proveedor__NotaEntrega").append(
            '<option idProveedor="' +
              item.id_proveedor +
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
 SELECCIONAR PROVEEDOR
=============================================*/
$(".formulario__NotaEntrega").on(
  "change",
  "select.proveedor__NotaEntrega",
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
        $("#idProveedor").val(respuesta["id_proveedor"]);
        $(".tipoDocumento__NotaEntrega").val(respuesta["tipo_documento"]);
        $(".documento__NotaEntrega").val(respuesta["documento"]);
        $(".tipoProveedor__NotaEntrega").val(respuesta["tipo_proveedor"]);
        $(".telefono__NotaEntrega").val(respuesta["telefono"]);
        $(".pais__NotaEntrega").val(respuesta["pais"]);
        $(".direccion__NotaEntrega").val(respuesta["direccion"]);
        /*=============================================
             AGRUPAR PROVEEDOR EN FORMATO JSON
            =============================================*/
        listaProveedor__NotaEntrega();
        /*======================================
            AGREGAR IDPROVEEDORES
            ======================================**/
        idProveedores__NotaEntrega();
      },
    });
  }
);

/*======================================
 AGREGAR IDPROVEEDORES
======================================**/
function idProveedores__NotaEntrega() {
  var idProveedor = $("#idProveedor");
  $("#proveedor_Id").val(idProveedor);
}

/*======================================
 LISTAR TODOS LOS PROVEEDORES
======================================**/
function listaProveedor__NotaEntrega() {
  var listaProveedor_NotaEntrega = [];
  var idProveedor = $("#idProveedor");
  var proveedor = $(".proveedor__NotaEntrega");
  var tipoDocumento = $(".tipoDocumento__NotaEntrega");
  var documento = $(".documento__NotaEntrega");
  var tipoProveedor = $(".tipoProveedor__NotaEntrega");
  var telefono = $(".telefono__NotaEntrega");
  var pais = $(".pais__NotaEntrega");
  var direccion = $(".direccion__NotaEntrega");

  for (var i = 0; i < proveedor.length; i++) {
    listaProveedor_NotaEntrega.push({
      id: $(idProveedor[i]).val(),
      nombre: $(proveedor[i]).val(),
      tipoDocumento: $(tipoDocumento[i]).val(),
      documento: $(documento[i]).val(),
      tipoProveedor: $(tipoProveedor[i]).val(),
      telefono: $(telefono[i]).val(),
      pais: $(pais[i]).val(),
      direccion: $(direccion[i]).val(),
    });
  }
  $("#listProveedor").val(JSON.stringify(listaProveedor_NotaEntrega));
}

/*=====================================
 AGRGANDO PRODUCTOS
======================================**/
var numUnidad = 0;
var numPrecio = 0;
var numCantidad = 0;
var numTotal = 0;
$(".btnAgregarNota").click(function () {
  numUnidad++;
  numPrecio++;
  numCantidad++;
  numTotal++;

  var datos = new FormData();
  datos.append("traerMedidas", "OK");

  $.ajax({
    url: "ajax/unidades.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $(".nuevoProductoNota").append(
        "" +
          "<tr>\n" +
          '<td style="text-align: center; padding-left: 0;width: 5%;">\n' +
          '<div class="input-group">\n' +
          '<span class="input-group-addon" style="border: none;background-color: transparent;">\n' +
          '<button type="button" class="btn btn-danger btn-xs quitarProducto">\n' +
          '<i class="fa fa-times"></i>\n' +
          "</button>\n" +
          "</span>\n" +
          "</div>\n" +
          "</td>" +
          '<td style="text-align: center;width: 10%;">\n' +
          '<input class="form-control codigoProducto" name="codigoProducto" required>\n' +
          "</td>\n" +
          '<td style="text-align: center;width: 25%;">\n' +
          '<input class="form-control descripcionProducto" name="descripcionProducto" required style="text-transform: uppercase;">\n' +
          "</td>\n" +
          '<td style="text-align: center;width: 15%;">\n' +
          '<select class="form-control unidadProducto" name="unidadProducto" id="unidad' +
          numUnidad +
          '" idUnidad required>\n' +
          "<option>Unidades</option>\n" +
          "</select>\n" +
          "</td>\n" +
          '<td style="text-align: center; width: 10%;">\n' +
          '<input type="text" class="form-control precioProducto" name="precioProducto" id="precioProducto' +
          numPrecio +
          '" precioReal required>\n' +
          "</td>\n" +
          '<td style="text-align: center; width: 10%;">\n' +
          '<input type="number" class="form-control cantidadProducto" min="1" value="0" name="cantidadProducto" id="cantidadProducto' +
          numCantidad +
          '" required>\n' +
          "</td>\n" +
          '<td style="text-align: center; width: 15%;">\n' +
          '<input type="text" class="form-control totalProducto" name="totalProducto" id="totalProducto' +
          numTotal +
          '" required readonly>\n' +
          "</td>\n" +
          "</tr>"
      );
      /*=============================================
             AGREGAR UNIDADES AL SELECT
            =============================================*/
      respuesta.forEach(funcionForEach);

      function funcionForEach(item, index) {
        if (item.stock != 0) {
          $("#unidad" + numUnidad).append(
            '<option idMedida="' +
              item.id +
              '" value="' +
              item.unidad +
              '">' +
              item.unidad +
              "</option>"
          );
        }
      }
      /*=============================================
             AGRUPAR PRODUCTOS EN FORMATO JSON
            =============================================*/
      listaProductos__NotaEntrega();
      /*======================================
             NUMEROS CON DECIMALES
            ======================================**/
      $(".precioProducto").number(true, 2, ",", ".");
      $(".totalProducto").number(true, 2, ",", ".");
    },
  });
});

/*=============================================
 SELECCIONAR UNIDADES
=============================================*/
$(".formulario__NotaEntrega").on(
  "change",
  "select.unidadProducto",
  function () {
    var nombreUnidad = $(this).val();
    var unidad = $(this)
      .parent()
      .parent()
      .children()
      .children(".unidadProducto");

    var datos = new FormData();
    datos.append("nombreUnidad", nombreUnidad);
    $.ajax({
      url: "ajax/unidades.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
        $(unidad).val(respuesta["unidad"]);
      },
    });
  }
);

/*======================================
 QUITAR PRODUCTOS
 ======================================**/
$(".formulario__NotaEntrega").on("click", "button.quitarProducto", function () {
  $(this).parent().parent().parent().parent().remove();
});

/*=============================================
 MODIFICAR CANTIDAD
=============================================*/
$(".formulario__NotaEntrega").on(
  "change",
  "input.cantidadProducto",
  function () {
    let precio = $("#precioProducto" + numPrecio).val();
    let total = Number($(this).val() * precio);
    $("#totalProducto" + numUnidad).val(total);
    /*=============================================
     SUMA TOTAL DE PRECIOS
    =============================================*/
    arraySumaNotaEntregaCompra();
  }
);

/*=============================================
 SUMA TODOS LOS PRECIOS
=============================================*/
function arraySumaNotaEntregaCompra() {
  var precioItem = $(".totalProducto");
  var arraySumaNotaEntrega = [];

  for (let i = 0; i < precioItem.length; i++) {
    arraySumaNotaEntrega.push(Number($(precioItem[i]).val()));
  }

  function sumaArrayNotaEntrega(total, numero) {
    return total + numero;
  }

  var sumaTotalNotaEntrega = arraySumaNotaEntrega.reduce(sumaArrayNotaEntrega);
  $("#subTotalNotaEntregaCompra").val(sumaTotalNotaEntrega);
}

/*======================================
 FORMATEAR PRECIOS
 ======================================**/
$("#subTotalNotaEntregaCompra").number(true, 2, ",", ".");

/*=============================================
 MODIFICAR LA CANTIDAD
=============================================*/
$(".formulario__NotaEntrega").on("change", function () {
  /*=============================================
     AGRUPAR PRODUCTOS EN FORMATO JSON
    =============================================*/
  listaProductos__NotaEntrega();
});

/*======================================
 LISTAR TODOS LOS PRODUCTOS
======================================**/
function listaProductos__NotaEntrega() {
  var listaProductos_NotaEntrega = [];
  var codigo = $(".codigoProducto");
  var descripcion = $(".descripcionProducto");
  var unidad = $(".unidadProducto");
  var precioUnitario = $(".precioProducto");
  var cantidad = $(".cantidadProducto");
  var total = $(".totalProducto");

  for (var i = 0; i < descripcion.length; i++) {
    listaProductos_NotaEntrega.push({
      codigo: $(codigo[i]).val(),
      descripcion: $(descripcion[i]).val(),
      unidad: $(unidad[i]).val(),
      precioUnitario: $(precioUnitario[i]).val(),
      cantidad: $(cantidad[i]).val(),
      total: $(total[i]).val(),
    });
  }
  $("#listProductos").val(JSON.stringify(listaProductos_NotaEntrega));
}

/*======================================
 VISUALIZAR NOTA DE ENTREGA COMPRA
=======================================*/
$(".btnVerNotaEntrega").click(function () {
  var idNotaCompra = $(this).attr("idNotaCompra");
  var datos = new FormData();
  datos.append("idNotaCompra", idNotaCompra);

  $.ajax({
    url: "ajax/nota-entrega.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      var proveedor = JSON.parse(respuesta["proveedor"]);
      var productos = JSON.parse(respuesta["productos"]);

      $("#verNumeroRecepcion").val(
        respuesta["numero_recepcion"].padStart(10, 0)
      );
      $("#verNumeroNota").val(respuesta["numero_nota"].padStart(10, 0));
      $("#verFechaNotaEntrega").val(respuesta["fecha_nota"]);
      $("#verFechaRegistroNota").val(respuesta["feregistro_nota"]);

      /*======================================
             LISTA DE PROVEEDORES
            =======================================*/
      $("#verProveedor").val(proveedor[0].nombre);
      $("#verTipoDocumento").val(proveedor[0].tipoDocumento);
      $("#verDocumento").val(proveedor[0].documento);
      $("#verTipoProveedor").val(proveedor[0].tipoProveedor);
      $("#verTelefonoProveedor").val(proveedor[0].telefono);
      $("#verPaisProveedor").val(proveedor[0].pais);
      $("#verDireccionProveedor").val(proveedor[0].direccion);
      /*======================================
             LISTA DE PRODUCTOS
            =======================================*/
      $("#verCodigo").val(productos[0].codigo);
      $("#verNombre").val(productos[0].descripcion);
      $("#verUnidadMedida").html(productos[0].unidad);
      $("#verUnidadMedida").val(productos[0].unidad);
      $("#verPrecioUnitario").val(productos[0].precioUnitario);
      $("#verCantidad").val(productos[0].cantidad);
      $("#verTotal").val(productos[0].total);

      $("#verTotal_NotaEntregaCompra").val(
        respuesta["total_nota_entrega_compra"]
      );
    },
  });
});

/*======================================
 EDITAR NOTA DE ENTREGA COMPRA
=======================================*/
$(".btnEditarNotaEntrega").click(function () {
  var idNotaCompra = $(this).attr("idNotaCompra");
  var datos = new FormData();
  datos.append("idNotaCompra", idNotaCompra);

  $.ajax({
    url: "ajax/nota-entrega.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      var proveedor = JSON.parse(respuesta["proveedor"]);
      var productos = JSON.parse(respuesta["productos"]);

      $("#editarNumeroRecepcion").val(
        respuesta["numero_recepcion"].padStart(10, 0)
      );
      $("#editarNumeroNota").val(respuesta["numero_nota"].padStart(10, 0));
      $("#editarFechaNotaEntrega").val(respuesta["fecha_nota"]);
      $("#editarFechaRegistroNota").val(respuesta["feregistro_nota"]);

      /*======================================
             LISTA DE PROVEEDORES
            =======================================*/
      $("#editarProveedor").val(proveedor[0].nombre);
      $("#editarTipoDocumento").val(proveedor[0].tipoDocumento);
      $("#editarDocumento").val(proveedor[0].documento);
      $("#editarTipoProveedor").val(proveedor[0].tipoProveedor);
      $("#editarTelefonoProveedor").val(proveedor[0].telefono);
      $("#editarPaisProveedor").val(proveedor[0].pais);
      $("#editarDireccionProveedor").val(proveedor[0].direccion);
      /*======================================
             LISTA DE PRODUCTOS
            =======================================*/
      $("#editarCodigo").val(productos[0].codigo);
      $("#editarNombre").val(productos[0].descripcion);
      $("#editarUnidadMedida").html(productos[0].unidad);
      $("#editarUnidadMedida").val(productos[0].unidad);
      $("#editarPrecioUnitario").val(productos[0].precioUnitario);
      $("#editarCantidad").val(productos[0].cantidad);
      $("#editarTotal").val(productos[0].total);

      $("#editarTotal_NotaEntregaCompra").val(
        respuesta["total_nota_entrega_compra"]
      );
    },
  });
});

/*======================================
 ELIMINAR NOTA DE ENTREGA COMPRA
=======================================*/
$(".btnEliminarNotaEntrega").click(function () {
  var idNotaCompra = $(this).attr("idNotaCompra");
  swal({
    title: "¿Esta Seguro de Borrar la Nota de Entrega?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borras orden de compra",
  }).then((result) => {
    if (result.value) {
      window.location =
        "index.php?ruta=notas-entrega&idNotaCompra=" + idNotaCompra;
    }
  });
});
