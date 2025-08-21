$(document).ready(function() {

    // ===================================================================
    //  1. SELECCIÓN DE ELEMENTOS DEL MODAL (para no repetir código)
    // ===================================================================
    const modalPago = $('#modalRegistrarPago');
    const fechaInput = modalPago.find('input[name="pagoFecha"]');
    const monedaSelect = modalPago.find('#pagoMoneda');
    const tasaContainer = modalPago.find('#pagoTasaCambioContainer');
    const tasaInput = modalPago.find('input[name="pagoTasaCambio"]');


    // ===================================================================
    //  2. FUNCIÓN PARA BUSCAR LA TASA DE CAMBIO VÍA AJAX
    // ===================================================================
    function buscarTasa(fechaSeleccionada) {
        
        // Solo continuamos si hay una fecha y la moneda seleccionada es 'VES'
        if (!fechaSeleccionada || monedaSelect.val() !== 'VES') {
            tasaInput.val(""); // Limpiamos el campo si no aplica
            return; 
        }

        $.ajax({
            url: "ajax/tasa-cambio.php", // Asegúrate que esta ruta es correcta
            method: "POST",
            data: { 'fecha': fechaSeleccionada },
            dataType: "json",
            success: function(respuesta) {
                if (respuesta && respuesta.tasa) {
                    // Si el servidor encontró una tasa, la ponemos en el input
                    tasaInput.val(respuesta.tasa);
                } else {
                    // Si no, limpiamos el campo y avisamos al usuario
                    tasaInput.val("");
                    tasaInput.focus(); // Colocamos el cursor para que ingrese la tasa
                    // Opcional: Usar SweetAlert para una mejor experiencia
                    alert("No se encontró una tasa para la fecha seleccionada. Por favor, ingrésela manualmente.");
                }
            },
            error: function() {
                alert("Error al conectar con el servidor para obtener la tasa de cambio.");
            }
        });
    }


    // ===================================================================
    //  3. FUNCIÓN PARA MOSTRAR U OCULTAR EL CAMPO DE TASA
    // ===================================================================
    function gestionarVisibilidadTasa() {
        if (monedaSelect.val() === 'VES') {
            tasaContainer.slideDown('fast');
            tasaInput.prop('required', true);
            // Al cambiar a VES, intentamos buscar la tasa con la fecha que ya está puesta
            buscarTasa(fechaInput.val()); 
        } else {
            tasaContainer.slideUp('fast');
            tasaInput.prop('required', false);
            tasaInput.val(''); // Limpiamos el valor al ocultar
        }
    }

    // Ocultar por defecto al cargar la página
    tasaContainer.hide();
    tasaInput.prop('required', false);

    // "Escuchamos" los cambios en el selector de moneda y en el input de fecha
    monedaSelect.on('change', gestionarVisibilidadTasa);
    fechaInput.on('change', function() {
        buscarTasa($(this).val());
    });


    // ===================================================================
    //  4. TU CÓDIGO ORIGINAL, AHORA INTEGRADO CON LAS NUEVAS FUNCIONES
    // ===================================================================
    $(".tablas").on("click", ".btnRegistrarCobro", function() {
        
        // Obtenemos los datos del botón
        var idVenta = $(this).data("id-venta");
        var saldoPendiente = $(this).data("saldo-pendiente");
        var nombreCliente = $(this).data("cliente");
        
        // Obtenemos la fecha de hoy en formato YYYY-MM-DD
        var hoy = new Date().toISOString().slice(0, 10);
        
        // Limpiamos y preparamos el modal para un nuevo registro
        
        // Reseteamos la moneda a USD (o tu moneda por defecto)
        monedaSelect.val('USD'); 
        gestionarVisibilidadTasa(); // Esto ocultará el campo de tasa

        // Llenamos los campos del modal con la información de la venta
        $("#pagoIdVenta").val(idVenta);
        $("#pagoNombreCliente").text(nombreCliente);
        $("#pagoSaldoPendiente").text(parseFloat(saldoPendiente).toFixed(2));
        $("#pagoMonto").val(parseFloat(saldoPendiente).toFixed(2));
        
        // Ponemos la fecha de hoy
        fechaInput.val(hoy);
        fechaInput.trigger('change'); 

    });

});