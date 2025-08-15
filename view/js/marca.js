const formMarcas = document.getElementById("formMarca");
const inputsMarcas = document.querySelectorAll("#formMarca input");

const exprMarcas = {
  marcas: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
};

const validarFormularioMarcas = (e) => {
  switch (e.target.name) {
    case "nuevaMarca":
      validarCamposMarcas(exprMarcas.marcas, e.target, "marca");
      break;
  }
};

const camposMarcas = {
  marcas: false,
};

const validarCamposMarcas = (exprMarcas, input, campo) => {
  if (exprMarcas.test(input.value)) {
    document.getElementById(`${campo}`).classList.remove("input_incorrecto");
    document.getElementById(`${campo}`).classList.add("input_correcto");
    campos[campo] = true;
  } else {
    document.getElementById(`${campo}`).classList.add("input_incorrecto");
    document.getElementById(`${campo}`).classList.remove("input_correcto");
    campos[campo] = false;
  }
};

inputsMarcas.forEach((input) => {
  input.addEventListener("keyup", validarFormularioMarcas);
  input.addEventListener("blur", validarFormularioMarcas);
});

/**=====================================
 EDITAR MARCAS
 ======================================**/
$(".btnEditarMarca").click(function () {
  var idMarca = $(this).attr("idMarca");
  var datos = new FormData();
  datos.append("idMarca", idMarca);
  $.ajax({
    url: "ajax/marcas.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editarMarca").val(respuesta["marca"]);
      $("#idMarca").val(respuesta["id_marca"]);
    },
  });
});

/**=====================================
 ELIMINAR MARCAS
 ======================================**/
$(".btnEliminarMarca").click(function () {
  var idMarca = $(this).attr("idMarca");
  Swal.fire({
    title: "¿Está seguro de borrar la marca?",
    text: "¡Si no lo está puede cancelar la acción!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar marca!",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=marcas&idMarca=" + idMarca;
    }
  });
});

/*======================================
 NO REPETIR MARCA
=======================================*/
$("#marca").keyup(function () {
  $(".alert").remove();
  var marca = $(this).val();
  var datos = new FormData();
  datos.append("validarMarca", marca);

  $.ajax({
    url: "ajax/marcas.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#marca")
          .parent()
          .after(
            '<div class="alert alert-danger" role="alert" style=" margin-top: 15px; width: 100%; z-index: 999;">Esta marca ya existe en la base de datos</div>'
          );
        $("#marca").val("");
      }
    },
  });
});
