/*======================================
  VISUALIZAR PROVEEDOR
 ======================================**/
$(document).on("click", ".btnVerProveedor", function(){

    var idProveedor = $(this).attr("idProveedor");
    var datos = new FormData();
    datos.append("idProveedor", idProveedor);
    $.ajax({
        url: "ajax/proveedores.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {

            $("#verCodigo").val(respuesta["codigo"].padStart(10, 0));
            $("#verTipoPersona").val(respuesta["tipo_persona"]);
            $("#verTipoPersona").html(respuesta["tipo_persona"]);
            $("#verTipoDocumento").val(respuesta["tipo_documento"]);
            $("#verTipoDocumento").html(respuesta["tipo_documento"]);
            $("#verNumeroDocumento").val(respuesta["documento"]);
            $("#verProveedor").val(respuesta["nombre"]);
            $("#verTelefono").val(respuesta["telefono"]);
            $("#verDiasCredito").val(respuesta["dias_credito"]);
            $("#verEmail").val(respuesta["email"]);
            $("#verDireccion").val(respuesta["direccion"]);
            $("#verNota").val(respuesta["nota"]);
        }
    })
})

/*======================================
  EDITAR PROVEEDOR
 ======================================**/
$(document).on("click", ".btnEditarProveedor", function(){
    var idProveedor = $(this).attr("idProveedor");
    var datos = new FormData();
    datos.append("idProveedor", idProveedor);
    $.ajax({
        url: "ajax/proveedores.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {

            $("#idProveedor").val(respuesta["id_proveedor"]);
            $("#editarCodigo").val(respuesta["codigo"].padStart(10, 0));
            $("#editarTipoPersona").val(respuesta["tipo_persona"]);
            $("#editarTipoPersona").html(respuesta["tipo_persona"]);
            $("#editarTipoDocumento").val(respuesta["tipo_documento"]);
            $("#editarTipoDocumento").html(respuesta["tipo_documento"]);
            $("#editarNumeroDocumento").val(respuesta["documento"]);
            $("#editarProveedor").val(respuesta["nombre"]);
            $("#editarTelefono").val(respuesta["telefono"]);
            $("#editarDiasCredito").val(respuesta["dias_credito"]);
            $("#editarEmail").val(respuesta["email"]);
            $("#editarDireccion").val(respuesta["direccion"]);
            $("#editarNota").val(respuesta["nota"]);
        }
    })
})

/*======================================
  ELIMINAR PROVEEDOR
======================================**/
$(document).on("click", ".btnEliminarProveedor", function(){
    var idProveedor = $(this).attr("idProveedor");
    Swal.fire({
        title: '¿Está seguro de borrar el proveedor?',
        text: "¡Si no lo está puede cancelar la acción!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar proveedor!'
    }).then((result) => {
        if (result.value) {
            window.location = "index.php?ruta=proveedores&idProveedor=" + idProveedor;
        }
    })
})