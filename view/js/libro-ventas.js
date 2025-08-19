$(".tablaLibro").DataTable({
  order: [[0, "desc"]],
  responsive: true,      // Soporte para dispositivos móviles
  scrollX: true,         // Scroll horizontal
  dom:  '<"row mb-2"<"col-sm-12"B<"custom-buttons-container pull-right">>>"' + 
        '<"row"<"col-sm-4"l><"col-sm-8"f>>' +
        't' +
        "<'row'<'col-sm-12'tr>>" +
        '<"row"<"col-sm-5"i><"col-sm-7"p>>',
  buttons: [
    {
      extend: 'excelHtml5',
      text: '<i class="fa fa-file-excel"></i>',
      titleAttr: 'Exportar a Excel',
      className: 'btn btn-success'
    },
    {
      extend: 'print',
      text: '<i class="fa fa-print"></i>',
      titleAttr: 'Imprimir',
      className: 'btn btn-warning'
    },
  ],
    "fnDrawCallback": function( oSettings ) {
            // Movemos nuestros botones personalizados al contenedor que creamos con 'dom'
        $("#customButtons").appendTo(".custom-buttons-container");
        $("#customButtons").removeClass("hidden"); // Los hacemos visibles
    },
   language: {
    sProcessing: "Procesando...",
    sLengthMenu: "Mostrar _MENU_ registros",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
    sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
    sInfoPostFix: "",
    sSearch: "Buscar:",
    sUrl: "",
    sInfoThousands: ",",
    sLoadingRecords: "Cargando...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "Siguiente",
      sPrevious: "Anterior",
    },
    oAria: {
      sSortAscending: ": Activar para ordenar la columna de manera ascendente",
      sSortDescending:
        ": Activar para ordenar la columna de manera descendente",
    },
  },

});

// Usamos .on() con delegación de eventos para que funcione con DataTables
$(".tablaLibro").on("click", ".btnCambiarEstado", function() {

    var botonEstado = $(this); // Guardamos la referencia al span que se clickeó
    var idVenta = botonEstado.data("idVenta");
    var estadoActual = botonEstado.data("estado-actual");
    
    // Lógica para determinar el nuevo estado en un ciclo
    var nuevoEstado = "";
    if (estadoActual === "Pendiente") {
        nuevoEstado = "Pagada";
    } else if (estadoActual === "Pagada") {
        nuevoEstado = "Anulada";
    } else { // Si es "Anulada" o cualquier otro estado, vuelve a "Pendiente"
        nuevoEstado = "Pendiente";
    }

    // Mostramos una confirmación antes de cambiar, especialmente para "Anulada"
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Quieres cambiar el estado a "${nuevoEstado}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            
            // Si el usuario confirma, procedemos con AJAX
            var datos = new FormData();
            datos.append("idVenta", idVenta);
            datos.append("nuevoEstado", nuevoEstado);

            $.ajax({
                url: "ajax/ventas.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function(respuesta) {
                    
                     if (respuesta.trim().includes("ok")) {
                        
                        // 1. Quitar clases de color antiguas
                        botonEstado.removeClass('label-warning label-success label-danger');

                        // 2. Añadir nueva clase de color y texto
                        var nuevaClase = "";
                        if (nuevoEstado === "Pagada") {
                            nuevaClase = "label-success";
                        } else if (nuevoEstado === "Pendiente") {
                            nuevaClase = "label-warning";
                        } else {
                            nuevaClase = "label-danger";
                        }
                        
                        botonEstado.addClass(nuevaClase);
                        botonEstado.html(nuevoEstado);
                        
                        // 3. Actualizar el atributo data-estado-actual para el próximo clic
                        botonEstado.data("estado-actual", nuevoEstado);

                        Swal.fire(
                            '¡Actualizado!',
                            'El estado de la factura ha sido cambiado.',
                            'success'
                        )
                        actualizarCajasDeTotales();
                    } else {
                        Swal.fire(
                            'Error',
                            'No se pudo actualizar el estado.',
                            'error'
                        );
                    }
                }
            });
        }
    });
});

$('#daterange-btn-ventas').daterangepicker(
  {
    ranges   : {
      'Hoy'       : [moment(), moment()],
      'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Últimos 7 días' : [moment().subtract(6, 'days'), moment()],
      'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
      'Este mes'  : [moment().startOf('month'), moment().endOf('month')],
      'Mes pasado'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment().startOf('month'), 
    endDate  : moment().endOf('month')
  },
  function (start, end) {
    $('#daterange-btn-ventas span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

    var fechaInicial = start.format('YYYY-MM-DD');
    var fechaFinal = end.format('YYYY-MM-DD');

    var urlTxt = "view/modules/generar-txt-ventas.php?fechaInicial=" + fechaInicial + "&fechaFinal=" + fechaFinal;
    
    // Asignamos la URL al botón y lo mostramos
    $("#btnGenerarTxtSeniat").attr("href", urlTxt).show();

    window.location = "index.php?ruta=libro-ventas&fechaInicial=" + fechaInicial + "&fechaFinal=" + fechaFinal;
  }
)

$(document).ready(function() {
    // Si la página ya cargó con fechas en la URL, también configuramos el botón
    var urlParams = new URLSearchParams(window.location.search);
    var fechaInicial = urlParams.get('fechaInicial');
    var fechaFinal = urlParams.get('fechaFinal');

    if (fechaInicial && fechaFinal) {
        var urlTxt = "view/modules/generar-txt-ventas.php?fechaInicial=" + fechaInicial + "&fechaFinal=" + fechaFinal;
        $("#btnGenerarTxtSeniat").attr("href", urlTxt).show();
    }
});

function actualizarCajasDeTotales() {

    var totalBaseImponible = 0;
    var totalIva = 0;
    var totalGeneralBs = 0;
    var totalGeneralUsd = 0;
    
    // Iteramos sobre cada fila de la tabla
    $(".tablaLibro tbody tr").each(function() {
        
        // 'this' se refiere a la fila <tr> actual
        var fila = $(this);

        // Obtenemos el estado de la venta de esta fila
        // Asumimos que el 'span' del estado tiene la clase 'btnCambiarEstado'
        var estado = fila.find('.btnCambiarEstado').text().trim();

        // Solo sumamos si el estado NO es "Anulada"
        if (estado !== "Anulada") {

            // Obtenemos el texto de cada celda (td), lo limpiamos y lo convertimos a número
            // Asumimos el orden de las columnas: 6=Base, 7=IVA, 8=Total VES, 9=Total USD
            var baseImponibleTexto = fila.find('td').eq(5).text();
            var ivaTexto = fila.find('td').eq(6).text();
            var totalBsTexto = fila.find('td').eq(7).text();
            var totalUsdTexto = fila.find('td').eq(8).text();
            
            // Función para limpiar el formato de moneda y convertir a número flotante
            function parseMoneda(texto) {
                if (typeof texto !== 'string') return 0;
                // Quita el símbolo 'Bs.' o '$', los puntos de miles y cambia la coma decimal por un punto
                return parseFloat(texto.replace(/[^0-9,-]+/g,"").replace(/\./g, '').replace(',', '.')) || 0;
            }
            
            totalBaseImponible += parseMoneda(baseImponibleTexto);
            totalIva += parseMoneda(ivaTexto);
            totalGeneralBs += parseMoneda(totalBsTexto);
            totalGeneralUsd += parseMoneda(totalUsdTexto);
        }
    });

    // Formateamos los resultados y los mostramos en las cajas
    const formatoUsd = { style: 'currency', currency: 'USD' };

    $('#totalBaseImponible').html(totalBaseImponible.toLocaleString('es-VE', { minimumFractionDigits: 2 }) + ' Bs.');
    $('#totalIva').html(totalIva.toLocaleString('es-VE', { minimumFractionDigits: 2 }) + ' Bs.');
    $('#totalGeneral').html(totalGeneralBs.toLocaleString('es-VE', { minimumFractionDigits: 2 }) + ' Bs.');
    $('#totalGeneralUsd').html(totalGeneralUsd.toLocaleString('en-US', formatoUsd));
}