const formImpuestos = document.getElementById("formImpuestos");
const inputsImpuestos = document.querySelectorAll("#formImpuestos input");

const exprImpuestos = {
  impuestos: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
  porcentaje: /^[0-9.]{4,6}$/,
};

const validarFormularioImpuestos = (e) => {
  switch (e.target.name) {
    case "nuevoImpuesto":
      validarCamposImpuestos(exprImpuestos.impuestos, e.target, "impuestos");
      break;
    case "nuevoPorcentaje":
      validarCamposImpuestos(exprImpuestos.porcentaje, e.target, "porcentaje");
      break;
  }
};

const camposImpuestos = {
  impuestos: false,
};

const validarCamposImpuestos = (exprImpuestos, input, campo) => {
  if (exprImpuestos.test(input.value)) {
    document.getElementById(`${campo}`).classList.remove("input_incorrecto");
    document.getElementById(`${campo}`).classList.add("input_correcto");
    campos[campo] = true;
  } else {
    document.getElementById(`${campo}`).classList.add("input_incorrecto");
    document.getElementById(`${campo}`).classList.remove("input_correcto");
    campos[campo] = false;
  }
};

inputsImpuestos.forEach((input) => {
  input.addEventListener("keyup", validarFormularioImpuestos);
  input.addEventListener("blur", validarFormularioImpuestos);
});

/**=====================================
 EDITAR IMPUESTO
 ======================================**/
$(".btnEditarImpuesto").click(function () {
  var idImpuesto = $(this).attr("idImpuesto");
  var datos = new FormData();
  datos.append("idImpuesto", idImpuesto);
  $.ajax({
    url: "ajax/impuestos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editarImpuesto").val(respuesta["impuesto"]);
      $("#idImpuesto").val(respuesta["id_impuesto"]);
      $("#editarPorcentaje").val(respuesta["porcentaje"]);
      $("#idImpuesto").val(respuesta["id_impuesto"]);
    },
  });
});

/**=====================================
 ELIMINAR IMPUESTO
 ======================================**/
$(".btnEliminarImpuesto").click(function () {
  var idImpuesto = $(this).attr("idImpuesto");
  swal({
    title: "¿Está seguro de borrar el impuesto?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar impuesto!",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=impuestos&idImpuesto=" + idImpuesto;
    }
  });
});
