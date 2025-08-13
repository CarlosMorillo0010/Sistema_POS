const formPagos = document.getElementById("formFormasPago");
const inputsPagos = document.querySelectorAll("#formFormasPago input");

const exprPagos = {
  pagos: /^[a-zA-ZÀ-ÿ\s]{1,40}$/, // Letras y espacios, pueden llevar acentos.
};

const validarFormularioFormaPagos = (e) => {
  switch (e.target.name) {
    case "nuevaPago":
      validarCamposPagos(exprPagos.pagos, e.target, "pagos");
      break;
  }
};

const camposPagos = {
  pagos: false,
};

const validarCamposPagos = (exprPagos, input, campo) => {
  if (exprPagos.test(input.value)) {
    document.getElementById(`${campo}`).classList.remove("input_incorrecto");
    document.getElementById(`${campo}`).classList.add("input_correcto");
    campos[campo] = true;
  } else {
    document.getElementById(`${campo}`).classList.add("input_incorrecto");
    document.getElementById(`${campo}`).classList.remove("input_correcto");
    campos[campo] = false;
  }
};

inputsPagos.forEach((input) => {
  input.addEventListener("keyup", validarFormularioFormaPagos);
  input.addEventListener("blur", validarFormularioFormaPagos);
});

/*======================================
NO REPETIR FORMAS DE PAGO
=======================================*/
$("#pagos").keyup(function () {
  $(".alert").remove();
  var pagos = $(this).val();
  var datos = new FormData();
  datos.append("validarPago", pagos);

  $.ajax({
    url: "ajax/pagos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#pagos")
          .parent()
          .after(
            '<div class="alert alert-danger" role="alert" style="position: relative; top: 15px; width: 100%; z-index: 999;">La forma de pago ya existe en la Base de Datos</div>'
          );
        $("#pagos").val("");
      }
    },
  });
});

/**=====================================
EDITAR PAGOS
======================================**/
$(".btnEditarPago").click(function () {
  var idPago = $(this).attr("idPago");
  var datos = new FormData();
  datos.append("idPago", idPago);
  $.ajax({
    url: "ajax/pagos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editarPago").val(respuesta["forma_pago"]);
      $("#idPago").val(respuesta["id_forma_pagos"]);
    },
  });
});

/**=====================================
  ELIMINAR PAGOS
  ======================================**/
$(".btnEliminarPago").click(function () {
  var idPago = $(this).attr("idPago");
  swal({
    title: "¿Está seguro de borrar La forma de pago?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar forma de pago!",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=formas-pago&idPago=" + idPago;
    }
  });
});
