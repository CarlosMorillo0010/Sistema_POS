const formCategorias = document.getElementById("formCategoria");
const inputsCategorias = document.querySelectorAll("#formCategoria input");

const exprCategorias = {
  categorias: /^[a-zA-ZÀ-ÿ\s]{1,40}$/, // Letras y espacios, pueden llevar acentos.
};

const validarFormularioCategorias = (e) => {
  switch (e.target.name) {
    case "nuevaCategoria":
      validarCamposCategorias(exprCategorias.categorias, e.target, "categori");
      break;
  }
};

const camposCategorias = {
  categorias: false,
};

const validarCamposCategorias = (exprCategorias, input, campo) => {
  if (exprCategorias.test(input.value)) {
    document.getElementById(`${campo}`).classList.remove("input_incorrecto");
    document.getElementById(`${campo}`).classList.add("input_correcto");
    campos[campo] = true;
  } else {
    document.getElementById(`${campo}`).classList.add("input_incorrecto");
    document.getElementById(`${campo}`).classList.remove("input_correcto");
    campos[campo] = false;
  }
};

inputsCategorias.forEach((input) => {
  input.addEventListener("keyup", validarFormularioCategorias);
  input.addEventListener("blur", validarFormularioCategorias);
});

/**=============================================
 SUBIENDO LA FOTO DE LA CATEGORIA
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
  EDITAR CATEGORIAS
  ======================================**/
$(document).on("click", ".btnEditarCategoria", function(){
  var idCategoria = $(this).attr("idCategoria");
  var datos = new FormData();
  datos.append("idCategoria", idCategoria);
  $.ajax({
    url: "ajax/categories.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editarCategoria").val(respuesta["categoria"]);
      $("#idCategoria").val(respuesta["id_categoria"]);
    },
  });
});

/**=====================================
  ELIMINAR CATEGORIAS
  ======================================**/
$(document).on("click", ".btnEliminarCategoria", function(){
  var idCategoria = $(this).attr("idCategoria");
  swal({
    title: "¿Está seguro de borrar la categoría?",
    text: "¡Si no lo está puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar categoría!",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=categorias&idCategoria=" + idCategoria;
    }
  });
});

/*======================================
 NO REPETIR CATEGORIA
=======================================*/
$("#categori").keyup(function () {
  $(".alert").remove();
  var categori = $(this).val();
  var datos = new FormData();
  datos.append("validarCategori", categori);

  $.ajax({
    url: "ajax/categories.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#categori")
          .parent()
          .after(
            '<div class="alert alert-danger" role="alert" style=" margin-top: 15px; width: 100%; z-index: 999;">Esta categoria ya existe en la base de datos</div>'
          );
        $("#categori").val("");
      }
    },
  });
});
