const formServicios = document.getElementById("formServicio");
const inputsServicios = document.querySelectorAll("#formServicio input");

const exprServicios = {
  servicios: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
};

const validarFormularioServicios = (e) => {
  switch (e.target.name) {
    case "nuevoServicio":
      validarCamposServicios(exprServicios.servicios, e.target, "service");
      break;
  }
};

const camposServicios = {
  servicios: false,
};

const validarCamposServicios = (exprServicios, input, campo) => {
  if (exprServicios.test(input.value)) {
    document.getElementById(`${campo}`).classList.remove("input_incorrecto");
    document.getElementById(`${campo}`).classList.add("input_correcto");
    campos[campo] = true;
  } else {
    document.getElementById(`${campo}`).classList.add("input_incorrecto");
    document.getElementById(`${campo}`).classList.remove("input_correcto");
    campos[campo] = false;
  }
};

inputsServicios.forEach((input) => {
  input.addEventListener("keyup", validarFormularioServicios);
  input.addEventListener("blur", validarFormularioServicios);
});

/**=============================================
 SUBIENDO LA FOTO DEL SERVICIO
 =============================================**/

$(".nuevaImagen").change(function () {
  var imagen = this.files[0];

  /*=============================================
      VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
      =============================================*/
  if (imagen["type"] != "image/jpeg" && imagen["type"] != "image/png") {
    $(".nuevaImagen").val("");

    swal({
      title: "Error al subir la imagen",
      text: "¡La imagen debe estar en formato JPG o PNG!",
      type: "error",
      confirmButtonText: "¡Cerrar!",
    });
  } else if (imagen["size"] > 2000000) {
    $(".nuevaImagen").val("");

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
      $(".mostrar").attr("src", rutaImagen);
    });
  }
});

/**=====================================
 EDITAR SERVICIOS
 ======================================**/

$(".btnEditarServicio").click(function () {
  var idServicio = $(this).attr("idServicio");
  var datos = new FormData();
  datos.append("idServicio", idServicio);

  $.ajax({
    url: "ajax/servicios.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editarServicio").val(respuesta["servicio"]);
      $("#idServicio").val(respuesta["id_servicio"]);
    },
  });
});

/**=====================================
 ELIMINAR SERVICIOS
 ======================================**/

$(".btnEliminarServicio").click(function () {
  var idServicio = $(this).attr("idServicio");

  swal({
    title: "¿Está seguro de borrar el servicio?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar servicio!",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=servicios&idServicio=" + idServicio;
    }
  });
});
/*======================================
 NO REPETIR SERVICIOS
=======================================*/
$("#service").keyup(function () {
  $(".alert").remove();
  var service = $(this).val();
  var datos = new FormData();
  datos.append("validarService", service);

  $.ajax({
    url: "ajax/servicios.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#service")
          .parent()
          .after(
            '<div class="alert alert-danger" role="alert" style=" margin-top: 15px; width: 100%; z-index: 999;">Este servicio ya existe en la base de datos</div>'
          );
        $("#service").val("");
      }
    },
  });
});
