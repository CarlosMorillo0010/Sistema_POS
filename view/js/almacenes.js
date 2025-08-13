const formAlmacenes = document.getElementById("formAlmacen");
const inputsAlmacenes = document.querySelectorAll("#formAlmacen input");

const exprAlmacenes = {
  almacen: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
};

const validarFormularioAlmacenes = (e) => {
  switch (e.target.name) {
    case "nuevoAlmacen":
      validarCamposAlmacences(exprAlmacenes.almacen, e.target, "almacen");
      break;
  }
};

const camposAlmacenes = {
  almacen: false,
};

const validarCamposAlmacences = (exprAlmacenes, input, campo) => {
  if (exprAlmacenes.test(input.value)) {
    document.getElementById(`${campo}`).classList.remove("input_incorrecto");
    document.getElementById(`${campo}`).classList.add("input_correcto");
    campos[campo] = true;
  } else {
    document.getElementById(`${campo}`).classList.add("input_incorrecto");
    document.getElementById(`${campo}`).classList.remove("input_correcto");
    campos[campo] = false;
  }
};

inputsAlmacenes.forEach((input) => {
  input.addEventListener("keyup", validarFormularioAlmacenes);
  input.addEventListener("blur", validarFormularioAlmacenes);
});

/**=====================================
 EDITAR ALMACEN
 ======================================**/
$(".btnEditarAlmacen").click(function () {
  var idAlmacen = $(this).attr("idAlmacen");
  var datos = new FormData();
  datos.append("idAlmacen", idAlmacen);
  $.ajax({
    url: "ajax/almacenes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editarAlmacen").val(respuesta["nombre"]);
      $("#idAlmacen").val(respuesta["id_almacen"]);
    },
  });
});

/**=====================================
 ELIMINAR ALMACEN
 ======================================**/
$(".btnEliminarAlmacen").click(function () {
  var idAlmacen = $(this).attr("idAlmacen");
  swal({
    title: "¿Está seguro de borrar el almacen?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar almacen!",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=almacenes&idAlmacen=" + idAlmacen;
    }
  });
});
/*======================================
 NO REPETIR ALMACENES
=======================================*/
$("#almacen").keyup(function () {
  $(".alert").remove();
  var almacen = $(this).val();
  var datos = new FormData();
  datos.append("validarAlmacen", almacen);

  $.ajax({
    url: "ajax/almacenes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#almacen")
          .parent()
          .after(
            '<div class="alert alert-danger" role="alert" style=" margin-top: 15px; width: 100%; z-index: 999;">Este almacen ya existe en la base de datos</div>'
          );
        $("#almacen").val("");
      }
    },
  });
});
