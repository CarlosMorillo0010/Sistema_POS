const formUser = document.getElementById("formUsuarios");
const inputsUser = document.querySelectorAll("#formUsuarios input");

const exprUser = {
  nombre: /^[a-zA-ZÀ-ÿ\s]{1,40}$/, // Letras y espacios, pueden llevar acentos.
  password: /^.{5,8}$/, // 5 a 8 digitos.
  cedula: /^([0-9]{8,8})$/, // 8 digitos.
  telefono: /^[\+]?[(]?[0-9]{4}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4}$/im,
  correo: /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i,
};

const validarFormularioUser = (e) => {
  switch (e.target.name) {
    case "nuevoDocumento":
      validarCamposUser(exprUser.cedula, e.target, "documento");
      break;
    case "nuevoNombre":
      validarCamposUser(exprUser.nombre, e.target, "nombre");
      break;
    case "password":
      validarCamposUser(exprUser.password, e.target, "password");
      validarRePassword();
      break;
    case "repassword":
      validarRePassword();
      break;
    case "nuevoTelefono":
      validarCamposUser(exprUser.telefono, e.target, "telefono");
      break;
    case "nuevoEmail":
      validarCamposUser(exprUser.correo, e.target, "email");
      break;
  }
};

const camposUser = {
  nombre: false,
  usuario: false,
  password: false,
  cedula: false,
  telefono: false,
  correo: false,
};

const validarCamposUser = (exprUser, input, campo) => {
  if (exprUser.test(input.value)) {
    document.getElementById(`${campo}`).classList.remove("input_incorrecto");
    document.getElementById(`${campo}`).classList.add("input_correcto");
    campos[campo] = true;
  } else {
    document.getElementById(`${campo}`).classList.add("input_incorrecto");
    document.getElementById(`${campo}`).classList.remove("input_correcto");
    campos[campo] = false;
  }
};

const validarRePassword = () => {
  const inputPassword = document.getElementById("password");
  const inputRePassword = document.getElementById("repassword");

  if (inputPassword.value !== inputRePassword.value) {
    document.getElementById("repassword").classList.add("input_incorrecto");
    document.getElementById("repassword").classList.remove("input_correcto");
    camposUser["password"] = false;
  } else {
    document.getElementById("repassword").classList.remove("input_incorrecto");
    document.getElementById("repassword").classList.add("input_correcto");
    camposUser["password"] = true;
  }
};

inputsUser.forEach((input) => {
  input.addEventListener("keyup", validarFormularioUser);
  input.addEventListener("blur", validarFormularioUser);
});

/*======================================
 EDITAR USUARIOS
=======================================*/
$(document).on("click", ".btnEditarUsuario", function(){
  var idUsuario = $(this).attr("idUsuario");
  var datos = new FormData();
  datos.append("idUsuario", idUsuario);

  $.ajax({
    url: "ajax/users.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editarNacionalidad").html(respuesta["nacionalidad"]);
      $("#editarNacionalidad").val(respuesta["nacionalidad"]);
      $("#editarDocumento").val(respuesta["documento"]);
      $("#editarNombre").val(respuesta["nombre"]);
      $("#passwordActual").val(respuesta["password"]);
      $("#editarTelefono").val(respuesta["telefono"]);
      $("#editarEmail").val(respuesta["correo_electronico"]);
      $("#editarGenero").html(respuesta["genero"]);
      $("#editarGenero").val(respuesta["genero"]);
      $("#editarPerfil").html(respuesta["perfil"]);
      $("#editarPerfil").val(respuesta["perfil"]);
    },
  });
});

/*======================================
 ACTIVAR USUARIOS
=======================================*/
$(document).on("click", ".btnActivar", function () {
  var idUsuario = $(this).attr("idUsuario");
  var estadoUsuario = $(this).attr("estadoUsuario");

  var datos = new FormData();
  datos.append("activarId", idUsuario);
  datos.append("activarUsuario", estadoUsuario);

  $.ajax({
    url: "ajax/users.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    success: function (respuesta) {},
  });
  if (estadoUsuario == 0) {
    $(this).removeClass("btn-success");
    $(this).addClass("btn-danger");
    $(this).html("Inactivo");
    $(this).attr("estadoUsuario", 1);
  } else {
    $(this).addClass("btn-success");
    $(this).removeClass("btn-danger");
    $(this).html("Activo");
    $(this).attr("estadoUsuario", 0);
  }
});
/*======================================
 NO REPETIR USUARIOS REGISTRADOS
=======================================*/
$("#documento").keyup(function () {
  $(".alert").remove();
  var documento = $(this).val();
  var datos = new FormData();
  datos.append("validarUsuario", documento);

  $.ajax({
    url: "ajax/users.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#documento")
          .parent()
          .after(
            '<div class="alert alert-danger" role="alert" style="position: absolute; top: 15px; left: 300px; width: 410px; z-index: 999;">El Número Documento ya existe en la Base de Datos</div>'
          );
        $("#documento").val("");
      }
    },
  });
});

/*======================================
 ELIMINAR USUARIOS
=======================================*/
$(document).on("click", ".btnEliminarUsuario", function(){

  var idUsuario = $(this).attr("idUsuario");
  Swal.fire({
    title: "¿Esta Seguro de Borrar el Usuario?",
    text: "¡Si no lo está puede cancelar la acción!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar usuario",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=config-usuarios&idUsuario=" + idUsuario;
    }
  });
});
