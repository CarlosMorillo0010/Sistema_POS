function initializeDateRangePicker() {
    
    // Lógica para leer la URL y establecer el estado inicial
    var urlParams = new URLSearchParams(window.location.search);
    var fechaInicialURL = urlParams.get('fechaInicial');
    var fechaFinalURL = urlParams.get('fechaFinal');

    var fechaInicio = moment().startOf('month');
    var fechaFin = moment().endOf('month');
    var textoBoton = "<span><i class='fa fa-calendar'></i> Rango de fecha</span>";

    if (fechaInicialURL !== null && fechaFinalURL !== null) {
        fechaInicio = moment(fechaInicialURL);
        fechaFin = moment(fechaFinalURL);
        textoBoton = "<span>" + fechaInicio.format('MMMM D, YYYY') + ' - ' + fechaFin.format('MMMM D, YYYY') + "</span>";
        
        var urlTxt = "view/modules/generar-txt-ventas.php?fechaInicial=" + fechaInicialURL + "&fechaFinal=" + fechaFinalURL;
        // Tenemos que buscar el botón dentro del contenedor de DataTables ahora
        $(".dt-buttons #btnGenerarTxtSeniat").attr("href", urlTxt).show(); 
    }
    
    $('#daterange-btn-ventas').html(textoBoton + ' <i class="fa fa-caret-down"></i>');

    // Inicialización del DateRangePicker
    $('#daterange-btn-ventas').daterangepicker({
        ranges: {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
            'Este mes': [moment().startOf('month'), moment().endOf('month')],
            'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: fechaInicio,
        endDate: fechaFin
    }, function(start, end) {
        var fechaInicial = start.format('YYYY-MM-DD');
        var fechaFinal = end.format('YYYY-MM-DD');
        window.location = "index.php?ruta=libro-ventas&fechaInicial=" + fechaInicial + "&fechaFinal=" + fechaFinal;
    });
}

$(document).ready(function() {
    $(".tablaLibro").DataTable({
        order: [[0, "desc"]],
        responsive: true,   
        scrollX: true,        

            dom: '<"row"<"col-sm-12 mb-1"B<"custom-buttons-container pull-right">>>' + // Ponemos los botones en su propia fila ancha
                '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                't' + // La tabla
                '<"row"<"col-sm-5"i><"col-sm-7"p>>', // Información y paginación
                
            buttons: [

                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                    titleAttr: 'Exportar a Excel',
                    className: 'btn btn-success'
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Imprimir',
                    titleAttr: 'Imprimir',
                    className: 'btn btn-warning'
                }
            ],
            "fnDrawCallback": function( oSettings ) {
            // Movemos nuestros botones personalizados al contenedor que creamos con 'dom'
            $("#customButtons2").appendTo(".custom-buttons-container");
            $("#customButtons2").removeClass("hidden"); // Los hacemos visibles
            },

            "initComplete": function( settings, json ) {

                var customButtons = $("#customButtons").clone().removeClass('hidden').children();

                $(".dt-buttons").prepend(customButtons);

                initializeDateRangePicker();
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

    $(".tablaLibro").on("click", ".btnCambiarEstado", function() {

        console.log("--- Botón de estado presionado ---");

        var botonEstado = $(this);
        // Leemos los tres data-attributes del botón
        var idVenta = botonEstado.data("id-venta");
        var estadoActual = botonEstado.data("estado-actual");
        var esNuevo = botonEstado.data("es-nuevo");

        console.log("ID Venta:", idVenta);
        console.log("Estado Actual:", estadoActual);
        console.log("¿Es un nuevo registro?:", esNuevo);

        // --- 1. DEFINIR LA ACCIÓN Y EL NUEVO ESTADO BASADO EN LA LÓGICA ---
        var accionParaBackend = "";
        var nuevoEstado = "";
        var textoConfirmacion = "";

        if (esNuevo === true && estadoActual === "Pagada") {
            
            accionParaBackend = "marcar_pendiente"; // Acción para el backend
            nuevoEstado = "Pendiente";
            textoConfirmacion = "Esto convertirá la venta en una cuenta por cobrar. ¿Estás seguro?";

        } else {
            
            accionParaBackend = "actualizar_estado";
            if (estadoActual === "Pagada") {
                nuevoEstado = "Pendiente";
            } else if (estadoActual === "Pendiente") {
                nuevoEstado = "Anulada";
            } else {
                nuevoEstado = "Pagada";
            }
            textoConfirmacion = `¿Quieres cambiar el estado a "${nuevoEstado}"?`;
        }

        // --- 2. MOSTRAR SWEETALERT DE CONFIRMACIÓN ---
        Swal.fire({
            title: '¿Estás seguro?',
            text: textoConfirmacion, // Usamos el texto dinámico
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                
                // --- 3. PREPARAR Y ENVIAR LA PETICIÓN AJAX ---
                var datos = new FormData();
                datos.append("idVenta", idVenta);
                datos.append("nuevoEstado", nuevoEstado); // El estado al que va a cambiar
                datos.append("accion", accionParaBackend); // La acción que el backend debe realizar

                $.ajax({
                    url: "ajax/ventas.ajax.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(respuesta) {
                        
                        if (respuesta.trim() === "ok") {
                            
                            // Si la acción fue crear un nuevo registro, es mejor recargar la página
                            // para que el atributo 'data-es-nuevo' se actualice a 'false'.
                            if (accionParaBackend === "marcar_pendiente") {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Venta marcada como pendiente!',
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                // Si solo fue una actualización, podemos hacerlo sin recargar
                                // --- Actualización visual ---
                                botonEstado.removeClass('label-warning label-success label-danger');
                                var nuevaClase = "";
                                if (nuevoEstado === "Pagada") { nuevaClase = "label-success"; } 
                                else if (nuevoEstado === "Pendiente") { nuevaClase = "label-warning"; } 
                                else { nuevaClase = "label-danger"; }
                                
                                botonEstado.addClass(nuevaClase);
                                botonEstado.html(nuevoEstado);
                                botonEstado.data("estado-actual", nuevoEstado);

                                Swal.fire(
                                    '¡Actualizado!',
                                    'El estado de la factura ha sido cambiado.',
                                    'success'
                                );
                                
                                // Recalculamos los totales sin recargar
                                actualizarCajasDeTotales();
                            }
                            
                        } else {
                            Swal.fire(
                                'Error',
                                'No se pudo actualizar el estado. Respuesta: ' + respuesta,
                                'error'
                            );
                        }
                    }
                });
            }
        });
    });

});

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

$(".tablaLibro").on("click", ".btnVerDetalleVenta", function() {

    var idVenta = $(this).data("id-venta");
    
    // Mostramos el spinner mientras cargan los datos
    $("#contenidoModalVenta").html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i><p>Cargando detalles...</p></div>');

    var datos = new FormData();
    datos.append("idVentaDetalle", idVenta);

    $.ajax({
        url: "ajax/ventas.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json", // Esperamos una respuesta JSON
        success: function(respuesta) {
            
            if (respuesta) {
                // Construimos el HTML con los datos recibidos
                var html = `
                    <div class="row">
                        <div class="col-xs-6">
                            <h4><strong>Cliente:</strong></h4>
                            <p>${respuesta.nombre}</p>
                            <p><strong>Documento:</strong> ${respuesta.tipo_documento}${respuesta.documento}</p>
                        </div>
                        <div class="col-xs-6 text-right">
                            <h4><strong>Factura Nº: ${respuesta.codigo_venta}</strong></h4>
                            <p><strong>Fecha:</strong> ${new Date(respuesta.fecha).toLocaleDateString('es-VE')}</p>
                            <p><strong>Nº Control:</strong> ${respuesta.numero_control || 'N/A'}</p>
                        </div>
                    </div>
                    <hr>
                    <h4><strong>Productos:</strong></h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Total.</th>
                            </tr>
                        </thead>
                        <tbody>`;
                
                // Iteramos sobre los productos
                respuesta.productos.forEach(function(producto) {
                    html += `<tr>
                                <td>${producto.descripcion}</td>
                                <td>${producto.cantidad}</td>
                                <td>${Number(producto.pvp_referencia).toLocaleString('es-VE', {minimumFractionDigits: 2})} USD.</td>
                                <td>${Number(producto.total).toLocaleString('es-VE', {minimumFractionDigits: 2})} USD.</td>
                             </tr>`;
                });

                html += `</tbody>
                    </table>
                    <hr>
                    <div class="text-center">
                        <p>EL TIPO DE CAMBIO (TASA BCV) PARA LA FECHA DE LA EMISION DE LA FACTURA ES: <strong>${respuesta.tasa_dia} Bs/USD</strong></p>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                        </div>
                        <div class="col-xs-6">
                            <table class="table">
                                 <tr>
                                    <th>Subtotal:</th>
                                    <td>${Number(respuesta.subtotal_bs).toLocaleString('es-VE', {minimumFractionDigits: 2})} Bs.</td>
                                </tr>
                                <tr>
                                    <th>IVA 16%:</th>
                                    <td>${Number(respuesta.iva_bs).toLocaleString('es-VE', {minimumFractionDigits: 2})} Bs.</td>
                                </tr>
                                <tr>
                                    <th><strong>Total (VES):</strong></th>
                                    <td><strong>${Number(respuesta.total_bs).toLocaleString('es-VE', {minimumFractionDigits: 2})} Bs.</strong></td>
                                </tr>
                                <tr>
                                    <th>Total (USD):</th>
                                    <td>${Number(respuesta.total_usd).toLocaleString('en-US', {style: 'currency', currency: 'USD'})}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                `;

                // Reemplazamos el spinner con el HTML construido
                $("#contenidoModalVenta").html(html);

            } else {
                 $("#contenidoModalVenta").html("<p class='text-danger'>No se pudo cargar la información de la venta.</p>");
            }
        }
    });

});