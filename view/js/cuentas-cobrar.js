$(document).ready(function() {
    // --- LÓGICA PARA EL MODAL DE REGISTRAR PAGO ---

    // 1. Ocultar el campo de tasa de cambio al inicio
    $('#pagoTasaCambioContainer').hide();
    $('input[name="pagoTasaCambio"]').prop('required', false);


    // 2. Manejar el clic en el botón "Registrar Cobro" para poblar el modal
    $('.btnRegistrarCobro').on('click', function() {
        var idVenta = $(this).data('id-venta');
        var saldoPendiente = $(this).data('saldo-pendiente');
        var cliente = $(this).data('cliente');

        $('#pagoIdVenta').val(idVenta);
        $('#pagoSaldoPendiente').text(parseFloat(saldoPendiente).toFixed(2));
        $('#pagoNombreCliente').text(cliente);

        // Restablecer el formulario a su estado inicial cada vez que se abre
        $('#pagoMoneda').val('USD').trigger('change'); // Volver a USD y disparar el evento change
        $('#pagoMonto').val('');
        $('input[name="pagoFecha"]').val('');
        $('input[name="pagoMetodo"]').val('');
        $('input[name="pagoReferencia"]').val('');

    });

    // 3. Mostrar u ocultar el campo de tasa de cambio según la moneda seleccionada
    $('#pagoMoneda').on('change', function() {
        if ($(this).val() === 'VES') {
            $('#pagoTasaCambioContainer').slideDown(); // Muestra con una animación suave
            $('input[name="pagoTasaCambio"]').prop('required', true); // Hacer el campo de tasa de cambio requerido
        } else {
            $('#pagoTasaCambioContainer').slideUp(); // Oculta con una animación suave
            $('input[name="pagoTasaCambio"]').prop('required', false); // Quitar el 'required'
            $('input[name="pagoTasaCambio"]').val(''); // Limpiar el valor si se oculta
        }
    });
});