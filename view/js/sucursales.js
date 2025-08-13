const formSucursales = document.getElementById("formSucursales");
const inputsSucursales = document.querySelectorAll("#formSucursales input");

const exprSucursales = {
  sucursales: /^[a-zA-ZÀ-ÿ\s]{1,40}$/, // Letras y espacios, pueden llevar acentos.
};

const validarFormularioSucursales = (e) => {
  switch (e.target.name) {
    case "nuevoNombre":
      validarCamposSucursales(exprSucursales.sucursales, e.target, "sucursal");
      break;
  }
};

const camposSucursales = {
  sucursales: false,
};

const validarCamposSucursales = (exprSucursales, input, campo) => {
  if (exprSucursales.test(input.value)) {
    document.getElementById(`${campo}`).classList.remove("input_incorrecto");
    document.getElementById(`${campo}`).classList.add("input_correcto");
    campos[campo] = true;
  } else {
    document.getElementById(`${campo}`).classList.add("input_incorrecto");
    document.getElementById(`${campo}`).classList.remove("input_correcto");
    campos[campo] = false;
  }
};

inputsSucursales.forEach((input) => {
  input.addEventListener("keyup", validarFormularioSucursales);
  input.addEventListener("blur", validarFormularioSucursales);
});

/**=====================================
 EDITAR SUCURSALES
 ======================================**/
$(".btnEditarSucursal").click(function () {
  var idSucursal = $(this).attr("idSucursal");
  var datos = new FormData();
  datos.append("idSucursal", idSucursal);

  $.ajax({
    url: "ajax/sucursales.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editarCodigo").val(respuesta["codigo"]);
      $("#idSucursal").val(respuesta["id_sucursal"]);
      $("#editarNombre").val(respuesta["nombre"]);
      $("#idSucursal").val(respuesta["id_sucursal"]);
    },
  });
});

/**=====================================
 ELIMINAR SUCURSALES
 ======================================**/
$(".btnEliminarSucursal").click(function () {
  var idSucursal = $(this).attr("idSucursal");

  swal({
    title: "¿Está seguro de borrar la sucursal?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar sucursal!",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=sucursales&idSucursal=" + idSucursal;
    }
  });
});

/*======================================
 NO REPETIR SUCURSALES
=======================================*/
$("#sucursal").keyup(function () {
  $(".alert").remove();
  var sucursal = $(this).val();
  var datos = new FormData();
  datos.append("validarSucursal", sucursal);

  $.ajax({
    url: "ajax/sucursales.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#sucursal")
          .parent()
          .after(
            '<div class="alert alert-danger" role="alert" style=" margin-top: 15px; width: 100%; z-index: 999;">Esta sucursal ya existe en la base de datos</div>'
          );
        $("#sucursal").val("");
      }
    },
  });
});
