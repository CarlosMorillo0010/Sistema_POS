/**=====================================
 EDITAR PERFIL
 ======================================**/
$(document).on("click", ".btnEditarPerfil", function(){
    var idPerfil = $(this).attr("idPerfil");
    var datos = new FormData();
    datos.append("idPerfil", idPerfil);
    $.ajax({
        url: "ajax/perfiles.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
            $("#editarPerfil").val(respuesta["perfil"]);
            $("#idPerfil").val(respuesta["id_perfil"]);
        }
    })

})

/** ELIMINAR PERFIL **/
$(document).on("click", ".btnEliminarPerfil", function(){
    var idPerfil = $(this).attr("idPerfil");
    Swal.fire({
        title: '¿Está seguro de borrar el perfil?',
        text: "¡Si no lo está puede cancelar la acción!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar perfil!'
    }).then((result)=>{
        if(result.value){
            window.location = "index.php?ruta=perfil&idPerfil="+idPerfil;
        }
    })
})