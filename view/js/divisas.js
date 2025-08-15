$(document).on("click", "#btnActualizarTasaBCV", function() {

    var boton = $(this);
    // Deshabilitamos el botón y mostramos un feedback visual
    boton.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Consultando...');

    $.ajax({
        url: "ajax/configuracion.ajax.php",
        method: "POST",
        data: { accion: "actualizarTasaBCV" },
        dataType: "json",
        success: function(response) {
            if (response.status === "ok") {
                // ¡AQUÍ ESTÁ LA MAGIA!
                // Usamos nuestro gestor para actualizar la configuración globalmente
                window.APP_CONFIG.update(response.config);

                // Mostramos una notificación de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Actualizado!',
                    text: response.mensaje,
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    // Opcional: Recargar la página de divisas para ver la tabla actualizada.
                    // Si no quieres recargar, tendrías que actualizar la tabla con JS.
                    // Por simplicidad, una recarga aquí está bien.
                    window.location.reload(); 
                });

            } else {
                // Si la API falla, mostramos el error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.mensaje
                });
            }
        },
        error: function() {
            // Si la llamada AJAX falla
            Swal.fire({
                icon: 'error',
                title: 'Error de Conexión',
                text: 'No se pudo comunicar con el servidor. Intente de nuevo.'
            });
        },
        complete: function() {
            // Volvemos a habilitar el botón
             boton.prop('disabled', false).html('<i class="fa fa-refresh"></i> Consultar y Actualizar Tasa del BCV');
        }
    });

});