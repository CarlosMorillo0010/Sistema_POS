$('#seleccionarProductoKardex').select2({
    placeholder: "Seleccione un producto",
    allowClear: true
});

// Variables para almacenar el rango de fechas seleccionado
var fechaInicialKardex = moment().startOf('month').format('YYYY-MM-DD');
var fechaFinalKardex = moment().endOf('month').format('YYYY-MM-DD');

// Inicializar el texto del botón con el rango de fechas por defecto
$('#daterange-btn-kardex span').html(moment().startOf('month').format('MMMM D, YYYY') + ' - ' + moment().endOf('month').format('MMMM D, YYYY'));

var tablaKardex = $('.tablaKardex').DataTable({
    "ajax": {
        "url": "ajax/datatable-kardex.ajax.php",
        "type": "GET",
        "data": function (d) {
            d.idProducto = $('#seleccionarProductoKardex').val();
            d.fechaInicial = fechaInicialKardex;
            d.fechaFinal = fechaFinalKardex;
        },
        "dataSrc": function(json) {
            // No cargar datos si no hay producto seleccionado
            if ($('#seleccionarProductoKardex').val() === "" || $('#seleccionarProductoKardex').val() === null) {
                return [];
            }
            return json.data;
        }
    },
    "columns": [
        { "data": 0 }, // #
        { "data": 1 }, // Fecha
        { "data": 2 }, // Documento
        { "data": 3 }, // Concepto
        { "data": 4, "className": "text-right" }, // Ent Cant
        { "data": 5, "className": "text-right" }, // Ent Costo U
        { "data": 6, "className": "text-right" }, // Ent Costo T
        { "data": 7, "className": "text-right" }, // Sal Cant
        { "data": 8, "className": "text-right" }, // Sal Costo U
        { "data": 9, "className": "text-right" }, // Sal Costo T
        { "data": 10, "className": "text-right" },// Saldo Cant
        { "data": 11, "className": "text-right" },// Saldo Costo U
        { "data": 12, "className": "text-right" } // Saldo Costo T
    ],
    "language": {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Seleccione un producto para ver su kardex",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    },
    "responsive": true,
    "autoWidth": false,
    "ordering": false // Es importante mantener el orden cronológico que viene del servidor
});

// Recargar la tabla cuando se cambia el producto
$('#seleccionarProductoKardex').on('change', function(){
    tablaKardex.ajax.reload();
});

// Configuración del daterangepicker
$('#daterange-btn-kardex').daterangepicker(
    {
        ranges   : {
            'Hoy'       : [moment(), moment()],
            'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 días' : [moment().subtract(6, 'days'), moment()],
            'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
            'Este mes'  : [moment().startOf('month'), moment().endOf('month')],
            'Último mes'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().startOf('month'),
        endDate  : moment().endOf('month')
    },
    function (start, end) {
        // Actualizar el texto del botón
        $('#daterange-btn-kardex span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        
        // Actualizar las variables de fecha
        fechaInicialKardex = start.format('YYYY-MM-DD');
        fechaFinalKardex = end.format('YYYY-MM-DD');
        
        // Recargar la tabla si hay un producto seleccionado
        var idProducto = $('#seleccionarProductoKardex').val();
        if(idProducto){
            tablaKardex.ajax.reload();
        }
    }
);

