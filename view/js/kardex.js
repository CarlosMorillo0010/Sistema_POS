$(document).ready(function() {
    // Inicializar Select2 para el buscador de productos
    $('#seleccionarProductoKardex').select2({
        placeholder: "Seleccione un producto",
        allowClear: true
    });

    // Inicializar el DateRangePicker
    $('#daterange-btn-kardex').daterangepicker({
        ranges: {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 Días': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 Días': [moment().subtract(29, 'days'), moment()],
            'Este Mes': [moment().startOf('month'), moment().endOf('month')],
            'Mes Anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        locale: {
            "format": "DD/MM/YYYY",
            "separator": " - ",
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Desde",
            "toLabel": "Hasta",
            "customRangeLabel": "Rango Personalizado",
            "daysOfWeek": ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            "monthNames": ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        }
    },
    function (start, end) {
        $('#daterange-btn-kardex span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        // Disparar la recarga de datos cuando cambia la fecha
        if ($('#seleccionarProductoKardex').val()) {
            $('.tablaKardex').DataTable().ajax.reload();
        }
    });

    // Inicializar DataTable
    var tablaKardex = $('.tablaKardex').DataTable({
        "ajax": {
            "url": "ajax/datatable-kardex.ajax.php",
            "type": "POST",
            "data": function(d) {
                // Enviar los filtros al servidor
                var idProducto = $('#seleccionarProductoKardex').val();
                var fechaInicial = $('#daterange-btn-kardex').data('daterangepicker').startDate.format('YYYY-MM-DD');
                var fechaFinal = $('#daterange-btn-kardex').data('daterangepicker').endDate.format('YYYY-MM-DD');

                d.id_producto = idProducto;
                d.fechaInicial = fechaInicial;
                d.fechaFinal = fechaFinal;
            }
        },
        "deferRender": true,
        "destroy": true,
        "processing": true,
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados para el producto y rango de fechas seleccionados.",
            "sEmptyTable": "Seleccione un producto para ver su kardex.",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar en reporte:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        "columnDefs": [
            { "className": "dt-body-right", "targets": [4, 5, 6, 7, 8, 9, 10, 11, 12] } // Alinear columnas numéricas a la derecha
        ]
    });

    // Recargar la tabla cuando se selecciona un producto
    $('#seleccionarProductoKardex').on('change', function() {
        if ($(this).val() !== "") {
            tablaKardex.ajax.reload();
        } else {
            tablaKardex.clear().draw();
        }
    });
});