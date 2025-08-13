const formBancos = document.getElementById("formBanco");
const inputsBancos = document.querySelectorAll("#formBanco input");

const exprBancos = {
  bancos: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
};

const validarFormularioBancos = (e) => {
  switch (e.target.name) {
    case "nuevaBanco":
      validarCamposBancos(exprBancos.bancos, e.target, "banco");
      break;
  }
};

const camposBancos = {
  bancos: false,
};

const validarCamposBancos = (exprBancos, input, campo) => {
  if (exprBancos.test(input.value)) {
    document.getElementById(`${campo}`).classList.remove("input_incorrecto");
    document.getElementById(`${campo}`).classList.add("input_correcto");
    campos[campo] = true;
  } else {
    document.getElementById(`${campo}`).classList.add("input_incorrecto");
    document.getElementById(`${campo}`).classList.remove("input_correcto");
    campos[campo] = false;
  }
};

inputsBancos.forEach((input) => {
  input.addEventListener("keyup", validarFormularioBancos);
  input.addEventListener("blur", validarFormularioBancos);
});

/**=====================================
 EDITAR BANCOS
======================================**/
$(".btnEditarBanco").click(function () {
  var idBanco = $(this).attr("idBanco");
  var datos = new FormData();
  datos.append("idBanco", idBanco);

  $.ajax({
    url: "ajax/bancos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editarBanco").val(respuesta["banco"]);
      $("#idBanco").val(respuesta["id_banco"]);
    },
  });
});

/**=====================================
 ELIMINAR BANCOS
======================================**/
$(".btnEliminarBanco").click(function () {
  var idBanco = $(this).attr("idBanco");

  swal({
    title: "¿Está seguro de borrar el banco?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar banco!",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=bancos&idBanco=" + idBanco;
    }
  });
});

/*======================================
 NO REPETIR BANCO
=======================================*/
$("#banco").keyup(function () {
  $(".alert").remove();
  var banco = $(this).val();
  var datos = new FormData();
  datos.append("validarBanco", banco);

  $.ajax({
    url: "ajax/bancos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#banco")
          .parent()
          .after(
            '<div class="alert alert-danger" role="alert" style=" margin-top: 15px; width: 100%; z-index: 999;">Este banco ya existe en la base de datos</div>'
          );
        $("#banco").val("");
      }
    },
  });
});
