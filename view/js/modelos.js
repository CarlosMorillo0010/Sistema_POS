const formModelos = document.getElementById("formModelo");
const inputsModelos = document.querySelectorAll("#formModelo input");

const exprModelos = {
  modelos: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]{1,40}$/,
};

const validarFormularioModelos = (e) => {
  switch (e.target.name) {
    case "nuevoModelo":
      validarCamposModelos(exprModelos.modelos, e.target, "modelo");
      break;
  }
};

const camposModelos = {
  modelos: false,
};

const validarCamposModelos = (exprModelos, input, campo) => {
  if (exprModelos.test(input.value)) {
    document.getElementById(`${campo}`).classList.remove("input_incorrecto");
    document.getElementById(`${campo}`).classList.add("input_correcto");
    campos[campo] = true;
  } else {
    document.getElementById(`${campo}`).classList.add("input_incorrecto");
    document.getElementById(`${campo}`).classList.remove("input_correcto");
    campos[campo] = false;
  }
};

inputsModelos.forEach((input) => {
  input.addEventListener("keyup", validarFormularioModelos);
  input.addEventListener("blur", validarFormularioModelos);
});

/**=====================================
 EDITAR MODELOS
 ======================================**/
$(".btnEditarModelo").click(function () {
  var idModelo = $(this).attr("idModelo");
  var datos = new FormData();
  datos.append("idModelo", idModelo);
  $.ajax({
    url: "ajax/modelos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editarModelo").val(respuesta["modelo"]);
      $("#idModelo").val(respuesta["id_modelo"]);
    },
  });
});

/**=====================================
 ELIMINAR MODELOS
 ======================================**/
$(".btnEliminarModelo").click(function () {
  var idModelo = $(this).attr("idModelo");
  Swal.fire({
    title: "¿Está seguro de borrar el modelo?",
    text: "¡Si no lo está puede cancelar la acción!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar modelo!",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=modelos&idModelo=" + idModelo;
    }
  });
});

/*======================================
 NO REPETIR MODELO
=======================================*/
$("#modelo").keyup(function () {
  $(".alert").remove();
  var modelo = $(this).val();
  var datos = new FormData();
  datos.append("validarModelo", modelo);

  $.ajax({
    url: "ajax/modelos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#modelo")
          .parent()
          .after(
            '<div class="alert alert-danger" role="alert" style=" margin-top: 15px; width: 100%; z-index: 999;">Esta modelo ya existe en la base de datos</div>'
          );
        $("#modelo").val("");
      }
    },
  });
});
