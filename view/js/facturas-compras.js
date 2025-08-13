/*=============================================
 VALIDAR FECHA
=============================================*/
var fechaVencimiento = $("input#fechaVencimiento");
if (fechaVencimiento.length > 0) {
  fechaVencimiento.datepicker({
    format: "yyyy/mm/dd",
    startDate: new Date(),
  });
}

/*=============================================
 AGREGAR PROVEEDORES DESDE EL BOTON
=============================================*/
$(".formulario__FacturaCompra").on("click", "button.btnFCompra", function () {
  $(this).removeClass("btn-primary btnFCompra");
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
      $(".FacturaCompra").append(
        "" +
          '<input type="hidden" class="form-control" id="idProveedor" name="idProveedor">\n' +
          '<div class="col-lg-3 input-group" style="padding-right: 10px;">\n' +
          '<label><small style="color: #000;">Proveedor:</small></label>\n' +
          '<select class="form-control proveedorFCompra" idProveedor id="proveedorFCompra" name="proveedorFCompra">\n' +
          "<option>Selecione un proeedor</option>\n" +
          "</select>\n" +
          "</div>\n" +
          '<div class="col-lg-2 input-group" style="padding-right: 10px;width: 80px;">\n' +
          '<label><small style="color: #000;">Letra:</small></label>\n' +
          '<input type="text" class="form-control tipoDocumentoFC" id="tipoDocumentoFC" name="tipoDocumentoFC" value="">\n' +
          "</div>\n" +
          '<div class="col-lg-2 input-group" style="padding-right: 10px;">\n' +
          '<label><small style="color: #000;">Documento:</small></label>\n' +
          '<input type="text" class="form-control documentoFC" id="documentoFC" name="documentoFC" value="">\n' +
          "</div>\n" +
          '<div class="input-group" style="padding-right: 10px;">\n' +
          '<label><small style="color: #000;">Tipo Proveedor:</small></label>\n' +
          '<input type="text" class="form-control tipoProveedorFC" id="tipoProveedorFC" name="tipoProveedorFC" value="">\n' +
          "</div>\n" +
          '<div class="input-group" style="padding-right: 10px;">\n' +
          '<label><small style="color: #000;">Telefono:</small></label>\n' +
          '<input type="text" class="form-control telefonoFC" id="telefonoFC" name="telefonoFC" value="">\n' +
          "</div>\n" +
          '<div class="col-lg-2 input-group" style="padding-right: 10px;">\n' +
          '<label><small style="color: #000;">Pais:</small></label>\n' +
          '<input type="text" class="form-control paisFC" id="paisFC" name="paisFC" value="">\n' +
          "</div>\n" +
          '<div class="col-lg-5 input-group">\n' +
          '<label><small style="color: #000;">Direccion:</small></label>\n' +
          '<input type="text" class="form-control direccionFC" id="direccionFC" name="direccionFC" value="">\n' +
          "</div>"
      );
      /*=============================================
                 AGREGAR LOS CLIENTES AL SELECT
             =============================================*/
      respuesta.forEach(funcionForEach);

      function funcionForEach(item, index) {
        $(".proveedorFCompra").append(
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
});

/*=============================================
 SELECCIONAR PROVEEDOR
=============================================*/
$(".formulario__FacturaCompra").on(
  "change",
  "select.proveedorFCompra",
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
        $(".tipoDocumentoFC").val(respuesta["tipo_documento"]);
        $(".documentoFC").val(respuesta["documento"]);
        $(".tipoProveedorFC").val(respuesta["tipo_proveedor"]);
        $(".telefonoFC").val(respuesta["telefono"]);
        $(".paisFC").val(respuesta["pais"]);
        $(".direccionFC").val(respuesta["direccion"]);
        /*=============================================
             AGRUPAR PROVEEDOR EN FORMATO JSON
            =============================================*/
        listaProveedor__FacturaCompras();
        /*======================================
            AGREGAR IDPROVEEDORES
            ======================================**/
        idProveedores__FacturaCompra();
      },
    });
  }
);

/*======================================
 AGREGAR IDPROVEEDORES
======================================**/
function idProveedores__FacturaCompra() {
  var idProveedor = $("#idProveedor");
  $("#proveedor_IDFC").val(idProveedor);
}

/*======================================
 LISTAR TODOS LOS PROVEEDORES
======================================**/
function listaProveedor__FacturaCompras() {
  var listaProveedor_FacturaCompra = [];
  var idProveedor = $("#idProveedor");
  var proveedor = $(".proveedorFCompra");
  var tipoDocumento = $(".tipoDocumentoFC");
  var documento = $(".documentoFC");
  var tipoProveedor = $(".tipoProveedorFC");
  var telefono = $(".telefonoFC");
  var pais = $(".paisFC");
  var direccion = $(".direccionFC");

  for (var i = 0; i < proveedor.length; i++) {
    listaProveedor_FacturaCompra.push({
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
  $("#proveedorFC").val(JSON.stringify(listaProveedor_FacturaCompra));
}

/*=====================================
 AGRGANDO PRODUCTO FACTURA COMPRA
======================================**/
var numUnidad = 0;
var numPrecio = 0;
var numImpuesto = 0;
var numCantidad = 0;
var numTotal = 0;
$(".formulario__FacturaCompra").on(
  "click",
  "button.btnProductoFC",
  function () {
    numUnidad++;
    numPrecio++;
    numImpuesto++;
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
        $(".nuevaFacturaCompra").append(
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
            '<td style="text-align: center;width: 10%;">\n' +
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
            '<input type="text" class="form-control impuestoProducto" name="impuestoProducto" id="impuestoProducto' +
            numImpuesto +
            '" required>\n' +
            "</td>\n" +
            '<td style="text-align: center; width: 10%;">\n' +
            '<input type="number" class="form-control cantidadProducto" min="1" value="0" name="cantidadProducto" id="cantidadProducto' +
            numCantidad +
            '" required>\n' +
            "</td>\n" +
            '<td style="text-align: center; width: 10%;">\n' +
            '<input type="text" class="form-control totalProducto" name="totalProducto" id="totalProducto' +
            numTotal +
            '" required readonly>\n' +
            "</td>\n" +
            "</tr>"
        );
        /*=============================================
             AGREGAR LOS ARTICULOS AL SELECT
            =============================================*/
        respuesta.forEach(funcionForEach);

        function funcionForEach(item, index) {
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
        /*=============================================
             AGRUPAR PRODUCTOS EN FORMATO JSON
            =============================================*/
        listaProductos__FacturaCompra();
        /*======================================
             NUMEROS CON DECIMALES
            ======================================**/
        $(".precioProducto").number(true, 2, ",", ".");
        $(".impuestoProducto").number(true, 2, ",", ".");
        $(".totalProducto").number(true, 2, ",", ".");
      },
    });
  }
);

/*======================================
 QUITAR PRODUCTOS
======================================**/
$(".formulario__FacturaCompra").on(
  "click",
  "button.quitarProducto",
  function () {
    $(this).parent().parent().parent().parent().remove();
    if ($(".nuevaFacturaCompra").children().length == 0) {
      $("#total__FacturaCompra").val(0);
    } else {
      /*=============================================
         SUMA TOTAL DE PRECIOS
        =============================================*/
      sumaTotal__FacturaGastos();
    }
  }
);

/*=============================================
 MODIFICAR LA CANTIDAD
=============================================*/
$(".formulario__FacturaCompra").on(
  "change",
  "input.cantidadProducto",
  function () {
    let precio = $("#precioProducto" + numPrecio).val();
    let cantidad = Number($(this).val() * precio);
    let impuesto = $("#impuestoProducto" + numImpuesto).val();
    let total = Number(cantidad) + Number((precio * impuesto) / 100);
    $("#totalProducto" + numUnidad).val(total);
    /*=============================================
     SUMAR PRECIOS
    =============================================*/
    sumaFacturaCompra();
    /*=============================================
     AGRUPAR PRODUCTOS EN FORMATO JSON
    =============================================*/
    listaProductos__FacturaCompra();
  }
);

/*=============================================
 SUMAR TODOS LOS PRECIOS FACTURA COMPRA
=============================================*/
function sumaFacturaCompra() {
  let precioItem = $(".totalProducto");
  let arraySumaFacturaCompra = [];

  for (let i = 0; i < precioItem.length; i++) {
    arraySumaFacturaCompra.push(Number($(precioItem[i]).val()));
  }

  function sumaArrayFacturaCompra(total, numero) {
    return total + numero;
  }

  let sumaTotalPrecio = arraySumaFacturaCompra.reduce(sumaArrayFacturaCompra);
  $("#subTotalFacturaCompra").val(sumaTotalPrecio);
}

/*======================================
 FORMATEAR PRECIOS
 ======================================**/
$("#subTotalFacturaCompra").number(true, 2, ",", ".");

/*======================================
 LISTAR TODOS LOS PRODUCTOS
======================================**/
function listaProductos__FacturaCompra() {
  var listaProductos_FacturaCompra = [];
  var codigo = $(".codigoProducto");
  var descripcion = $(".descripcionProducto");
  var unidad = $(".unidadProducto");
  var precioUnitario = $(".precioProducto");
  var cantidad = $(".cantidadProducto");
  var total = $(".totalProducto");

  for (var i = 0; i < descripcion.length; i++) {
    listaProductos_FacturaCompra.push({
      codigo: $(codigo[i]).val(),
      descripcion: $(descripcion[i]).val(),
      unidad: $(unidad[i]).val(),
      precioUnitario: $(precioUnitario[i]).val(),
      cantidad: $(cantidad[i]).val(),
      total: $(total[i]).val(),
    });
  }
  $("#productosFC").val(JSON.stringify(listaProductos_FacturaCompra));
}

/*======================================
 VISUALIZAR FACTURA DE COMPRA
=======================================*/
$(".btnVerFacturaCompra").click(function () {
  var idFacturaCompra = $(this).attr("idFacturaCompra");
  var datos = new FormData();
  datos.append("idFacturaCompra", idFacturaCompra);

  $.ajax({
    url: "ajax/factura-compra.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      var proveedor = JSON.parse(respuesta["proveedor"]);
      var productos = JSON.parse(respuesta["productos"]);
      /*======================================
             LISTA DE PROVEEDORES
            =======================================*/
      $("#verCodigo").html(respuesta["codigo"].padStart(10, 0));
      $("#verCodigo").val(respuesta["codigo"].padStart(10, 0));
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
      $("#verCodigoProducto").val(productos[0].codigo);
      $("#verDescripcionProducto").val(productos[0].descripcion);
      $("#verUnidadProducto").html(productos[0].unidad);
      $("#verUnidadProducto").val(productos[0].unidad);
      $("#verCantidadProducto").val(productos[0].cantidad);
    },
  });
});

/*======================================
 EDITAR FACTURA DE COMPRA
=======================================*/
$(".btnEditarFacturaCompra").click(function () {
  var idFacturaCompra = $(this).attr("idFacturaCompra");
  var datos = new FormData();
  datos.append("idFacturaCompra", idFacturaCompra);

  $.ajax({
    url: "ajax/factura-compra.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      var proveedor = JSON.parse(respuesta["proveedor"]);
      var productos = JSON.parse(respuesta["productos"]);
      /*======================================
             LISTA DE PROVEEDORES
            =======================================*/
      $("#editarCodigo").html(respuesta["codigo"].padStart(10, 0));
      $("#editarCodigo").val(respuesta["codigo"].padStart(10, 0));
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
      $("#editarCodigoProducto").val(productos[0].codigo);
      $("#editarDescripcionProducto").val(productos[0].descripcion);
      $("#editarUnidadProducto").html(productos[0].unidad);
      $("#editarUnidadProducto").val(productos[0].unidad);
      $("#editarCantidadProducto").val(productos[0].cantidad);
    },
  });
});

/*======================================
 ELIMINAR FACTURA DE COMPRA
=======================================*/
$(".btnEliminarFacturaCompra").click(function () {
  var idFacturaCompra = $(this).attr("idFacturaCompra");
  swal({
    title: "¿Esta Seguro de Borrar la Factura de Compra?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borras factura de compra",
  }).then((result) => {
    if (result.value) {
      window.location =
        "index.php?ruta=facturas-compra&idFacturaCompra=" + idFacturaCompra;
    }
  });
});
