/*=============================================
 AGREGAR PROVEEDORES DESDE EL BOTON
=============================================*/
$(".formulario__OrdenCompra").on("click", "button.btnBuscarProveedores", function () {
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
      $(".agregarProveedores").append(
        "" +
          '<input type="hidden" class="form-control" id="idProveedor" name="idProveedor">\n'
      );
    },
  });
});

/*=============================================
 SELECCIONAR PROVEEDOR
=============================================*/
$(".btnTraerProveedor").click(function () {
  var idProveedor = $(this).attr("idProveedor");
  var datos = new FormData();
  datos.append("idProveedor", idProveedor);

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
      $(".nuevoTipoDocumento_OrdenCompra").val(respuesta["tipo_documento"]);
      $(".nuevoDocumento_OrdenCompra").val(respuesta["documento"]);
      $(".nuevo-ProveedorOrdenCompra").val(respuesta["nombre"]);
      /*=============================================
        AGRUPAR PROVEEDOR EN FORMATO JSON
      =============================================*/
      listaProveedor();
      /*======================================
      AGREGAR IDPROVEEDORES
      ======================================**/
      idProveedores();
      /*======================================
      CERRAR MODAL PROVEEDORES
      ======================================**/
      cerrar();
    },
  });

});

/*======================================
 QUITAR PRODUCTOS
======================================**/
$(".formulario__OrdenCompra").on("click", "button.quitarProducto", function () {
  $(this).parent().parent().parent().parent().remove();
  /*=============================================
     AGRUPAR PRODUCTOS EN FORMATO JSON
    =============================================*/
  listaProductos_OrdenCompra();
});

/*=====================================
 AGREGANDO PRODUCTO ORDEN DE COMPRA
======================================**/
var numProducto = 0;
$(".formulario__OrdenCompra").on(
  "click",
  "button.btnAgregarOrdenCompra",
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
        $(".nuevaOrdenCompra").append(
          "" +
            "<tr>\n" +
            "</td>\n" +
            '<td style="text-align: center; padding-left: 0;width: 5%;">\n' +
            '<div class="input-group">\n' +
            '<span class="input-group-addon" style="border: none;background-color: transparent;">\n' +
            '<button type="button" class="btn btn-primary btn-xs agregarProducto" data-toggle="modal" data-target="#modalAgregarProducto">\n' +
            '<i class="fa fa-plus"></i>\n' +
            "</button>\n" +
            "</span>\n" +
            "</div>\n" +
            "</td>\n" +
            '<td style="text-align: center; padding-left: 0;width: 5%;">\n' +
            '<div class="input-group">\n' +
            '<span class="input-group-addon" style="border: none;background-color: transparent;">\n' +
            '<button type="button" class="btn btn-danger btn-xs quitarProducto">\n' +
            '<i class="fa fa-times"></i>\n' +
            "</button>\n" +
            "</span>\n" +
            "</div>\n" +
            "</td>" +
            '<td style="text-align: center;width: 15%;">\n' +
            '<input class="form-control codigoProducto" name="codigoProducto" id="codigo' + numProducto + '" required readonly>\n' +
            "</td>\n" +
            '<td style="text-align: center;width: 40%;">\n' +
            '<input class="form-control descripcionProducto" name="descripcionProducto" id="producto' + numProducto + '" idProducto required readonly style="text-transform: uppercase;">\n' +
            "</td>\n" +
            '<td style="text-align: center;width: 20%;">\n' +
            '<input class="form-control unidadMedida" name="unidadMedida" id="unidad' + numProducto + '" required readonly style="text-transform: uppercase;">\n' +
            "</td>\n" +
            '<td style="text-align: center; padding-left: 0;width: 10%;">\n' +
            '<input type="text" class="form-control cantidadProducto" value="1" min="1" name="cantidadProducto" required style="border: none;">\n' +
            "</td>\n" +
            "</tr>"
        );
        /*=============================================
          AGRUPAR PRODUCTOS EN FORMATO JSON
        =============================================*/
        listaProductos_OrdenCompra();
      },
    });
  }
);

/*=============================================
 SELECCIONAR PRODUCTOS
=============================================*/
$(".btnTraerProducto").click(function () {
  var idProducto = $(this).attr("idProducto");
  var datos = new FormData();
  datos.append("idProducto", idProducto);
  
  $.ajax({
    url: "ajax/productos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#codigo" + numProducto).val(respuesta["codigo"]);
      $("#producto" + numProducto).val(respuesta["descripcion"]);
      $("#unidad" + numProducto).val(respuesta["unidad"]);
      cerrar();
      /*=============================================
        AGRUPAR PRODUCTOS EN FORMATO JSON
      =============================================*/
      listaProductos_OrdenCompra();
      editarProductos_OrdenCompra();
    },
  });
});

/*=============================================
 MODIFICAR LA CANTIDAD
=============================================*/
$(".formulario__OrdenCompra").on("change", function () {
  /*=============================================
     AGRUPAR PRODUCTOS EN FORMATO JSON
    =============================================*/
  listaProductos_OrdenCompra();
  editarProductos_OrdenCompra();
});

/*======================================
 LISTAR TODOS LOS PRODUCTOS
======================================**/
function listaProductos_OrdenCompra() {
  var listaProductos = [];
  var codigo = $(".codigoProducto");
  var descripcion = $(".descripcionProducto");
  var unidad = $(".unidadMedida");
  var cantidad = $(".cantidadProducto");

  for (var i = 0; i < descripcion.length; i++) {
    listaProductos.push({
      codigo: $(codigo[i]).val(),
      descripcion: $(descripcion[i]).val(),
      unidad: $(unidad[i]).val(),
      cantidad: $(cantidad[i]).val(),
    });   
  }
  $("#listarProductos_OrdenCompra").val(JSON.stringify(listaProductos));
}

/*======================================
 AGREGAR IDPROVEEDORES
======================================**/
function idProveedores() {
  var idProveedor = $("#idProveedor");
  $("#proveedorId").val(idProveedor);
}

/*======================================
 LISTAR TODOS LOS PROVEEDORES
======================================**/
function listaProveedor() {
  var listaProveedor = [];
  var idProveedor = $("#idProveedor");
  var tipoDocumento = $(".nuevoTipoDocumento_OrdenCompra");
  var documento = $(".nuevoDocumento_OrdenCompra");
  var proveedor = $(".nuevo-ProveedorOrdenCompra");

  for (var i = 0; i < proveedor.length; i++) {
    listaProveedor.push({
      id: $(idProveedor[i]).val(),
      tipoDocumento: $(tipoDocumento[i]).val(),
      documento: $(documento[i]).val(),
      nombre: $(proveedor[i]).val(),
    });
  }
  $("#listaProveedor").val(JSON.stringify(listaProveedor));
}

/*======================================
 VISUALIZAR ORDEN DE COMPRA
=======================================*/
$(".btnVerOrdenCompra").click(function () {
  var idOrden = $(this).attr("idOrden");
  var datos = new FormData();
  datos.append("idOrden", idOrden);

  $.ajax({
    url: "ajax/ordenes-compras.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      var proveedor = JSON.parse(respuesta["proveedor"]);
      var productos = JSON.parse(respuesta["productos"]);

      $("#verFechaEmision").val(respuesta["fecha"]);
      /*======================================
        LISTA DE PROVEEDORES
      =======================================*/
      $("#verCodigo_OrdenCompra").html(respuesta["codigo"].padStart(10, 0));
      $("#verCodigo_OrdenCompra").val(respuesta["codigo"].padStart(10, 0));

      $("#verProveedor").val(proveedor[0].nombre);
      $("#verTipoDocumento").val(proveedor[0].tipoDocumento);
      $("#verDocumento").val(proveedor[0].documento);

      /*======================================
        LISTA DE PRODUCTOS
      =======================================*/
      var tabla = document.querySelector('#verOrdenDeCompra');
      tabla.innerHTML =''
      for(let valor of productos){
        tabla.innerHTML += `
        <tr><td style="text-align: center; padding-left: 0;width: 5%;"><div class="input-group"><span class="input-group-addon" style="border: none;background-color: transparent;"><button type="button" class="btn btn-primary btn-xs" disabled><i class="fa fa-plus"></i></button></span></div></td><td style="text-align: center; padding-left: 0;width: 5%;"><div class="input-group"><span class="input-group-addon" style="border: none;background-color: transparent;"><button type="button" class="btn btn-danger btn-xs" disabled><i class="fa fa-times"></i></button></span></div></td><td style="text-align: center;width: 15%;"><input class="form-control" value="${ valor.codigo }" readonly></td><td style="text-align: center;width: 40%;"><input class="form-control" value="${ valor.descripcion }" readonly style="text-transform: uppercase;"></td><td style="text-align: center;width: 20%;"><input class="form-control" value="${ valor.unidad }" readonly style="text-transform: uppercase;"></td><td style="text-align: center; padding-left: 0;width: 10%;"><input type="text" class="form-control" value="${ valor.cantidad }" readonly style="border: none;"></td></tr>
        `
      }
    },
  });
});

/*======================================
 EDITAR ORDEN DE COMPRA
=======================================*/
$(".btnEditarOrdenCompra").click(function () {
  var idOrden = $(this).attr("idOrden");
  window.location = "index.php?ruta=editar-orden-compra&idOrden="+idOrden;
});

/*=============================================
 AGREGAR PROVEEDORES DESDE EL BOTON
=============================================*/
$(".formulario__OrdenCompra").on("click", "button.btnBuscarProveedores", function () {
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
      $(".editarProveedores").append(
        "" +
          '<input type="hidden" class="form-control" id="editar_idProveedor" name="editar_idProveedor">\n'
      );
      editarProveedorOrdenCompra();
    },
  });
});

/*=============================================
 EDITAR SELECCION PROVEEDOR
=============================================*/
$(".btnTraerProveedor").click(function () {
  var idProveedor = $(this).attr("idProveedor");
  var datos = new FormData();
  datos.append("idProveedor", idProveedor);

  $.ajax({
    url: "ajax/proveedores.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editar_idProveedor").val(respuesta["id_proveedor"]);
      $(".editarTipoDocumento_OrdenCompra").val(respuesta["tipo_documento"]);
      $(".editarDocumento_OrdenCompra").val(respuesta["documento"]);
      $(".editar-ProveedorOrdenCompra").val(respuesta["nombre"]);
      /*=============================================
        AGRUPAR PROVEEDOR EN FORMATO JSON
      =============================================*/
      editarProveedorOrdenCompra();
      /*======================================
      AGREGAR IDPROVEEDORES
      ======================================**/
      idProveedores();
      /*======================================
      CERRAR MODAL PROVEEDORES
      ======================================**/
      cerrar();
    }
  });
});

/*======================================
 EDITAR TODOS LOS PROVEEDORES
======================================**/
function editarProveedorOrdenCompra() {
  var editarListaProveedor = [];
  var idProveedor = $("#editar_idProveedor");
  var tipoDocumento = $(".editarTipoDocumento_OrdenCompra");
  var documento = $(".editarDocumento_OrdenCompra");
  var proveedor = $(".editar-ProveedorOrdenCompra");

  for (var i = 0; i < proveedor.length; i++) {
    editarListaProveedor.push({
      id: $(idProveedor[i]).val(),
      tipoDocumento: $(tipoDocumento[i]).val(),
      documento: $(documento[i]).val(),
      nombre: $(proveedor[i]).val(),
    });
  }
  $("#editarListaProveedor").val(JSON.stringify(editarListaProveedor));
  console.log("Proveedor: ", editarListaProveedor);
}

/*=====================================
 AGREGANDO PRODUCTO ORDEN DE COMPRA
======================================**/
var numProducto = 0;
$(".formulario__OrdenCompra").on(
  "click",
  "button.btnAgregarOrdenCompra",
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
        $(".editarOrdenCompra").append(
          "" +
            "<tr>\n" +
            "</td>\n" +
            '<td style="text-align: center; padding-left: 0;width: 5%;">\n' +
            '<div class="input-group">\n' +
            '<span class="input-group-addon" style="border: none;background-color: transparent;">\n' +
            '<button type="button" class="btn btn-primary btn-xs agregarProducto" data-toggle="modal" data-target="#modalAgregarProducto">\n' +
            '<i class="fa fa-plus"></i>\n' +
            "</button>\n" +
            "</span>\n" +
            "</div>\n" +
            "</td>\n" +
            '<td style="text-align: center; padding-left: 0;width: 5%;">\n' +
            '<div class="input-group">\n' +
            '<span class="input-group-addon" style="border: none;background-color: transparent;">\n' +
            '<button type="button" class="btn btn-danger btn-xs quitarProducto">\n' +
            '<i class="fa fa-times"></i>\n' +
            "</button>\n" +
            "</span>\n" +
            "</div>\n" +
            "</td>" +
            '<td style="text-align: center;width: 15%;">\n' +
            '<input class="form-control editarCodigoProducto" name="editarCodigoProducto" id="codigo' + numProducto + '" required readonly>\n' +
            "</td>\n" +
            '<td style="text-align: center;width: 40%;">\n' +
            '<input class="form-control editarDescripcionProducto" name="editarDescripcionProducto" id="producto' + numProducto + '" idProducto required readonly style="text-transform: uppercase;">\n' +
            "</td>\n" +
            '<td style="text-align: center;width: 20%;">\n' +
            '<input class="form-control editarUnidadMedida" name="editarUnidadMedida" id="unidad' + numProducto + '" required readonly style="text-transform: uppercase;">\n' +
            "</td>\n" +
            '<td style="text-align: center; padding-left: 0;width: 10%;">\n' +
            '<input type="text" class="form-control editarCantidadProducto" value="1" min="1" name="editarCantidadProducto" required style="border: none;">\n' +
            "</td>\n" +
            "</tr>"
        );
        /*=============================================
          AGRUPAR PRODUCTOS EN FORMATO JSON
        =============================================*/
        editarProductos_OrdenCompra();
      },
    });
  }
);

/*======================================
 EDITAR LISTA TODOS LOS PRODUCTOS
======================================**/
function editarProductos_OrdenCompra() {
  var editarListaProductos = [];
  var codigo = $(".editarCodigoProducto");
  var descripcion = $(".editarDescripcionProducto");
  var unidad = $(".editarUnidadMedida");
  var cantidad = $(".editarCantidadProducto");

  for (var i = 0; i < descripcion.length; i++) {
    editarListaProductos.push({
      codigo: $(codigo[i]).val(),
      descripcion: $(descripcion[i]).val(),
      unidad: $(unidad[i]).val(),
      cantidad: $(cantidad[i]).val(),
    });   
  }
  $("#editarListaProductos").val(JSON.stringify(editarListaProductos));
  console.log("Productos", editarListaProductos);
}


/*======================================
 ELIMINAR ORDEN DE COMPRA
=======================================*/
$(".btnEliminarOrdenCompra").click(function () {
  var idOrden = $(this).attr("idOrden");
  swal({
    title: "¿Esta Seguro de Borrar la Orden de Compra?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borras orden de compra",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=orden-compra&idOrden=" + idOrden;
    }
  });
});

/*======================================
 CERRAR MODAL AL SELECCIONAR PROVEEDORES
=======================================*/
function cerrar(){
  $("#modalBuscarProveedores").modal('hide');
  $("#modalAgregarProducto").modal('hide');
}

/*======================================
 NO DUPLICAR PRODUCTO
=======================================*/
$(".btnTraerProducto").click(
  function noDuplicar(){
  $(this).removeClass("btn-primary btnTraerProducto");
  $(this).addClass("disabled");
  $(this).prop('disabled', true);
});

/*======================================
 IMPRIMIR ORDEN DE COMPRA
=======================================*/
$(".tablas").on("click", ".btnImprimir_ODC", function () {
  var codigo = $(this).attr("codigo");
  window.open("extensions/tcpdf/pdf/orden-compra.php", "_blank");
})