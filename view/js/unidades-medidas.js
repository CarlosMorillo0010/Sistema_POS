const formUnidadesMedidas = document.getElementById("formUnidadesMedidas");
const inputsUnidadesMedidas = document.querySelectorAll(
  "#formUnidadesMedidas input"
);

const exprUnidadesMedidas = {
  medidas: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
  unidades: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
};

const validarFormularioUnidadesMedidas = (e) => {
  switch (e.target.name) {
    case "nuevoNombre":
      validarCamposUnidadesMedidas(
        exprUnidadesMedidas.medidas,
        e.target,
        "medida"
      );
      break;
    case "nuevaUnidad":
      validarCamposUnidadesMedidas(
        exprUnidadesMedidas.unidades,
        e.target,
        "unidad"
      );
      break;
  }
};

const camposUnidadesMedidas = {
  medidas: false,
  unidades: false,
};

const validarCamposUnidadesMedidas = (exprUnidadesMedidas, input, campo) => {
  if (exprUnidadesMedidas.test(input.value)) {
    document.getElementById(`${campo}`).classList.remove("input_incorrecto");
    document.getElementById(`${campo}`).classList.add("input_correcto");
    campos[campo] = true;
  } else {
    document.getElementById(`${campo}`).classList.add("input_incorrecto");
    document.getElementById(`${campo}`).classList.remove("input_correcto");
    campos[campo] = false;
  }
};

inputsUnidadesMedidas.forEach((input) => {
  input.addEventListener("keyup", validarFormularioUnidadesMedidas);
  input.addEventListener("blur", validarFormularioUnidadesMedidas);
});

/**=====================================
 EDITAR UNIDADES
 ======================================**/
$(".btnEditarUnidad").click(function () {
  var idUnidad = $(this).attr("idUnidad");
  var datos = new FormData();
  datos.append("idUnidad", idUnidad);

  $.ajax({
    url: "ajax/unidades.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editarNombre").val(respuesta["nombre"]);
      $("#idUnidad").val(respuesta["id_unidad"]);
      $("#editarUnidad").val(respuesta["unidad"]);
      $("#idUnidad").val(respuesta["id_unidad"]);
    },
  });
});

/**=====================================
 ELIMINAR UNIDADES
 ======================================**/
$(".btnEliminarUnidad").click(function () {
  var idUnidad = $(this).attr("idUnidad");

  swal({
    title: "¿Está seguro de borrar la und. de medida?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar unid. de medida!",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=unidades-medida&idUnidad=" + idUnidad;
    }
  });
});

/*======================================
 NO REPETIR UNIDADES
=======================================*/
$("#medida").keyup(function () {
  $(".alert").remove();
  var medida = $(this).val();
  var datos = new FormData();
  datos.append("validarMedida", medida);

  $.ajax({
    url: "ajax/unidades.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#medida")
          .parent()
          .after(
            '<div class="alert alert-danger" role="alert" style=" margin-top: 15px; width: 100%; z-index: 999;">Esta unidad de medida ya existe en la base de datos</div>'
          );
        $("#medida").val("");
      }
    },
  });
});
