/**=====================================
VER CUENTA POR PAGAR
======================================**/
$(".btnVerCuentaPagar").click(function () {
  var idCuentaPagar = $(this).attr("idCuentaPagar");

  var datos = new FormData();
  datos.append("idCuentaPagar", idCuentaPagar);

  $.ajax({
    url: "ajax/cuentas-pagar.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#idCuentaPagar").val(respuesta.id_libro_compra);
      $("#verProveedor").val(respuesta.proveedor);
      $("#verNumFactura").val(respuesta.factura);
      $("#verMonto").val(respuesta.monto);
      $("#verIva").val(respuesta.iva);
      $("#verTotal").val(respuesta.total);
      $("#verFechaEmision").val(respuesta.fecha_emision);
      $("#verFechaVencimiento").val(respuesta.fecha_vencimiento);
      $("#verFechaPago").val(respuesta.fecha_pago);
      $("#verEstado").val(respuesta.estado);
      $("#verNotaPago").val(respuesta.nota_pago);
    },
  });
});

$(".table").on("click", ".btnPagarDeuda", function(){

    var idCuentaPagar = $(this).attr("idCuentaPagar");

    $("#idCuentaPagar").val(idCuentaPagar);

});

/**=====================================
 ELIMINAR CUENTAS POR PAGAR
======================================**/
$(".btnEliminarCuentaPagar").click(function () {
  var idCuentaPagar = $(this).attr("idCuentaPagar");

  Swal.fire({
    title: "¿Está seguro de borrar la cuenta?",
    text: "¡Si no lo está puede cancelar la acción!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar cuenta!",
  }).then((result) => {
    if (result.value) {
      window.location =
        "index.php?ruta=cuentas-pagar&idCuentaPagar=" + idCuentaPagar;
    }
  });
});

