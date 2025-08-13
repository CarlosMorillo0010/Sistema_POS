$(document).ready(function() {

    // Previsualizar imagen del logo
    $(".imagenEmpresa").change(function() {
        var imagen = this.files[0];
        if (imagen["type"] != "image/jpeg" && imagen["type"] != "image/png") {
            $(".imagenEmpresa").val("");
            swal({
                title: "Error al subir la imagen",
                text: "¡La imagen debe estar en formato JPG o PNG!",
                type: "error",
                confirmButtonText: "¡Cerrar!"
            });
        } else if (imagen["size"] > 2000000) {
            $(".imagenEmpresa").val("");
            swal({
                title: "Error al subir la imagen",
                text: "¡La imagen no debe pesar más de 2MB!",
                type: "error",
                confirmButtonText: "¡Cerrar!"
            });
        } else {
            var datosImagen = new FileReader;
            datosImagen.readAsDataURL(imagen);
            $(datosImagen).on("load", function(event) {
                var rutaImagen = event.target.result;
                $(".previsualizar").attr("src", rutaImagen);
            })
        }
    });

    // Botón para actualizar la tasa del BCV
    $("#btnActualizarTasaBCV").click(function() {
        swal({
            title: 'Consultando Tasa del BCV...',
            text: 'Por favor, espere un momento.',
            allowOutsideClick: false,
            onOpen: () => {
                swal.showLoading();
            }
        });

        $.ajax({
            url: "ajax/tasa-cambio.ajax.php",
            method: "POST",
            dataType: "json",
            success: function(respuesta) {
                swal.close();

                if (respuesta.status === "success") {
                    swal({
                        type: 'success',
                        title: '¡Proceso Exitoso!',
                        text: respuesta.message,
                    }).then((result) => {
                        if (result.value) {
                            window.location.reload();
                        }
                    });
                } else if (respuesta.status === "info") {
                     swal({
                        type: 'info',
                        title: 'Información',
                        text: respuesta.message,
                    });
                } else {
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: respuesta.message,
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                swal({
                    type: 'error',
                    title: 'Error de Conexión',
                    text: 'No se pudo comunicar con el servidor. Por favor, revisa tu conexión a internet. (' + textStatus + ')',
                });
            }
        });
    });
});