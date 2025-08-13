$(".tablaCompuestos").DataTable({
  ajax: "ajax/datatable-compuestos.ajax.php",
  deferRender: true,
  retrieve: true,
  processing: true,
  order: [[0, "desc"]],
  language: {
    sProcessing: "Procesando...",
    sLengthMenu: "Mostrar _MENU_ registros",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
    sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
    sInfoPostFix: "",
    sSearch: "Buscar:",
    sUrl: "",
    sInfoThousands: ",",
    sLoadingRecords: "Cargando...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "Siguiente",
      sPrevious: "Anterior",
    },
    oAria: {
      sSortAscending: ": Activar para ordenar la columna de manera ascendente",
      sSortDescending:
        ": Activar para ordenar la columna de manera descendente",
    },
  },
});

/*=====================================
 CAPTURANDO LA CATEGORIA PARA ASIGNAR UN CODIGO
======================================**/
$("#nuevaCategoria").change(function () {
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
    },
  });
});

/*=====================================
 PRECIO UNITARIO
======================================**/
$("#nuevoPrecioUnitario, #editarPrecioUnitario").keyup(function () {
  if ($(".porcentaje").prop("checked")) {
    var valorPorcentaje = $(".nuevoPorcentaje").val();
    var porcentaje =
      Number(($("#nuevoPrecioUnitario").val() * valorPorcentaje) / 100) +
      Number($("#nuevoPrecioUnitario").val());
    var editarPorcentaje =
      Number(($("#editarPrecioUnitario").val() * valorPorcentaje) / 100) +
      Number($("#editarPrecioUnitario").val());

    $("#nuevoPrecioVenta").val(porcentaje);
    $("#nuevoPrecioVenta").prop("readonly", true);

    $("#editarPrecioVenta").val(editarPorcentaje);
    $("#editarPrecioVenta").prop("readonly", true);
  }
});

/*=====================================
 CAMBIO DE PORCENTAJE UNITARIO
======================================**/
$(".nuevoPorcentaje").keyup(function () {
  if ($(".porcentaje").prop("checked")) {
    var valorPorcentaje = $(this).val();
    var porcentaje =
      Number(($("#nuevoPrecioUnitario").val() * valorPorcentaje) / 100) +
      Number($("#nuevoPrecioUnitario").val());
    var editarPorcentaje =
      Number(($("#editarPrecioUnitario").val() * valorPorcentaje) / 100) +
      Number($("#editarPrecioUnitario").val());

    $("#nuevoPrecioVenta").val(porcentaje);
    $("#nuevoPrecioVenta").prop("readonly", true);

    $("#editarPrecioVenta").val(editarPorcentaje);
    $("#editarPrecioVenta").prop("readonly", true);
  }
});

$(".porcentaje").on("ifUnchecked", function () {
  $("#nuevoPrecioVenta").prop("readonly", false);
  $("#editarPrecioVenta").prop("readonly", false);
});

$(".porcentaje").on("ifChecked", function () {
  $("#nuevoPrecioVenta").prop("readonly", true);
  $("#editarPrecioVenta").prop("readonly", true);
});

/*=====================================
 PRECIO OFERTA
======================================**/
$("#nuevoPrecioOferta, #editarPrecioOferta").keyup(function () {
  if ($(".porcentajeOferta").prop("checked")) {
    var valorPorcentaje = $(".nuevoPorcentajeOferta").val();
    var porcentajeOferta =
      Number(($("#nuevoPrecioOferta").val() * valorPorcentaje) / 100) +
      Number($("#nuevoPrecioOferta").val());
    var editarPorcentaje =
      Number(($("#editarPrecioOferta").val() * valorPorcentaje) / 100) +
      Number($("#editarPrecioOferta").val());

    $("#nuevoPrecioVentaOferta").val(porcentajeOferta);
    $("#nuevoPrecioVentaOferta").prop("readonly", true);

    $("#editarPrecioVentaOferta").val(editarPorcentaje);
    $("#editarPrecioVentaOferta").prop("readonly", true);
  }
});

/*=====================================
 CAMBIO DE PORCENTAJE OFERTA
======================================**/
$(".nuevoPorcentajeOferta").keyup(function () {
  if ($(".porcentajeOferta").prop("checked")) {
    var valorPorcentaje = $(this).val();
    var porcentajeOferta =
      Number(($("#nuevoPrecioOferta").val() * valorPorcentaje) / 100) +
      Number($("#nuevoPrecioOferta").val());
    var editarPorcentajeOferta =
      Number(($("#editarPrecioOferta").val() * valorPorcentaje) / 100) +
      Number($("#editarPrecioOferta").val());

    $("#nuevoPrecioVentaOferta").val(porcentajeOferta);
    $("#nuevoPrecioVentaOferta").prop("readonly", true);

    $("#editarPrecioVentaOferta").val(editarPorcentajeOferta);
    $("#editarPrecioVentaOferta").prop("readonly", true);
  }
});

$(".porcentajeOferta").on("ifUnchecked", function () {
  $("#nuevoPrecioVentaOferta").prop("readonly", false);
  $("#editarPrecioVentaOferta").prop("readonly", false);
});

$(".porcentajeOferta").on("ifChecked", function () {
  $("#nuevoPrecioVentaOferta").prop("readonly", true);
  $("#editarPrecioVentaOferta").prop("readonly", true);
});

/*=====================================
 PRECIO MAYORISTA
======================================**/
/*$("#nuevoPrecioMayor, #editarPrecioMayor").keyup(function () {
    if ($(".porcentajeMayor").prop("checked")) {
        var valorPorcentaje = $(".nuevoPorcentajeMayor").val();
        var porcentajeMayor = Number(($("#nuevoPrecioMayor").val() * valorPorcentaje / 100)) + Number($("#nuevoPrecioMayor").val());
        var editarPorcentajeMayor = Number(($("#editarPrecioMayor").val() * valorPorcentaje / 100)) + Number($("#editarPrecioMayor").val());

        $("#nuevoPrecioVentaMayor").val(porcentajeMayor);
        $("#nuevoPrecioVentaMayor").prop("readonly", true);

        $("#editarPrecioVentaMayor").val(editarPorcentajeMayor);
        $("#editarPrecioVentaMayor").prop("readonly", true);
    }
})*/

/*=====================================
 CAMBIO DE PORCENTAJE MAYORISTA
======================================**/
/*$(".nuevoPorcentajeMayor").keyup(function () {
    if ($(".porcentajeMayor").prop("checked")) {
        var valorPorcentaje = $(this).val();
        var porcentajeMayor = Number(($("#nuevoPrecioMayor").val() * valorPorcentaje / 100)) + Number($("#nuevoPrecioMayor").val());
        var editarPorcentajeMayor = Number(($("#editarPrecioMayor").val() * valorPorcentaje / 100)) + Number($("#editarPrecioMayor").val());

        $("#nuevoPrecioVentaMayor").val(porcentajeMayor);
        $("#nuevoPrecioVentaMayor").prop("readonly", true);

        $("#editarPrecioVentaMayor").val(editarPorcentajeMayor);
        $("#editarPrecioVentaMayor").prop("readonly", true);
    }
})

$(".porcentajeMayor").on("ifUnchecked", function () {
    $("#nuevoPrecioVentaMayor").prop("readonly", false);
    $("#editarPrecioVentaMayor").prop("readonly", false);
})

$(".porcentajeMayor").on("ifChecked", function () {
    $("#nuevoPrecioVentaMayor").prop("readonly", true);
    $("#editarPrecioVentaMayor").prop("readonly", true);
})*/

/*=============================================
 SUBIENDO LA FOTO DEL PRODUCTO
=============================================*/
$(".imagenCompuesto").change(function () {
  var imagen = this.files[0];

  /*=============================================
      VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
      =============================================*/
  if (imagen["type"] != "image/jpeg" && imagen["type"] != "image/png") {
    $(".imagenProducto").val("");
    swal({
      title: "Error al subir la imagen",
      text: "¡La imagen debe estar en formato JPG o PNG!",
      type: "error",
      confirmButtonText: "¡Cerrar!",
    });
  } else if (imagen["size"] > 2000000) {
    $(".imagenCompuesto").val("");
    swal({
      title: "Error al subir la imagen",
      text: "¡La imagen no debe pesar más de 2MB!",
      type: "error",
      confirmButtonText: "¡Cerrar!",
    });
  } else {
    var datosImagen = new FileReader();
    datosImagen.readAsDataURL(imagen);
    $(datosImagen).on("load", function (event) {
      var rutaImagen = event.target.result;
      $(".previsualizar").attr("src", rutaImagen);
    });
  }
});

/*=====================================
 AGRGANDO PRODUCTO PARA COMPUESTOS
======================================**/
var numProducto = 0;
$(".btnAgregarCompuesto").click(function () {
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
      $(".nuevoProductoCompuesto").append(
        "" +
          "<tr>\n" +
          '<td style="text-align: center; padding-left: 0;width: 15%;">\n' +
          '<div class="input-group">\n' +
          '<span class="input-group-addon" style="border: none;background-color: transparent;">\n' +
          '<button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto>\n' +
          '<i class="fa fa-times"></i>\n' +
          "</button>\n" +
          "</span>\n" +
          "</div>\n" +
          "</td>" +
          '<td style="text-align: center;width: 70%;">\n' +
          '<select class="form-control productoCompuesto" id="producto' +
          numProducto +
          '" name="productoCompuesto" idProducto required>\n' +
          "<option>Seleciones un producto</option>\n" +
          "</select>\n" +
          "</td>\n" +
          '<td style="text-align: center; padding-left: 0;width: 15%;">\n' +
          '<input type="number" class="form-control cantidadProductoCompuesto" id="cantidadProductoCompuesto" name="cantidadProductoCompuesto" min="1" value="1" stock nuevoStock style="border: none;">\n' +
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
 SELECCIONAR PRODUCTOS
=============================================*/
$(".formularioCompuestos").on(
  "change",
  "select.productoCompuesto",
  function () {
    var nombreProducto = $(this).val();
    var cantidadCompuesto = $(this)
      .parent()
      .parent()
      .children()
      .children("#cantidadProductoCompuesto");

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
      },
    });
  }
);

/*=============================================
 MODIFICAR LA CANTIDAD
=============================================*/
$(".formularioCompuestos").on(
  "change",
  "input.cantidadProductoCompuesto",
  function () {
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
        confirmButtonText: "¡Cerrar!",
      });
      return;
    }
    /*=============================================
     AGRUPAR PRODUCTOS EN FORMATO JSON
    =============================================*/
    listaTerminados();
  }
);

/*======================================
 QUITAR PRODUCTOS COMPUESTOS
 ======================================**/
$(".formularioCompuestos").on("click", "button.quitarProducto", function () {
  $(this).parent().parent().parent().parent().remove();
  if ($(".productoCompuesto").children().length == 0) {
  } else {
    /*=============================================
         AGRUPAR PRODUCTOS EN FORMATO JSON
        =============================================*/
    listaProductos();
  }
});

/*======================================
 LISTAR TODOS LOS PRODUCTOS TERMINADOS
======================================**/
function listaTerminados() {
  var listaTerminados = [];

  var descripcion = $(".productoCompuesto");
  var cantidad = $(".cantidadProductoCompuesto");

  for (var i = 0; i < descripcion.length; i++) {
    listaTerminados.push({
      descripcion: $(descripcion[i]).val(),
      cantidad: $(cantidad[i]).val(),
      stock: $(cantidad[i]).attr("nuevoStock"),
    });
  }
  $("#listaCompuestos").val(JSON.stringify(listaTerminados));
  console.log("listaTerminados", listaTerminados);
}

/*=============================================
 EDITAR PRODUCTO
=============================================*/
$(".tablaCompuestos tbody").on(
  "click",
  "button.btnEditarCompuesto",
  function () {
    var idCompuesto = $(this).attr("idCompuesto");
    var datos = new FormData();
    datos.append("idCompuesto", idCompuesto);
    $.ajax({
      url: "ajax/compuestos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
        var datosCategoria = new FormData();
        datosCategoria.append("idCategoria", respuesta["id"]);
        $.ajax({
          url: "ajax/categories.ajax.php",
          method: "POST",
          data: datosCategoria,
          cache: false,
          contentType: false,
          processData: false,
          dataType: "json",
          success: function (respuesta) {
            $("#editarCategoria").val(respuesta["id_categoria"]);
            $("#editarCategoria").html(respuesta["categoria"]);
          },
        });
        $("#editarCodigo").val(respuesta["codigo"]);
        $("#editarServicio").val(respuesta["servicio"]);
        $("#editarServicio").html(respuesta["servicio"]);
        $("#editarDescripcion").val(respuesta["descripcion"]);
        $("#editarStock").val(respuesta["stock"]);
        $("#editarUnidad").val(respuesta["unidad"]);
        $("#editarUnidad").html(respuesta["unidad"]);
        $("#editarPrecioUnitario").val(respuesta["precio_unitario"]);
        $("#editarPorcentaje").val(respuesta["impuesto"]);
        $("#editarPrecioVenta").val(respuesta["precio_unitario_total"]);
        $("#editarPrecioOferta").val(respuesta["precio_oferta"]);
        $("#editarPrecioVentaOferta").val(respuesta["precio_oferta_total"]);

        $("#editarCodigoBarra").val(respuesta["codigo_barras"]);
        if (respuesta["imagen"] != "") {
          $("#imagenActual").val(respuesta["imagen"]);
          $(".previsualizar").attr("src", respuesta["imagen"]);
        }
      },
    });
  }
);

/*=============================================
 ELIMINAR PRODUCTO
=============================================*/
$(".tablaCompuestos tbody").on(
  "click",
  "button.btnEliminarCompuesto",
  function () {
    var idCompuesto = $(this).attr("idCompuesto");
    var codigo = $(this).attr("codigo");
    var imagen = $(this).attr("imagen");

    swal({
      title: "¿Está seguro de borrar el producto compuesto?",
      text: "¡Si no lo está puede cancelar la accíón!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      cancelButtonText: "Cancelar",
      confirmButtonText: "Si, borrar producto compuesto!",
    }).then(function (result) {
      if (result.value) {
        window.location =
          "index.php?ruta=producto-compuesto&idCompuesto=" +
          idCompuesto +
          "&imagen=" +
          imagen +
          "&codigo=" +
          codigo;
      }
    });
  }
);
